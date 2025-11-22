<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProjectRepositoryInterface;
use App\Models\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function all()
    {
        return Project::all();
    }

    public function find($id)
    {
        return Project::find($id);
    }

    public function create(array $data)
    {
        return Project::create($data);
    }

    public function update($id, array $data)
    {
        $project = Project::find($id);
        if (! $project) return null;
        $project->update($data);
        return $project;
    }

    public function delete($id)
    {
        $project = Project::find($id);
        if (! $project) return false;
        return $project->delete();
    }
}
