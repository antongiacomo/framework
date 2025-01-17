<?php

namespace Taskday\Http\Controllers;

use Taskday\Models\Field;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Taskday\Support\Page\Breadcrumb;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Fields/Index', [
            'title' => 'Fields',
            'breadcrumbs' => [
                new Breadcrumb('Dashboard', route('dashboard')),
            ],
            'fields' => Field::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Fields/Create', [
            'title' => 'New Field',
            'breadcrumbs' => [
                [ 'name' =>  'Dashboard',                'href' => route('dashboard') ],
                [ 'name' =>  'Fields' ]
            ],
            'fields' => Field::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'handle' => 'required',
            'type' => 'required',
            'options' => 'nullable'
        ]);

        Field::create($data);

        return redirect()->route('fields.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Inertia::render('Fields/Edit', [
            'field' => Field::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field)
    {
        $data = $request->validate([
            'title' => 'sometimes|required',
            'handle' => 'sometimes|required',
            'type' => 'sometimes|required',
            'options' => 'nullable',
            'hidden' => 'sometimes'
        ]);

        $field->update($data);

        return redirect()->back()->with('success', 'Field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
