<?php

namespace App\Http\Controllers;

use App\Models\Upti;
use Illuminate\Http\Request;

class UptiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:uptis,name',
        ]);

        $upti = Upti::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'UPTI created successfully.',
            'upti' => $upti
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Upti $upti)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:uptis,name,' . $upti->id,
        ]);

        $upti->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'UPTI updated successfully.',
            'upti' => $upti
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Upti $upti)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized.');
        }

        // Before deleting, ensure no applications are assigned to this UPTI (or let DB handle it, or nullify)
        // Since we have nullOnDelete in DB, it will automatically set upti_id to null for related applications.
        $upti->delete();

        return response()->json([
            'success' => true,
            'message' => 'UPTI deleted successfully.'
        ]);
    }
}
