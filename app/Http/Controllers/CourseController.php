<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException\ConflictException;
use App\Models\Course;
use App\Traits\RestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class CourseController extends Controller
{

    use RestResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //         "page" => "1"
        //   "size" => "3"
        //   "sort" => "id"
        //   "type_sort" => "asc"

        $page = $request->page;
        $size = $request->size;
        $sort = $request->sort;
        $type_sort = $request->type_sort;

        return $this->success(Course::orderBy($sort, $type_sort)->paginate($size));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       
       
        $course = new Course;
      
        $course->c_name=$request->c_name;
        $course->c_description=$request->c_description;
        $course->c_period=$request->c_period;
        $course->c_numberStudent=$request->c_numberStudent;
        $course->c_date_initial=$request->c_date_initial;
        $course->c_note_approved=$request->c_note_approved;
        $course->save();
        return $this->information(__('messages.success'));
        //return $this->success($course);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function show(Course $course)
    {
        $query = $course;

        if (!empty($this->relations)) {
            $query = $query->with($this->relations);
        }



        return $this->success($query->findOrFail($query->id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Course $course)
    {
        // $course = new Course;

        $course->fill($request->all());

        if ($course->isClean())
            return $this->information(__('messages.nochange'));

        $course->save();

        return $this->success($course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return $this->information(__('messages.delete'));
        // DB::beginTransaction();
        // try {
        //     $course->delete();

            
        //     return $this->information(__('messages.delete'));
        //     DB::commit();
        //     // return $this->success($course);
        // } catch (\Exception $ex) {
        //     DB::rollBack();
        //     throw new ConflictException($ex->getMessage());
        // }
    }
}
