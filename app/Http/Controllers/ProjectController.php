<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::orderBy('sort_order')->orderBy('created_at', 'desc');

        if ($request->category) {
            $query->where('category', $request->category);
        }

        $projects = $query->paginate(9);
        $categories = Project::distinct()->pluck('category')->filter()->values();

        return view('projects.index', compact('projects', 'categories'));
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->firstOrFail();

        $related = Project::where('category', $project->category)
            ->where('id', '!=', $project->id)
            ->take(3)
            ->get();

        return view('projects.show', compact('project', 'related'));
    }
}
