<?php

namespace App\Http\Controllers;

use App\Models\County;
use App\Models\Pick_Point;
use App\Models\Product;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $results = Route::orderBy('route_name', 'DESC')->get();

        $counties = County::where(['status' => 1])->orderBy('name', 'DESC')->get();

        return view('content.routes.index', compact('pageConfigs', 'counties', 'results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requested_data = $request->except(['_token']);

        $request->validate(
            [
                'route_name' => ['required', 'string', Rule::unique('routes', 'route_name')],
                'counties_id' => ['required', 'array'],
                'pickup_point_id' => 'required|array',
            ],
            [
                'pickup_point_id.required' => 'Pick Up Location is required.',
                'counties_id.required' => 'County name is required.',
                'route_name.required' => 'Route Name is required.',
            ]
        );
        try {
            $store  = new Route;
            $store->route_name = $request->route_name;
            $store->counties_id =implode(", ",  $request->counties_id);
            $store->pickup_point_id = implode(", ", $request->pickup_point_id);
            $store->created_by = Auth::user()->id;
            $store->save();
            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => $th->getMessage());
        }
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $routes = Route::where(['id' => $id])->first();

        $points = explode(', ', $routes->pickup_point_id);

        $results = array();

        foreach ($points as $key => $value) {

            $pickup = Pick_Point::select('name', 'counties_id')->where(['id' => $value])->first();

            $res['counties'] = County::select('name')->where(['id' => $pickup->counties_id])->first()->toArray();
            $res['pickup_point'] = $pickup->name;
            $results[] = $res;
        }

        return view('route.view', compact('results'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result =  Route::find($id);

        $pickup_point = explode(', ', $result->pickup_point_id);

        $county = explode(', ', $result->counties_id);

        $arr_point = [];

        $arr_county = [];

        foreach ($pickup_point as $value) {
            $arr_point[] = Pick_Point::select('name', 'id')->where(['id' => $value])->first();
        }

        foreach ($county as $value) {
            $arr_county[] = County::select('name', 'id')->where(['id' => $value])->first();
        }

        $result->pickup_point_id = $arr_point;
        $result->counties_id = $arr_county;

        if ($result) {
            $response = array('status' => 200, 'result' => $result);
        } else {
            $response = array('status' => 400, 'msg' => 'Something went wrong...!');
        }
        return json_encode($response);
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
        $requested_data = $request->except(['_token', '_method']);

        $request->validate(
            [
                'route_name' => ['required', 'string'],
                'counties_id' => ['required', 'array'],
                'pickup_point_id' => 'required|array',
            ],
            [
                'pickup_point_id.required' => 'Pick Up Location is required.',
                'counties_id.required' => 'County name is required.',
                'route_name.required' => 'Route Name is required.',
            ]
        );

        try {
            $update  = Route::find($id);
            foreach ($requested_data as $key => $data) {
                $update->$key = $data;
            }
            $update->counties_id = implode(', ', $request->counties_id);
            $update->pickup_point_id = implode(', ', $request->pickup_point_id);
            $update->save();
            $response = array('status' => 200, 'msg' => 'Data updated successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Route::find($id)->delete($id);
        return json_encode([
            'status' => 200,
            'msg' => 'Record deleted successfully!'
        ]);
    }

    public function status($id)
    {
        // dd($id);
        try {
            $update  = Route::find($id);
            $update->status = !$update->status;
            $update->save();
            $response = array('status' => 200, 'msg' => 'Status updated successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return json_encode($response);
    }

    public function get_pickuppoint(Request $request)
    {
        try {
            $pickup_points = [];

            foreach ($request->id as $key => $value) {
                
                $pickup_points[] = Pick_Point::where(['counties_id' => $value])->get()->makeHidden(['created_by', 'status', 'created_at', 'updated_at']);
            }

            $response = array('status' => 200, 'data' => $pickup_points);

        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return json_encode($response);
        
    }
}
