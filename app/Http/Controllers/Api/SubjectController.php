<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Subject;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub=Subject::all();
        return response()->json($sub);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sub=new Subject();
        $sub->id=$request->get('id');
        $sub->subject=$request->get('subject');
        $sub->day=$request->get('day');
        $sub->start=$request->get('start');
        $sub->end=$request->get('end');
        $sub->room_id=$request->get('room_id');
        $sub->timestart=$request->get('timestart');
        $sub->timeend=$request->get('timeend');
        $sub->save();
        return response()->json($sub);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sub = Subject::find($id);
        return response()->json($sub);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sub = Subject::find($id);
        $sub->subject=$request->get('subject');
        $sub->day=$request->get('day');
        $sub->start=$request->get('start');
        $sub->end=$request->get('end');
        $sub->room_id=$request->get('room_id');
        $sub->timestart=$request->get('timestart');
        $sub->timeend=$request->get('timeend');

        //$room->status=$request->get('status');
        $sub->update();
        return response()->json($sub);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub = Subject::find($id);
        $sub->delete();
        return response()->json($sub);
    }
}
