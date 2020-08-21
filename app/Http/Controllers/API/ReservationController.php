<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Reservation;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $reservations = Reservation::with('restaurant')->orderBy('reservation_date', 'ASC')->get();
            
            if ($reservations->count()){
                return response()->json([
                    'message' => 'The consultation of the list of Restaurants has been successful',
                    'data' => $reservations
                ])->setStatusCode(200);
            }else{
                return response()->json([
                    'message' => 'There are no registered Reservations',
                    'data' => []
                ])->setStatusCode(200);
            }

            return response()->json([
                'message' => 'The consultation of the list of Reservations has been successful',
                'data' => Reservation::all()
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while consulting the Reservation list',
                'data' => []
            ])->setStatusCode(500);
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
        
        $this->validate($request,[
            'restaurant_id' => 'required',
            'reservation_date' => 'required',
            'table_name' => 'required',
            'amount_of_people' => 'required'
        ]);

        try {
            $count_reservation = Reservation::where('reservation_date', $request->reservation_date)
                ->where('restaurant_id',$request->restaurant_id)
                ->count();
  
            if ($count_reservation < 16 ) {                
                $reservation = Reservation::create($request->all());
                return response()->json([
                    'message' => 'The Reservation has been succesfully created',
                    'data' => $reservation
                ])->setStatusCode(201);
            }

            return response()->json([
                'message' => 'The limit of daily reservations has been reached',
                'data' => []
            ])->setStatusCode(204);            

        } catch (\Exception $e) {
            return $e->getMessage();
            return response()->json([
                'message' => 'An error occurred while creating the Reservation',
                'data' => []
            ])->setStatusCode(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);

            return response()->json([
                'message' => 'The Reservation consultation has been successful',
                'data' => $reservation
            ])->setStatusCode(200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Reservation not found',
                'data' => []
            ])->setStatusCode(500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error has occurred while consulting the Reservation',
                'data' => []
            ])->setStatusCode(500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'restaurant_id' => 'required',
            'reservation_date' => 'required',
            'table_name' => 'required',
            'amount_of_people' => 'required'
        ]);
        
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->update($request->all());

            return response()->json([
                'message' => 'The Reservation has been succesfully updated',
                'data' => $reservation
            ])->setStatusCode(200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Reservation not found',
                'data' => []
            ])->setStatusCode(500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the Reservation',
                'data' => []
            ])->setStatusCode(500);
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
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();

            return response()->json([
                'message' => 'The Reservation has been succesfully deleted',
                'data' => []
            ])->setStatusCode(200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Reservation not found',
                'data' => []
            ])->setStatusCode(500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the Reservation',
                'data' => []
            ])->setStatusCode(500);
        }
    }
}
