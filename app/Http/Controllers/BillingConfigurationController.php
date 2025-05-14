<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BillingConfiguration;
use App\Models\BillingItem;
use App\Models\SchoolYear;

class BillingConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get active school year
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['message' => 'No active school year.'], 404);
        }

        // Only fetch billing configurations for the active school year
        $configs = BillingConfiguration::with(['billingItems', 'yearLevels', 'schoolYears'])
            ->where('schoolYearId', $activeSchoolYear->schoolYearId)
            ->get();

        return response()->json($configs);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate request data (removed schoolYearId)
            $validated = $request->validate([
                'yearLevelId' => 'required|exists:year_levels,yearLevelId',
                'amount' => 'required|numeric|min:0',
                'isRequired' => 'nullable|boolean',
                'billingItem.billItem' => 'required|string',
                'billingItem.category' => 'required|string',
                'billingItem.remarks' => 'nullable|string',
            ]);

            // Fetch the active school year
            $activeSchoolYear = SchoolYear::where('status', 1)->first();

            if (!$activeSchoolYear) {
                return response()->json([
                    'message' => 'No active school year found.',
                ], 404);
            }

            // Create or find the billing item
            $billingItem = BillingItem::firstOrCreate(
                ['billItem' => $validated['billingItem']['billItem']],
                [
                    'category' => $validated['billingItem']['category'],
                    'remarks' => $validated['billingItem']['remarks'] ?? null,
                ]
            );

            // Create the billing configuration
            $billingConfig = BillingConfiguration::create([
                'schoolYearId' => $activeSchoolYear->schoolYearId,
                'yearLevelId' => $validated['yearLevelId'],
                'billingItemId' => $billingItem->billingItemId,
                'amount' => $validated['amount'],
                'isRequired' => $validated['isRequired'] ?? true,
            ]);

            return response()->json([
                'message' => 'Billing configuration created successfully.',
                'data' => $billingConfig
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while processing your request.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $billingConfig = BillingConfiguration::find($id);

            if (!$billingConfig) {
                return response()->json([
                    'message' => 'Billing configuration not found.'
                ], 404);
            }

            // Validate only updatable fields
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0',
                'isRequired' => 'nullable|boolean',
            ]);

            $billingConfig->update([
                'amount' => $validated['amount'],
                'isRequired' => $validated['isRequired'] ?? true
            ]);

            return response()->json([
                'message' => 'Billing configuration updated successfully.',
                'data' => $billingConfig
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
