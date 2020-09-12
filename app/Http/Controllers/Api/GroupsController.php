<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

/**
 * Class GroupsController
 *
 * @package App\Http\Controllers\Api
 */
class GroupsController extends Controller
{
    public function index(Request $request)
    {
        return Group::with('tasks')->get();
    }
}
