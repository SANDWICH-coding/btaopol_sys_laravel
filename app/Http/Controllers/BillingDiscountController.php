<?php

namespace App\Http\Controllers;

use App\Models\BillingDiscount;
use Illuminate\Http\Request;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Validator;

class BillingDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['message' => 'No active school year found.'], 404);
        }

        $discounts = BillingDiscount::where('schoolYearId', $activeSchoolYear->schoolYearId)->get();

        return response()->json([
            'message' => 'Billing discounts retrieved successfully.',
            'data' => $discounts,
        ], 200);
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
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['message' => 'No active school year found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'discountName' => 'required|string|max:255',
            'discountDiscription' => 'nullable|string|max:255',
            'discountType' => 'required|in:percentage,fixed',
            'discountValue' => 'required|numeric|min:0',
            'appliesTo' => 'required|in:Tuition,Registration,Miscellaneous,Books,Others',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $class = BillingDiscount::create([
            'schoolYearId' => $activeSchoolYear->schoolYearId,
            'discountName' => $request->discountName,
            'discountDiscription' => $request->discountDiscription,
            'discountType' => $request->discountType,
            'discountValue' => $request->discountValue,
            'appliesTo' => $request->appliesTo,
        ]);

        return response()->json([
            'message' => 'Discount added successfully',
            'data' => $class,
        ], 201);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
