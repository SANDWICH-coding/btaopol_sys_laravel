<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::with([
            'enrollment.student',
            'billingConfiguration.billingItems'
        ])->get();

        return response()->json([
            'status' => 'success',
            'data' => $payments
        ]);
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
        $validator = Validator::make($request->all(), [
            'enrollmentId' => 'required|exists:enrollments,enrollmentId',
            'paymentDate' => 'required|date',
            'receiptNumber' => 'required|string|unique:payments,receiptNumber',
            'paymentMethod' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'billingDetails' => 'required|array|min:1',
            'billingDetails.*.billingConfigId' => 'required|exists:billing_configurations,billingConfigId',
            'billingDetails.*.amountPaid' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $payments = [];

        foreach ($request->billingDetails as $detail) {
            $payments[] = Payment::create([
                'enrollmentId' => $request->enrollmentId,
                'paymentDate' => $request->paymentDate,
                'receiptNumber' => $request->receiptNumber,
                'billingConfigId' => $detail['billingConfigId'],
                'amountPaid' => $detail['amountPaid'],
                'paymentMethod' => $request->paymentMethod,
                'notes' => $request->notes,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Payment(s) recorded successfully.',
            'data' => $payments
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
