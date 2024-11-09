<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use Illuminate\Http\Request;

class LeadStatusController extends Controller
{
    public function index()
    {
        $leadStatus = LeadStatus::get();

        return response()->json([
            'status' => 'success',
            'data' => $leadStatus,
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'leads_id' => 'required|exists:leads,id',
            'master_status_id' => 'required|exists:master_status,id',
        ]);

        $leadStatus = LeadStatus::create($data);

        return response()->json([
            'status' => 'success',
            'data' => $leadStatus,
        ], 201);
    }
    public function destroy($id)
    {
        $leadStatus = LeadStatus::find($id);

        if (!$leadStatus) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead status not found',
            ], 404);
        }

        $leadStatus->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Lead status deleted successfully',
        ], 200);
    }
}
