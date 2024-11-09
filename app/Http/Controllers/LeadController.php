<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Models\UserData;
use Illuminate\Http\Request;
use \Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::latest()
            ->with('salesperson:id,name')
            ->paginate(5);

        return response()->json([
            'status' => 'success',
            'data' => $leads
        ], 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:leads,email',
                'phone' => 'required|string',
            ]);

            $salespersons = UserData::where('role_id', 3)
                ->where(function ($query) {
                    $query->whereNull('punish_date')
                        ->orWhere('punish_date', '<=', now());
                })
                ->withCount(['leads' => function ($query) {
                    $query->whereHas('leadStatus', function ($query) {
                        $query->where('master_status_id', '<>', 8);
                    });
                }])
                ->orderBy('leads_count')
                ->get();

            if ($salespersons->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No available salespersons',
                ], 400);
            }

            $assignedSalesperson = $salespersons->first();

            $leadData = array_merge($data, [
                'salesperson_id' => $assignedSalesperson->id,
            ]);

            $lead = Lead::create($leadData);

            return response()->json([
                'status' => 'success',
                'message' => 'Lead created successfully',
                'data' => $lead,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while creating lead',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $lead = Lead::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => $lead
        ], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string',
                'phone' => 'required|string',
                'salesperson_id' => 'nullable|exists:users,id',
            ]);

            $lead = Lead::findOrFail($id); // Menemukan lead berdasarkan ID
            $lead->update($data); // Update lead dengan data yang baru

            return response()->json([
                'status' => 'success',
                'message' => 'Lead updated successfully',
                'data' => $lead
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lead not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while updating lead',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Lead deleted successfully',
        ], 201);
    }
}
