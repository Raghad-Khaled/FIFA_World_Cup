<?php

namespace App\Http\Controllers;

use App\Http\Traits\Helpers\ApiResponseTrait;
use App\Models\Matche;
use App\Models\Stadium;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TicketController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function formatch($id)
    {
        // get reserved seats for match
        $seats = Ticket::where('match_id', $id)->pluck('seat_number');

        return $this->respondWithResourceCollection(new ResourceCollection(["seats" => $seats]), "Seats reserved");
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (auth()->user()->role == "fan") {
            // validate request
            $request->validate([
                'match_id' => 'required|exists:matches,id|unique:tickets,match_id,NULL,id,seat_number,' . $request->seat_number,
                'seat_number' => 'required|integer'
            ]);

            // get the match
            $match = Matche::find($request->match_id);
            if ($match->date < now()) {
                return $this->respondError("Match already started");
            }
            // get stadium size
            $stadium = $match->stadium;
            if ($stadium->number_of_rows * $stadium->number_of_columns < $request->seat_number) {
                return $this->respondError("Number of seat not available");
            }
            $request['user_id'] = auth()->user()->id;
            // get id of matches in the same day and time is overlapping
            #loop over matches and check if user reserved ticket in the same day
            $time = $match->time;
            $matches1 = Matche::where('id', '!=', $match->id)->where('date', $match->date)->where(function ($query) use ($time) {
                $query->whereBetween('time', [$time, date('H:i:s', strtotime($time . '+3 hours'))])
                    ->orWhereBetween('time', [date('H:i:s', strtotime($time . '-3 hours')), $time]);
            })->pluck('id');
            foreach ($matches1 as $matchres) {
                $ticket = Ticket::where('user_id', auth()->user()->id)->where('match_id', $matchres)->first();
                if ($ticket) {
                    return $this->respondError("You already reserved ticket in the same day and time clashing");
                }
            }
            // create ticket and select it's id
            $ticket = Ticket::create($request->all());
            return $this->respondWithResourceCollection(new ResourceCollection(["ticket_number" => $ticket]), "Ticket reserved Sucessfully");
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
        // delete ticket if user id is current user id
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return $this->respondError("Ticket not found");
        }
        $user = auth()->user();
        if ($ticket->user_id == $user->id && $user->role == "fan") {
            // the match before three days can't cancel the ticket
            if ($ticket->match->date < date('Y-m-d', strtotime('+3 days'))) {
                return $this->respondError("the match is before three days can't cancel the ticket");
            }
            $ticket->delete();
            return $this->respondSuccess("Ticket deleted Sucessfully");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
    }

    /**
     * get all tickets of user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function get_tickets(Request $request)
    {
        if (auth()->user()->role == "fan") {
            $user = auth()->user();
            $tickets = Ticket::where('user_id', $user->id)->get();
            return $this->respondWithResourceCollection(new ResourceCollection(["tickets" => $tickets]), "tickets");
        } else {
            return $this->respondUnAuthorized("user UnAuthorised");
        }
    }
}
