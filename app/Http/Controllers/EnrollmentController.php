<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\YearLevel;
use App\Models\ClassArm;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


$enrollmentNumber = strtoupper(Str::random(12));

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the active school year
        $activeSchoolYear = SchoolYear::where('status', 1)->first();

        if (!$activeSchoolYear) {
            return response()->json(['message' => 'No active school year found.'], 404);
        }

        // Get students who have enrollment in the active school year
        $students = Student::whereHas('enrollments', function ($query) use ($activeSchoolYear) {
            $query->where('schoolYearId', $activeSchoolYear->schoolYearId);
        })
            ->with([
                'enrollments' => function ($query) use ($activeSchoolYear) {
                    $query->where('schoolYearId', $activeSchoolYear->schoolYearId);
                }
            ])
            ->get();

        $yearLevels = YearLevel::with('classArms')->get();
        $classArms = ClassArm::all();

        return response()->json([
            'students' => $students,
            'yearLevels' => $yearLevels,
            'classArms' => $classArms,
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
        Log::info('Received enrollment data:', $request->all());

        $validator = Validator::make($request->all(), [
            'studentId' => 'required_if:enrollmentType,old/continuing|exists:students,studentId',
            'lrn' => 'nullable',
            'firstName' => 'required_if:enrollmentType,new,transferee|string|max:255',
            'middleName' => 'nullable|string|max:255',
            'lastName' => 'required_if:enrollmentType,new,transferee|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'profilePhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            'yearLevelId' => 'required|exists:year_levels,yearLevelId',
            'classArmId' => 'required|exists:class_arms,classArmId',
            'enrollmentType' => 'required|in:new,transferee,old/continuing',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        $validated = $validator->validated();

        DB::beginTransaction();

        try {
            $activeSchoolYear = SchoolYear::where('status', 1)->first();

            if (!$activeSchoolYear) {
                return response()->json([
                    'message' => 'No active school year found.'
                ], 422);
            }

            // Handle file upload if any
            $profilePhotoPath = null;
            if ($request->hasFile('profilePhoto')) {
                $file = $request->file('profilePhoto');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profile_photos', $filename);
                $profilePhotoPath = 'storage/profile_photos/' . $filename;
            }

            if ($validated['enrollmentType'] === 'old/continuing') {
                // Use existing student
                $student = Student::findOrFail($validated['studentId']);
            } else {
                // Create new student
                $student = Student::create([
                    'lrn' => $validated['lrn'] ?? null,
                    'firstName' => $validated['firstName'],
                    'middleName' => $validated['middleName'] ?? null,
                    'lastName' => $validated['lastName'],
                    'suffix' => $validated['suffix'] ?? null,
                    'profilePhoto' => $profilePhotoPath,
                ]);
            }

            Log::info('Student:', $student->toArray());

            // Generate enrollment number
            $enrollmentNumber = strtoupper(Str::random(12));

            // Create enrollment
            $enrollment = Enrollment::create([
                'enrollmentNumber' => $enrollmentNumber,
                'schoolYearId' => $activeSchoolYear->schoolYearId,
                'yearLevelId' => $validated['yearLevelId'],
                'classArmId' => $validated['classArmId'],
                'studentId' => $student->studentId,
                'enrollmentType' => $validated['enrollmentType'],
            ]);

            Log::info('Enrollment created:', $enrollment->toArray());

            DB::commit();

            return response()->json([
                'message' => 'Student enrolled successfully.',
                'student' => $student,
                'enrollment' => $enrollment,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enrollment error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Enrollment failed.',
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
