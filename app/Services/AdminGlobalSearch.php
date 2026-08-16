<?php

namespace App\Services;

use App\Models\ContactMessage;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\Location;
use App\Models\MediaAsset;
use App\Models\NewsArticle;
use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\QuoteRequest;
use App\Models\Solution;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AdminGlobalSearch
{
    /**
     * @return array<int, array{group: string, title: string, subtitle: string, url: string, icon: string}>
     */
    public function search(string $query, User $user, int $limit = 8): array
    {
        $query = trim($query);

        if (strlen($query) < 1) {
            return [];
        }

        $results = collect()
            ->merge($this->searchNavigation($query, $user))
            ->merge($this->searchQuickActions($query, $user));

        if ($user->can('products.view')) {
            $results = $results
                ->merge($this->searchProducts($query, $limit))
                ->merge($this->searchCategories($query, $limit));
        }

        if ($user->can('quotes.view')) {
            $results = $results->merge($this->searchQuotes($query, $limit));
        }

        if ($user->can('messages.view')) {
            $results = $results->merge($this->searchMessages($query, $limit));
        }

        if ($user->can('content.view')) {
            $results = $results
                ->merge($this->searchSolutions($query, $limit))
                ->merge($this->searchNews($query, $limit))
                ->merge($this->searchPages($query, $limit));
        }

        if ($user->can('projects.view')) {
            $results = $results->merge($this->searchProjects($query, $limit));
        }

        if ($user->can('careers.view')) {
            $results = $results
                ->merge($this->searchJobs($query, $limit))
                ->merge($this->searchApplications($query, $limit));
        }

        if ($user->can('locations.view')) {
            $results = $results->merge($this->searchLocations($query, $limit));
        }

        if ($user->can('media.view')) {
            $results = $results->merge($this->searchMedia($query, $limit));
        }

        return $results
            ->unique(fn (array $item) => $item['url'].'|'.$item['title'])
            ->take(24)
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, array{group: string, title: string, subtitle: string, url: string, icon: string}>
     */
    private function searchNavigation(string $query, User $user): Collection
    {
        $pages = [
            ['title' => 'Dashboard', 'subtitle' => 'Operations overview', 'route' => 'admin.dashboard', 'permission' => null, 'icon' => 'fa-solid fa-gauge-high', 'keywords' => 'home overview'],
            ['title' => 'Products', 'subtitle' => 'Catalogue module', 'route' => 'admin.products.index', 'permission' => 'products.view', 'icon' => 'fa-solid fa-box-open', 'keywords' => 'catalogue sku'],
            ['title' => 'Categories', 'subtitle' => 'Product categories', 'route' => 'admin.categories.index', 'permission' => 'products.view', 'icon' => 'fa-solid fa-tags', 'keywords' => 'groups'],
            ['title' => 'Media library', 'subtitle' => 'Images and files', 'route' => 'admin.media.index', 'permission' => 'media.view', 'icon' => 'fa-solid fa-images', 'keywords' => 'upload assets photos'],
            ['title' => 'Quotations', 'subtitle' => 'Sales inbox', 'route' => 'admin.quotes.index', 'permission' => 'quotes.view', 'icon' => 'fa-solid fa-file-invoice-dollar', 'keywords' => 'rfq quotes sales'],
            ['title' => 'Messages', 'subtitle' => 'Contact inbox', 'route' => 'admin.messages.index', 'permission' => 'messages.view', 'icon' => 'fa-solid fa-envelope', 'keywords' => 'contact support'],
            ['title' => 'Solutions', 'subtitle' => 'Content module', 'route' => 'admin.solutions.index', 'permission' => 'content.view', 'icon' => 'fa-solid fa-diagram-project', 'keywords' => 'applications'],
            ['title' => 'Projects', 'subtitle' => 'Portfolio module', 'route' => 'admin.projects.index', 'permission' => 'projects.view', 'icon' => 'fa-solid fa-building', 'keywords' => 'case studies portfolio'],
            ['title' => 'News', 'subtitle' => 'Articles and media', 'route' => 'admin.news.index', 'permission' => 'content.view', 'icon' => 'fa-solid fa-newspaper', 'keywords' => 'press blog'],
            ['title' => 'Pages', 'subtitle' => 'Corporate pages', 'route' => 'admin.pages.index', 'permission' => 'content.view', 'icon' => 'fa-solid fa-file-lines', 'keywords' => 'about quality sustainability'],
            ['title' => 'Jobs', 'subtitle' => 'Career listings', 'route' => 'admin.jobs.index', 'permission' => 'careers.view', 'icon' => 'fa-solid fa-briefcase', 'keywords' => 'vacancies hiring'],
            ['title' => 'Applications', 'subtitle' => 'Candidate inbox', 'route' => 'admin.applications.index', 'permission' => 'careers.view', 'icon' => 'fa-solid fa-id-card', 'keywords' => 'cv applicants'],
            ['title' => 'Locations', 'subtitle' => 'Branches and plants', 'route' => 'admin.locations.index', 'permission' => 'locations.view', 'icon' => 'fa-solid fa-location-dot', 'keywords' => 'offices depots'],
            ['title' => 'Settings', 'subtitle' => 'Workspace configuration', 'route' => 'admin.settings.edit', 'permission' => 'settings.view', 'icon' => 'fa-solid fa-gear', 'keywords' => 'company seo stats'],
        ];

        return collect($pages)
            ->filter(fn (array $page) => ($page['permission'] === null || $user->can($page['permission']))
                && $this->matches($query, $page['title'], $page['subtitle'], $page['keywords']))
            ->map(fn (array $page) => [
                'group' => 'Pages & modules',
                'title' => $page['title'],
                'subtitle' => $page['subtitle'],
                'url' => route($page['route']),
                'icon' => $page['icon'],
            ]);
    }

    /**
     * @return Collection<int, array{group: string, title: string, subtitle: string, url: string, icon: string}>
     */
    private function searchQuickActions(string $query, User $user): Collection
    {
        $actions = [
            ['title' => 'Add product', 'subtitle' => 'Create catalogue item', 'route' => 'admin.products.create', 'permission' => 'products.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create product'],
            ['title' => 'Add category', 'subtitle' => 'Create product category', 'route' => 'admin.categories.create', 'permission' => 'products.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create category'],
            ['title' => 'Add solution', 'subtitle' => 'Create solution page', 'route' => 'admin.solutions.create', 'permission' => 'content.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create solution'],
            ['title' => 'Add project', 'subtitle' => 'Create portfolio project', 'route' => 'admin.projects.create', 'permission' => 'projects.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create project'],
            ['title' => 'Add article', 'subtitle' => 'Create news article', 'route' => 'admin.news.create', 'permission' => 'content.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create news article'],
            ['title' => 'Add page', 'subtitle' => 'Create corporate page', 'route' => 'admin.pages.create', 'permission' => 'content.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create page'],
            ['title' => 'Add job', 'subtitle' => 'Create job listing', 'route' => 'admin.jobs.create', 'permission' => 'careers.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create vacancy'],
            ['title' => 'Add location', 'subtitle' => 'Create location', 'route' => 'admin.locations.create', 'permission' => 'locations.view', 'icon' => 'fa-solid fa-plus', 'keywords' => 'new create branch'],
            ['title' => 'Upload media', 'subtitle' => 'Open media library', 'route' => 'admin.media.index', 'permission' => 'media.view', 'icon' => 'fa-solid fa-cloud-arrow-up', 'keywords' => 'upload image file'],
            ['title' => 'View website', 'subtitle' => 'Open public site', 'route' => 'home', 'permission' => null, 'icon' => 'fa-solid fa-globe', 'keywords' => 'public frontend site'],
        ];

        return collect($actions)
            ->filter(fn (array $action) => ($action['permission'] === null || $user->can($action['permission']))
                && $this->matches($query, $action['title'], $action['subtitle'], $action['keywords']))
            ->map(fn (array $action) => [
                'group' => 'Actions',
                'title' => $action['title'],
                'subtitle' => $action['subtitle'],
                'url' => route($action['route']),
                'icon' => $action['icon'],
            ]);
    }

    private function searchProducts(string $query, int $limit): Collection
    {
        return Product::query()
            ->with('category')
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(fn (Product $product) => [
                'group' => 'Products',
                'title' => $product->name,
                'subtitle' => trim(($product->category?->name ?: 'Uncategorised').($product->sku ? ' · '.$product->sku : '')),
                'url' => route('admin.products.edit', $product),
                'icon' => 'fa-solid fa-box-open',
            ]);
    }

    private function searchCategories(string $query, int $limit): Collection
    {
        return ProductCategory::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(fn (ProductCategory $category) => [
                'group' => 'Categories',
                'title' => $category->name,
                'subtitle' => 'Product category',
                'url' => route('admin.categories.edit', $category),
                'icon' => 'fa-solid fa-tags',
            ]);
    }

    private function searchQuotes(string $query, int $limit): Collection
    {
        return QuoteRequest::query()
            ->where(function ($builder) use ($query) {
                $builder->where('reference', 'like', "%{$query}%")
                    ->orWhere('name', 'like', "%{$query}%")
                    ->orWhere('company', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (QuoteRequest $quote) => [
                'group' => 'Quotations',
                'title' => $quote->reference,
                'subtitle' => trim($quote->name.($quote->company ? ' · '.$quote->company : '')),
                'url' => route('admin.quotes.show', $quote),
                'icon' => 'fa-solid fa-file-invoice-dollar',
            ]);
    }

    private function searchMessages(string $query, int $limit): Collection
    {
        return ContactMessage::query()
            ->where(function ($builder) use ($query) {
                $builder->where('subject', 'like', "%{$query}%")
                    ->orWhere('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (ContactMessage $message) => [
                'group' => 'Messages',
                'title' => $message->subject,
                'subtitle' => $message->name.' · '.$message->email,
                'url' => route('admin.messages.show', $message),
                'icon' => 'fa-solid fa-envelope',
            ]);
    }

    private function searchSolutions(string $query, int $limit): Collection
    {
        return Solution::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('headline', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(fn (Solution $solution) => [
                'group' => 'Solutions',
                'title' => $solution->name,
                'subtitle' => $solution->headline ?: 'Solution page',
                'url' => route('admin.solutions.edit', $solution),
                'icon' => 'fa-solid fa-diagram-project',
            ]);
    }

    private function searchProjects(string $query, int $limit): Collection
    {
        return Project::query()
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%")
                    ->orWhere('client', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%");
            })
            ->orderBy('title')
            ->limit($limit)
            ->get()
            ->map(fn (Project $project) => [
                'group' => 'Projects',
                'title' => $project->title,
                'subtitle' => trim(($project->location ?: 'Project').($project->year ? ' · '.$project->year : '')),
                'url' => route('admin.projects.edit', $project),
                'icon' => 'fa-solid fa-building',
            ]);
    }

    private function searchNews(string $query, int $limit): Collection
    {
        return NewsArticle::query()
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->latest('id')
            ->limit($limit)
            ->get()
            ->map(fn (NewsArticle $article) => [
                'group' => 'News',
                'title' => $article->title,
                'subtitle' => $article->is_published ? 'Published article' : 'Draft article',
                'url' => route('admin.news.edit', $article),
                'icon' => 'fa-solid fa-newspaper',
            ]);
    }

    private function searchPages(string $query, int $limit): Collection
    {
        return Page::query()
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('slug', 'like', "%{$query}%")
                    ->orWhere('headline', 'like', "%{$query}%");
            })
            ->orderBy('title')
            ->limit($limit)
            ->get()
            ->map(fn (Page $page) => [
                'group' => 'Pages',
                'title' => $page->title,
                'subtitle' => '/'.$page->slug,
                'url' => route('admin.pages.edit', $page),
                'icon' => 'fa-solid fa-file-lines',
            ]);
    }

    private function searchJobs(string $query, int $limit): Collection
    {
        return JobListing::query()
            ->where(function ($builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('department', 'like', "%{$query}%")
                    ->orWhere('location', 'like', "%{$query}%");
            })
            ->orderBy('title')
            ->limit($limit)
            ->get()
            ->map(fn (JobListing $job) => [
                'group' => 'Jobs',
                'title' => $job->title,
                'subtitle' => trim(($job->department ?: 'Role').($job->location ? ' · '.$job->location : '')),
                'url' => route('admin.jobs.edit', $job),
                'icon' => 'fa-solid fa-briefcase',
            ]);
    }

    private function searchApplications(string $query, int $limit): Collection
    {
        return JobApplication::query()
            ->with('jobListing')
            ->where(function ($builder) use ($query) {
                $builder->where('full_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('position', 'like', "%{$query}%");
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (JobApplication $application) => [
                'group' => 'Applications',
                'title' => $application->full_name,
                'subtitle' => $application->jobListing?->title ?: ($application->position ?: 'Applicant'),
                'url' => route('admin.applications.show', $application),
                'icon' => 'fa-solid fa-id-card',
            ]);
    }

    private function searchLocations(string $query, int $limit): Collection
    {
        return Location::query()
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('county', 'like', "%{$query}%")
                    ->orWhere('address', 'like', "%{$query}%");
            })
            ->orderBy('name')
            ->limit($limit)
            ->get()
            ->map(fn (Location $location) => [
                'group' => 'Locations',
                'title' => $location->name,
                'subtitle' => $location->county ?: 'Location',
                'url' => route('admin.locations.edit', $location),
                'icon' => 'fa-solid fa-location-dot',
            ]);
    }

    private function searchMedia(string $query, int $limit): Collection
    {
        return MediaAsset::query()
            ->where(function ($builder) use ($query) {
                $builder->where('original_name', 'like', "%{$query}%")
                    ->orWhere('filename', 'like', "%{$query}%")
                    ->orWhere('alt', 'like', "%{$query}%")
                    ->orWhere('folder', 'like', "%{$query}%");
            })
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (MediaAsset $media) => [
                'group' => 'Media',
                'title' => $media->original_name,
                'subtitle' => $media->folder.' · '.$media->collection,
                'url' => route('admin.media.index'),
                'icon' => 'fa-solid fa-images',
            ]);
    }

    private function matches(string $query, string ...$haystacks): bool
    {
        $needle = Str::lower($query);

        foreach ($haystacks as $haystack) {
            if (str_contains(Str::lower($haystack), $needle)) {
                return true;
            }
        }

        return false;
    }
}
