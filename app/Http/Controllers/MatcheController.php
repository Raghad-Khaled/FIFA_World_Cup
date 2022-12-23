<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers\ApiResponseTrait;
use App\Models\Matche;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MatcheController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getmatch($id)
    {
        // get all teams
        $match = Matche::find($id);
        return $this->respondWithResourceCollection(new ResourceCollection(["match" => $match]), "match details");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all teams

        $matches = Matche::all();
        return $this->respondWithResourceCollection(new ResourceCollection(["matches" => $matches]), "All Matches");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        # validate request
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'time' => 'required|date_format:H:i:s',
            'referee' => 'required|string',
            'lineman1' => 'required|string',
            'lineman2' => 'required|string',
            'team1_id' => 'required|exists:teams,id',
            'team2_id' => 'required|exists:teams,id',
            'stadium_id' => 'required|exists:stadiums,id'
        ]);
        if (auth()->user()->role == "manager") {
            // check if team 1 has a match in the same day
            $matches1 = Matche::where('date', $request->date)->where('team1_id', $request->team1_id)->get();
            if (count($matches1) > 0) {
                return $this->respondError("Team 1 has a match in the same day");
            }
            // check if team 2 has a match in the same day
            $matches2 = Matche::where('date', $request->date)->where('team2_id', $request->team2_id)->get();
            if (count($matches2) > 0) {
                return $this->respondError("Team 2 has a match in the same day");
            }
            $match = Matche::create($request->all());

            return $this->respondWithResourceCollection(new ResourceCollection(["match" => $match]), "Match created");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->role == "manager") {
            // delete team
            $match = Matche::find($id);
            $match->delete();
            return $this->respondSuccess("Match deleted");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
            'time' => 'nullable|date_format:H:i:s',
            'referee' => 'nullable|string',
            'lineman1' => 'nullable|string',
            'lineman2' => 'nullable|string',
            'team1_id' => 'nullable|exists:teams,id',
            'team2_id' => 'nullable|exists:teams,id',
            'stadium_id' => 'nullable|exists:stadiums,id'
        ]);

        if (auth()->user()->role == "manager") {
            // delete team
            $match = Matche::find($id);
            $match->update($request->all());
            return $this->respondWithResourceCollection(new ResourceCollection(["match" => $match]), "Match Updated");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
    }
}
