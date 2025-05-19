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
            'billingDiscountIds' => 'required|array',
            'billingDiscountIds.*' => 'exists:billing_discounts,billingDiscountId',
        ]);

        $enrollmentId = $validated['enrollmentId'];
        $discountIds = $validated['billingDiscountIds'];

        // Get the enrollment model
        $enrollment = Enrollment::findOrFail($enrollmentId);

        // Attach discounts (replaces existing if needed)
        $enrollment->billingDiscounts()->sync($discountIds); // or ->attach() to add without removing

        return response()->json([
            'message' => 'Discounts applied to enrollment successfully.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $discounts = BillingDiscountEnrollment::with('billingDiscount')
            ->where('enrollmentId', $id)
            ->get();

        return response()->json($discounts);
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
