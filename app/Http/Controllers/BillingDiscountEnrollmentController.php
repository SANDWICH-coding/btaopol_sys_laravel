<?php

namespace App\Http\Controllers;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Models\BillingDiscountEnrollment;

class BillingDiscountEnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BillingDiscountEnrollment::with(['enrollment', 'billingDiscount'])->get();

        return response()->json($data);
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
        $validated = $request->validate([
            'enrollmentId' => 'required|exists:enrollments,enrollmentId',
            'billingDiscountIds' => 'array', // Make it optional and allow empty arrays
            'billingDiscountIds.*' => 'exists:billing_discounts,billingDiscountId',
        ]);

        $enrollmentId = $validated['enrollmentId'];
        $discountIds = $validated['billingDiscountIds'] ?? [];

        // Get the enrollment model
        $enrollment = Enrollment::findOrFail($enrollmentId);

        // Sync (adds, updates, or removes all depending on array content)
        $enrollment->billingDiscounts()->sync($discountIds); // Empty array removes all

        return response()->json([
            'message' => 'Discounts updated successfully.',
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $discounts = BillingDiscountEnrollment::where('enrollmentId', $id)
            ->pluck('billingDiscountId'); // This returns array of IDs

        return response()->json([
            'message' => 'Applied discounts retrieved successfully.',
            'data' => $discounts,
        ]);
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
