<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Restaurant;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $restaurants = Restaurant::orderBy('name','ASC')->orderBy('city','ASC')->get();

            if ($restaurants->count()){
                return response()->json([
                    'message' => 'The consultation of the list of Restaurants has been successful',
                    'data' => $restaurants
                ])->setStatusCode(200);
            }else{
                return response()->json([
                    'message' => 'There are no registered Restaurants',
                    'data' => []
                ])->setStatusCode(200);
            }
            

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while consulting the Restaurant list',
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
            'name' => 'required',
            'description' => 'required',
            'address' => 'required',
            'city' => 'required',
            'photo_url' => 'required'  
        ]);

        try {
            $restaurant = Restaurant::create($request->all());

            return response()->json([
                'message' => 'The Restaurant has been succesfully created',
                'data' => $restaurant
            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the Restaurant',
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
            $restaurant = Restaurant::findOrFail($id);
            return $restaurant;
            return response()->json([
                'message' => 'The Restaurant consultation has been successful',
                'data' => $restaurant
            ])->setStatusCode(200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Restaurant not found',
                'data' => []
            ])->setStatusCode(500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error has occurred while consulting the Restaurant',
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
            'name' => 'required',
            'description' => 'required',
            'address' => 'required',
            'city' => 'required',
            'photo_url' => 'required'  
        ]);
        
        try {
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->update($request->all());

            return response()->json([
                'message' => 'The Restaurant has been succesfully updated',
                'data' => $restaurant
            ])->setStatusCode(200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Restaurant not found',
                'data' => []
            ])->setStatusCode(500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while updating the Restaurant',
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
            $restaurant = Restaurant::findOrFail($id);
            $restaurant->delete();

            return response()->json([
                'message' => 'The Restaurant has been succesfully deleted',
                'data' => []
            ])->setStatusCode(200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Restaurant not found',
                'data' => []
            ])->setStatusCode(500);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while deleting the Restaurant',
                'data' => []
            ])->setStatusCode(500);
        }
    }
}
