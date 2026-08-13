@extends('layouts.admin')

@section('title', 'News')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="section-label mb-2">Content</p>
            <h1 class="font-display text-4xl font-bold uppercase tracking-wide">News Articles</h1>
        </div>
        <a href="{{ route('admin.news.create') }}" class="btn-primary">Add article</a>
    </div>

    <div class="overflow-x-auto border border-line bg-white">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-line bg-mist text-xs uppercase tracking-wide text-steel">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($articles as $article)
                    <tr class="border-b border-line/70">
                        <td class="px-4 py-3 font-semibold">{{ $article->title }}</td>
                        <td class="px-4 py-3">{{ $article->category->label() }}</td>
                        <td class="px-4 py-3">{{ $article->is_published ? 'Published' : 'Draft' }}</td>
                        <td class="px-4 py-3 text-right"><a href="{{ route('admin.news.edit', $article) }}" class="font-semibold text-brand-deep hover:underline">Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-steel">No articles yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $articles->links() }}</div>
@endsection
