<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses=Course::withTrashed()->get();
        $course_type=CourseType::pluck('id','name');
        return view('master.course',compact('courses','course_type'));
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
            'course_name' => 'required|string|max:255',
            'course_fees' => 'required',
            'duration' => 'required',
            'course_type' => 'required',
           
        ],
        [
            'course_name.required' => 'Course Field is required',
            'course_fees.required' => 'Course Fees is required',     
            'duration.required' => 'Course Duration is required',
            'course_type.required' => 'Course Type is required',
            
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $data=$request->all();

      
        $data['is_active']=1;
     
        $course_fees=$data['course_fees'];
      
        try {
            if($data['id']==null){
                Course::create($data);
            }else{
                Course::findOrFail($data['id'])->update($data);
            }
            return response()->json(['success' => true, 'message' => 'Course Added/Updated successfully']);

           
        }catch(Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id=$request->id;
        $course=Course::findOrFail($id);
       
       
        return response()->json(['course' => $course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        
        $course = Course::find($request->id);
    
        if ($course) {
            $course->delete();
            return response()->json(['success' => true, 'message' => 'Course deleted successfully']);
           
        } else {
            return response()->json(['error' => true, 'message' => 'Course not deleted.... ']);
        }
    
    }
}
