<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $projects = Project::where('user_id',$user->id)->get();

        return response()->json([
            'success' => true,
            'projects' => $projects,
            'message' => 'Retrieved successfully',
            'code' => 200
        ],200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()],400);
        }

        $project = new Project();
        $project->user_id = auth()->user()->id;
        $project->name = $request->name;
        $project->save();

        return response()->json([
            'success' => true,
            'project' => $project,
            'message' => 'Created successfully',
            'code' => 201
        ],201);
    }

    public function show($id)
    {
        $user = auth()->user();
        $project = Project::where('id',$id)
                ->where('user_id',$user->id)
                ->first();
       
        if(!$project) return response()->json(['message' => 'Not Found'],404);
        return response()->json([
            'success' => true,
            'project' => $project,
            'message' => 'Retrieved successfully',
            'code' => 200
        ],200);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $project = Project::where('id',$id)
                ->where('user_id',$user->id)
                ->first();

        if(!$project) return response()->json(['message' => 'Not Found'],404);
        
        $project->name = $request->name;
        $project->save();

        return response()->json([
            'success' => true,
            'project' => $project,
            'message' => 'Updated successfully',
            'code' => 200
        ],200);
    
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $project = Project::where('id',$id)
                ->where('user_id',$user->id)
                ->first();

        if(!$project) return response()->json(['message' => 'Not Found'],404);
        $project->delete();

        return response()->json([],204);
    }
}