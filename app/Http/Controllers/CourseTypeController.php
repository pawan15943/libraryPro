<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course_type=CourseType::get();
        return view('master.course_type' , compact('course_type'));
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
            'name' => 'required|string|max:100',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $data=$request->all();
       
        $data['is_active']=1;
        try {
            if($data['id']==null){
                CourseType::create($data);
                $message='Course Type Added successfully';
            }else{
                CourseType::findOrFail($data['id'])->update($data);
                $message='Course Type Updated successfully';
            }
            return response()->json(['success' => true, 'message' =>$message ]);

           
        }catch(Exception $e){
            return response()->json(['error' => true, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CourseType $courseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id=$request->id;
        $grade=CourseType::findOrFail($id);
       
       
        return response()->json(['grade' => $grade]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseType $courseType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseType $courseType)
    {
        //
    }
}
