<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers\ApiResponseTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all teams
        if (auth()->user()->role == "manager") {
            $teams = Team::all();
            return $this->respondWithResourceCollection(new ResourceCollection(["teams" => $teams]), "All teams");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
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
            'name' => 'required|unique:teams|max:255'
        ]);
        if (auth()->user()->role == "manager") {
            # create team
            $team = Team::create([
                'name' => $request->name
            ]);

            return $this->respondWithResourceCollection(new ResourceCollection(["team" => $team]), "Team created");
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
            $team = Team::find($id);
            $team->delete();
            return $this->respondSuccess("Team deleted");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
    }
}
