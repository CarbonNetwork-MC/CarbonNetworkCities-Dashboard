<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['message' => 'Not supported'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json(['message' => 'Not supported'], 405);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $playerLocation = \App\Models\PlayerLocation::where('player_uuid', $uuid)->first(['server_name']);

        if (!$playerLocation)
            return response()->json(['message' => 'Player location not found'], 404);

        return response()->json($playerLocation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        return response()->json(['message' => 'Not supported'], 405);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        return response()->json(['message' => 'Not supported'], 405);
    }
}
