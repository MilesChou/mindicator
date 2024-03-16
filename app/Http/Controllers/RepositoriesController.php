<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRepositoriesRequest;
use App\Http\Requests\UpdateRepositoriesRequest;
use App\Models\Repositories;

class RepositoriesController extends Controller
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
    public function store(StoreRepositoriesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Repositories $sourceRepository)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Repositories $sourceRepository)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRepositoriesRequest $request, Repositories $sourceRepository)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repositories $sourceRepository)
    {
        //
    }
}
