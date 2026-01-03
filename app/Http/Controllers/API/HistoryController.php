<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Require authentication for all methods
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Store is not used for history (POST endpoint)
     */
    public function store(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'History tidak dapat dibuat melalui endpoint ini. Gunakan POST /predict untuk membuat history.',
        ], 400);
    }

    /**
     * Display all histories for the authenticated user (GET /histories)
     */
    public function index(Request $request)
    {
        $histories = History::where('user_id', $request->user()->id)
            ->with('ageClassification')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat prediksi berhasil diambil',
            'data' => $histories,
            'count' => $histories->count(),
        ]);
    }

    /**
     * Get a specific history record (GET /histories/{id})
     */
    public function show(Request $request, $id)
    {
        $history = History::where('user_id', $request->user()->id)
            ->with('ageClassification')
            ->find($id);

        if (!$history) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Riwayat berhasil diambil',
            'data' => $history,
        ]);
    }

    /**
     * Update a history record (PUT/PATCH /histories/{id})
     */
    public function update(Request $request, $id)
    {
        $history = History::where('user_id', $request->user()->id)->find($id);

        if (!$history) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat tidak ditemukan',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'age' => 'sometimes|integer|min:0|max:150',
            'email' => 'sometimes|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $history->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat berhasil diperbarui',
            'data' => $history->load('ageClassification'),
        ]);
    }

    /**
     * Delete a history record (DELETE /histories/{id})
     */
    public function destroy(Request $request, $id)
    {
        $history = History::where('user_id', $request->user()->id)->find($id);

        if (!$history) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat tidak ditemukan',
            ], 404);
        }

        $deletedHistory = $history;
        $history->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat berhasil dihapus',
            'data' => $deletedHistory,
        ]);
    }

    /**
     * Clear all histories for the authenticated user (DELETE /histories)
     */
    public function clearAll(Request $request)
    {
        $count = History::where('user_id', $request->user()->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Semua riwayat berhasil dihapus',
            'deleted_count' => $count,
        ]);
    }
}
