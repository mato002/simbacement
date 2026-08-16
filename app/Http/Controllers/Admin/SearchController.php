<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminGlobalSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request, AdminGlobalSearch $search): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (strlen($query) < 1) {
            return response()->json(['results' => []]);
        }

        return response()->json([
            'results' => $search->search($query, $request->user()),
        ]);
    }
}
