<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Task\CreateRequest;
use App\Http\Requests\Api\Task\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    public function index()
    {
        $data = Task::query()->get();
        return response()->json(['data' => $data]);
    }

    public function store(CreateRequest $createRequest)
    {  
        $request = $createRequest->validated();
        $result = $this->taskService->create($request);

        if ($result) {
            return new TaskResource($result);
        }

        return response()->json([
            'message' => 'error'

        ]);
    }

    public function show(Task $task)
    {
        return response()->json(['data' => new TaskResource($task)]);
    }

    public function update(UpdateRequest $updateRequest, Task $task)
    {
        $request = $updateRequest->validated();
        $result = $this->taskService->edit($task, $request);

        if ($result) {
            return new TaskResource($result);
        }

        return response()->json([
            'message' => 'error'

        ]);
    }

    // soft delete
    public function destroy(Task $task)
    {
        try {
            $task->delete();

            return response()->json([
                'message' => 'ok'
    
            ]);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception
    
            ]);
        }
    }
}
