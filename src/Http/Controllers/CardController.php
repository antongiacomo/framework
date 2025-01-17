<?php

namespace Taskday\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Taskday\Base\Filter;

use Taskday\Models\Card;
use Taskday\Models\Field;
use Taskday\Models\Project;
use Taskday\Models\Workspace;
use Taskday\Support\Page\Breadcrumb;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Inertia::render('Cards/Index', [
            'title' => 'Cards',
            'breadcrumbs' => [
                new Breadcrumb('Dashboard', route('dashboard')),
            ],
            'fields' => Field::query()
                ->orderBy('title')
                ->get(),
            'workspaces' => Workspace::query()
                ->select(['id', 'title'])
                ->orderBy('title')
                ->sharedWithCurrentUser()
                ->get(),
            'projects' => Project::query()
                ->select(['id', 'title'])
                ->orderBy('title')
                ->sharedWithCurrentUser()
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Workspace $workspace
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required'
        ]);

        $card = $project->cards()->create([
            'title' => $data['title'],
            'user_id' => Auth::id()
        ]);

        $fields = $request->validate(['fields' => 'nullable|array'])['fields'];

        foreach ($fields as $key => $value) {
            $card->setCustom(Field::where('handle', $key)->first(), $value);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param Workspace $workspace
     * @param Card $card
     * @return \Inertia\Response
     */
    public function show(Card $card)
    {
        $this->authorize('view', $card);

        $card->load(['comments.creator', 'fields', 'project.workspace.projects', 'project.fields']);

        return Inertia::render('Cards/Show', [
            'title' => Str::of($card->title)->replaceMatches('/.*?\//', '')->title(),
            'breadcrumbs' => $card->breadcrumbs,
            'workspace' => $card->project->workspace,
            'project' => $card->project,
            'card' => $card
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Workspace $workspace
     * @param Card $card
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  Card $card)
    {
        $data = array_filter($request->validate([
            'title' => 'nullable',
            'content' => 'nullable',
            'order' => 'nullable'
        ]));

        $fields = $request->validate(['fields' => 'nullable|array'])['fields'];

        foreach ($fields as $key => $value) {
            $card->setCustom(Field::where('handle', $key)->first(), $value);
        }

        $card->update($data);

        $card->touch();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Workspace $workspace
     * @param Card $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        $card->delete();

        return redirect()->back();
    }
}
