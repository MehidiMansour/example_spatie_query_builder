<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest as StoreRequest;
use App\Http\Requests\ProjectRequest as UpdateRequest;
use App\Http\Resources\ProjectResource;


class ProjectController extends Controller
{
    public function index(Request $request)
    {
        return ProjectResource::collection(Project::all());
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(StoreRequest $request)
    {
        $validated = $request->validated();
        return new ProjectResource(Project::create($validated));
    }
    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Project $project)
    {
        return new ProjectResource($project);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Project $project)
    {
        $project->update($request->validated());
        return new ProjectResource($project);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Project $project)
    {
        $project->delete();
        return response()->json([
            'message' => 'Project deleted successfully'
        ], 200);
    }
}
