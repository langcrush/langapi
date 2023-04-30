<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStatRequest;
use App\Http\Requests\UpdateStatRequest;
use App\Http\Resources\StatResource;
use App\Models\Stat;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StatResource::collection(Stat::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatRequest $request)
    {
        $stat = Stat::create($request->validated());
        return StatResource::make($stat);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stat $stat)
    {
        return StatResource::make($stat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatRequest $request, Stat $stat)
    {
        $stat->update($request->validated());
        return StatResource::make($stat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stat $stat)
    {
        $stat->delete();
        return response()->noContent();
    }
}
