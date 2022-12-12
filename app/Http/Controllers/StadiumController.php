<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers\ApiResponseTrait;
use App\Models\Stadium;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StadiumController extends Controller
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
            $stadiums = Stadium::all();
            return $this->respondWithResourceCollection(new ResourceCollection(["stadiums" => $stadiums]), "All stadiums");
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
            'name' => 'required|max:255',
            'location' => 'required',
            'number_of_rows' => 'required',
            'number_of_columns' => 'required'
        ]);
        if (auth()->user()->role == "manager") {
            # create team
            $stadium = Stadium::create($request->all());

            return $this->respondWithResourceCollection(new ResourceCollection(["stadium" => $stadium]), "Stadium created");
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
            $stadium = Stadium::find($id);
            $stadium->delete();
            return $this->respondSuccess("Stadium deleted");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
    }
}
