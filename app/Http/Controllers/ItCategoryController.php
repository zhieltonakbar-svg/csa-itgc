<?php

namespace App\Http\Controllers;

use App\Models\ItCategory;
use Illuminate\Http\Request;

class ItCategoryController extends Controller
{
    /**
     * Display a listing of the IT Categories for Admin management.
     */
    public function index()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $categories = ItCategory::orderBy('created_at', 'asc')->get();
        return view('it-category.index', compact('categories'));
    }

    /**
     * Store a newly created IT Category.
     * Admin only.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        $category = ItCategory::create([
            'name'        => $request->name,
            'description' => $request->description,
            'icon'        => $request->icon ?? 'bi-shield-lock',
        ]);

        // Automatically sync to all applications
        $applications = \App\Models\Application::all();
        foreach ($applications as $app) {
            $app->itCategories()->attach($category->id, ['completion_status' => 'not_complete']);
        }

        return response()->json([
            'success' => true,
            'message' => 'IT Category created successfully.',
            'category' => $category
        ]);
    }

    /**
     * Update an IT Category.
     */
    public function update(Request $request, ItCategory $it_category)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
        ]);

        $it_category->update($request->only(['name', 'description', 'icon']));

        return response()->json([
            'success' => true,
            'message' => 'IT Category updated successfully.',
            'category' => $it_category
        ]);
    }

    /**
     * Delete an IT Category.
     * Admin only.
     */
    public function destroy(ItCategory $it_category)
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        // Before deleting, detach from all applications to avoid orphaned pivot data
        $it_category->applications()->detach();
        $it_category->delete();

        return response()->json([
            'success' => true,
            'message' => "IT Category \"{$it_category->name}\" has been deleted.",
        ]);
    }
}
