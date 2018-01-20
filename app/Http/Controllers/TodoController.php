<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Todo::orderBy('id' , 'desc')->get();
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
        $request->validate([
            'task' => 'required|min:30'
        ]);

        Todo::create([
            'task' => $request->task,
            'done' => 0
        ]);
//        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $todo = Todo::find($id);
        $todo->task = $request->task;
        $todo->done = $request->done;
        $todo->save();
    }

    public function UpdateOrder(Request $request)
    {
        Todo::truncate();
        $reversed_arry = array_reverse($request->all());
        foreach ($reversed_arry as $todo)
        {
            Todo::create([
                'task' => $todo['task'],
                'done' => $todo['done']
            ]);
        }
    }

    public function makeDone($id)
    {
        $todo = Todo::find($id);
        $todo->done = 1;
        $todo->save();
    }
    public function makeUnDone($id)
    {
        $todo = Todo::find($id);
        $todo->done = 0;
        $todo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Todo::destroy($id);
    }
}
