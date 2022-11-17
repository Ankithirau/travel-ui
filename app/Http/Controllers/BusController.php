<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Bus_details;
use App\Models\Bus_schedule;
use App\Models\County;
use App\Models\Pick_Point;
use App\Models\Product;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $results = Bus::orderBy('id', 'DESC')->get();

        $query = Bus::select('bus_details.id','bus_details.bus_number','bus_details.capacity','bus_details.status', 'users.name', 'routes.route_name')
            ->leftjoin('users', 'users.id', '=', 'bus_details.operator_id')
            ->leftjoin('routes', 'routes.id', '=', 'bus_details.route_id');
        
        if (Auth::user()->type!=='Admin') {
            $query->where('bus_details.operator_id',Auth::user()->id);
        }
        
        $results=$query->orderBy('bus_details.id', 'DESC')->get();

        $routes = Route::where(['status'=> 1])->orderBy('id', 'DESC')->get();

        return view('content.bus.index', compact('pageConfigs', 'results', 'routes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request, $id)
    {

        $products = Product::select('pickup_point_id', 'booked_seat', 'date_concert')->where(['id' => $id])->first();

        $buses = Bus::where('status', 1)->get();

        if ($request->get('route_id') && $request->get('product_id')) {

            $routes = Route::where(['id' => $request->route_id, 'product_id' => $request->product_id])->orderBy('route_name', 'desc')->get();

            $point_array = explode(',', $routes[0]['pickup_point_id']);
        } else {

            $routes = Route::where(['status' => 1, 'product_id' => $id])->orderBy('route_name', 'desc')->get();

            if ($routes->count() == 0) {

                $status = "No Route is assign";

                return view("schedule.create", compact('status'));
            } else {

                $route_point = "";

                foreach ($routes as $key => $value) {

                    $route_point .= $value->pickup_point_id . ',';
                }

                $point_array = explode(',', rtrim($route_point, ', '));
            }
        }

        $point = array_map('trim', $point_array);

        $alloted_points = Bus_schedule::where('product_id', $id)->pluck('pickup_point_id')->toArray();

        $remaining_points = array_diff($point, $alloted_points);

        if (empty($remaining_points)) {

            $status = "No Record Founds";

            return view("booking.filter", compact('status', 'id'));
        }

        $points = Pick_Point::select('id', 'counties_id', 'name')->whereIn('id', $remaining_points)->get();

        foreach ($points as $key => $value) {
            $points[$key]['seat_count'] = Booking::where(['product_id' => $id, 'pickup_id' => $value->id])->sum('number_of_seats');
            $county =  County::select('id', 'name')->where('id', $value->counties_id)->first();
            $points[$key]['county_name'] = $county->name;
            $points[$key]['date_concert'] = explode(',', $products->date_concert);
            $points[$key]['product_id'] = $id;
            $points[$key]['bus_assign'] = Route::where(['product_id' => $id])->where('pickup_point_id', 'LIKE', '%' . $value->id . '%')->get();
        }
        if ($request->get('route_id') && $request->get('product_id')) {
            return view("booking.filter", compact('buses', 'points', 'routes'));
        } else {
            return view("schedule.create", compact('buses', 'points', 'routes'));
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

        $requested_data = $request->except(['_token', '_method']);

        $request->validate(
            [
                'bus_number' => 'required|string',
                'bus_registration_number' => 'required|string',
                'bus_type' => 'required|string',
                'capacity' => 'required|integer',
                'dusername' => 'required|string|unique:bus_details',
                'dpassword' => [
                    'required',
                    'integer',
                    'min:6'
                ]
            ],
            [
                'bus_number.required'      => 'Bus Number is required.',
                'bus_registration_number.required' => 'Bus Registration Number is required.',
                'bus_type.required'      => 'Bus type is required.',
                'capacity.required'      => 'Bus Capacity is required.',
                'dusername.required'      => 'Driver username is required.',
                'dpassword.required'      => 'Driver Password is required.',
                'dpassword.min'      => 'Password must be at least 6 characters.',
                // 'dpassword.regex'      => 'Password should be contain upper case, lower case, numbers and special characters (!@£$%^&)',
            ]
        );
        try {
            $store  = new Bus();
            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }
            $store->dpassword = Hash::make($request->dpassword);
            $store->operator_id = Auth::user()->id;
            $store->created_by = Auth::user()->id;
            $store->save();
            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'error' => $th->getMessage());
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $result =  Bus_details::find($id);

            $response = array('status' => 200, 'result' => $result,'id'=>'ss');

        } catch (\Throwable $th) {

            $response = array('status' => 400, 'msg' => 'Something went wrong...!','err'=>$th->getMessage());
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
                'bus_number' => 'required|string',
                'bus_registration_number' => 'required|string',
                'bus_type' => 'required|string',
                'capacity' => 'required|integer',
                'dusername' => 'required|string',
                'dpassword' => [
                    'required',
                    'string',
                    'min:6',             // must be at least 10 characters in length
                    // 'regex:/[a-z]/',      // must contain at least one lowercase letter
                    // 'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    // 'regex:/[0-9]/',      // must contain at least one digit
                    // 'regex:/[@$!%*#?&]/', // must contain a special character
                ]
            ],
            [
                'bus_number.required'      => 'Bus Number is required.',
                'bus_registration_number.required' => 'Bus Registration Number is required.',
                'bus_type.required'      => 'Bus type is required.',
                'capacity.required'      => 'Bus Capacity is required.',
                'dusername.required'      => 'Driver username is required.',
                'dpassword.required'      => 'Driver Password is required.',
                'dpassword.min'      => 'Password must be at least 6 characters.',
                // 'dpassword.regex'      => 'Password should be contain upper case, lower case, numbers and special characters (!@£$%^&)',
            ]
        );

        try {
            $update  = Bus::find($id);
            foreach ($requested_data as $key => $data) {
                $update->$key = $data;
            }
            $update->dpassword = (!empty($request->dpassword))?Hash::make($request->dpassword):Hash::make($update->dpassword);
            $update->operator_id = (!empty(Auth::user()->id))?Auth::user()->id:Hash::make($update->operator_id);
            $update->created_by =(!empty(Auth::user()->id))?Auth::user()->id:Hash::make($update->created_by);;
            $update->update();
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
        try {
            Bus::find($id)->destroy($id);

            $response = array('status' => 200, 'msg' => 'Record deleted successfully!');
        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => 'Something went wrong...!','errors'=>$th->getMessage());
        }
        return json_encode($response);
    }

    public function status($id)
    {
        try {
            $update  = Bus::find($id);
            $update->status = !$update->status;
            $update->save();
            $response = array('status' => 200, 'msg' => 'Status updated successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return json_encode($response);
    }
}
