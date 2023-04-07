<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Models\User;
use App\Traits\HttpResponse;

class TaskController extends Controller
{

    use HttpResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TaskResource::collection(
            Task::where('user_id', Auth::user()->id)->get()
        );
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $request->validated();

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority
        ]);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return $this->isAuthorized($task) ? $this->isAuthorized($task) : new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->isAuthorized($task);
        
        $task->update($request->all());

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        return $this->isAuthorized($task) ? $this->isAuthorized($task) :  $task->delete();
    }

    private function isAuthorized(Task $task)
    {
        if (auth()->user()->id != $task->user_id) {
            return $this->error(null, "You're not authorized to make request", 403);
        }
    }
}
