<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommitsRequest;
use App\Http\Requests\UpdateCommitsRequest;
use App\Models\Commits;

class CommitsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommitsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Commits $commits)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commits $commits)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommitsRequest $request, Commits $commits)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commits $commits)
    {
        //
    }
}
