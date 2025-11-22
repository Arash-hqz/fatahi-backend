<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        return ProjectResource::collection(Project::all());
    }

    public function store(CreateProjectRequest $request)
    {
        $data = $request->validated();
        $imageUrl = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $imageUrl = Storage::url($path);
        }

        $project = Project::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? null,
            'image' => $path ?? null,
        ]);

        $resp = new ProjectResource($project);
        $arr = $resp->toArray(request());
        $arr['imageUrl'] = $imageUrl;

        return response()->json($arr, 201);
    }

    public function show($id)
    {
        $project = Project::find($id);
        if (! $project) return response()->json(['message' => 'Not found'], 404);
        $resp = new ProjectResource($project);
        $arr = $resp->toArray(request());
        $arr['imageUrl'] = $project->image ? Storage::url($project->image) : null;
        return $arr;
    }

    public function update(Request $request, $id)
    {
        $project = Project::find($id);
        if (! $project) return response()->json(['message' => 'Not found'], 404);
        $project->update($request->all());
        return new ProjectResource($project);
    }

    public function destroy($id)
    {
        $project = Project::find($id);
        if (! $project) return response()->json(['message' => 'Not found'], 404);
        $project->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
