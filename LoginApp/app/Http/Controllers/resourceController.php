<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;



class resourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        // using Auth we take teacher Id
        //$teacherId = auth()->user()->teacher_id;
        
        // using Session . we take teacher Id
        $teacherId=session('teacher_id');
        $teacherName=session('teacher_name');

        $students = Student::where('teacher_id', $teacherId)->get();
        //$allstudentdata = Student::all();  // fetch all rows
    return view('studentsTable', ['students' => $students,'teacher'=>$teacherName]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('studentForm');
    }
            
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $teacherId=session('teacher_id');
        // create model object
        $student = new Student();
//        $teacherId = auth()->user()->teacher_id;

        $request->validate([
            'phonenumber' => 'required|digits:10'
        ]);

         //DB col name(KEY)  = (Value) Api request key from user/UI
            $student->name = $request->name;    // $request->input('name'); hey aasa pan use karu shakto.

            $student->teacher_id = $request->teacher_id;
            // $student->teacher_id = $teacherId; 
            $student->class = $request->class;
            $student->phonenumber = $request->phonenumber;
            $student->state = $request->state;
            $student->save();
        
        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
         
        if(session('teacher_id') == $student->teacher_id){
            //return $student;
              
            return view('studentsTable',['students'=>[$student],'teacher'=>session('teacher_name')]);
        }
        return redirect('/students');
        
    }
    public function editForm(Request $request){
        $student=Student::findOrFail($request->id);
        return view('updateStudent',['students'=>$student]);
    }
        public function viewForm(Request $request){
        $student=Student::findOrFail($request->id);
        return view('studentsTable',['students'=>[$student],'teacher'=>session('teacher_name')]);
    }

    
    // public function edit($id)
    // {
    //     $student = Student::find($id);
        
    //     if (!$student) {
    //         return redirect('/students')->with('unsuccess', 'Student not found.');
    //     }

    //     if(session('teacher_id') != $student->teacher_id){
    //         return redirect('/students');
    //     }

    //     // Convert model → array for Blade array syntax
    //     return view('updateStudent', ['students' => $student]); // runs when sucess
    // }

    /**
     * Update the specified resource in storage. Patch we are using here.
     */
    public function update(Request $request, $id)
    {
         $validated = $request->validate([
            'teacher_id' => 'required|exists:users,teacher_id', // :table_name,col_name
            'name' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'state' =>  'required|string|max:255',
            'phonenumber' => 'required|digits:10'     
        ]);

        $student = Student::findOrFail($id);
        $student->update($validated); // requires $fillable in model
        // Student::findOrFail($id)->update($request->all()); 

         return redirect('/students')->with('status','Student updated successfully');
        //return redirect()->route ('students.index')->with('status','Student updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
            return url('');
            //return redirect()->route('students.index')->with('status','Student deleted successfully');
        }

        return redirect()->route('students.index')->with('error','Student not found');
    }

    public function deleteAll(){
        Student::truncate();
        return redirect('/students');
    }

    /**
     * Delete a student via a temporary signed POST.
     * Expects a valid signature in the action URL and a one-time token in the POST body.
     */
    public function deleteViaLink(Request $request, $id)
    {
        // Validate the signed URL signature and expiry
        if (! $request->hasValidSignature()) {
            abort(403, 'Invalid or expired link.');
        }

        // Validate presence of one-time token
        $oneTime = $request->input('one_time_token');
        if (empty($oneTime)) {
            abort(403, 'Missing one-time token.');
        }

        // Attempt to pull and remove the token from cache (single-use)
        $cacheKey = 'delete_token:'. $oneTime;
        $payload = \Illuminate\Support\Facades\Cache::pull($cacheKey);

        if (! $payload || ! isset($payload['student_id']) || ! isset($payload['teacher_id'])) {
            abort(403, 'Invalid or expired one-time token.');
        }

        // Verify token binds to the requested student and teacher
        if ((int) $payload['student_id'] !== (int) $id) {
            abort(403, 'Token does not match student.');
        }

        if ((int) $payload['teacher_id'] !== (int) session('teacher_id')) {
            abort(403, 'Token not issued for this user.');
        }

        $student = Student::findOrFail($id);

        // Final ownership check (defense in depth)
        if (session('teacher_id') != $student->teacher_id) {
            abort(403);
        }

        $student->delete();

        return redirect()->route('students.index')->with('status', 'Student deleted successfully via signed link');
    }

}
