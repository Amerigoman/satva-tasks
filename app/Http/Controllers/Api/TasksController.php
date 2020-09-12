<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, Response};

/**
 * Class TasksController
 *
 * @package App\Http\Controllers
 */
class TasksController extends Controller
{
    /**
     * @return Task[]
     */
    public function index()
    {
        return Task::all();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return Task::create($request->all());
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return Task::findOrFail($id);
    }

    /**
     * @param Request $request
     * @param         $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());

        return $task;
    }

    /**
     * @param $id
     * @return Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return new Response('');
    }
}
