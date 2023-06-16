<?php

namespace App\Http\Controllers;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    public function index()
    {
        return view('tasks.index');
    }

    public function get_tasks()
    {
        $tasks = Task::all();
        return response()->json([
            'tasks'=>$tasks,
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title'=> 'required|max:225|min:5',
            'description'=>'required|max:225|min:5',

        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }
        else
        {
            $input = $request->all();
            Task::create($input);

            return response()->json([
                'status'=>200,
                'message'=>'Task Added Successfully.'
            ]);
        }

    }

    public function edit($id)
    {

        $task = Task::find($id);
        if($task)
        {
            return response()->json([
                'status'=>200,
                'task'=> $task,
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Task Found.'
            ]);
        }

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'=> 'required|max:225|min:5',
            'description'=>'required|max:225|min:5',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }
        else
        {
            $task = Task::find($id);
            if($task)
            {
                $task->title = $request->title;
                $task->description = $request->description;
                $task->update();
                return response()->json([
                    'status'=>200,
                    'message'=>'Task Updated Successfully.'
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>404,
                    'message'=>'No Task Found.'
                ]);
            }

        }
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        if($task)
        {
            $task->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Task Deleted Successfully.'
            ]);
        }
        else
        {
            return response()->json([
                'status'=>404,
                'message'=>'No Task Found.'
            ]);
        }
    }
}



