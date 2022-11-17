<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Bus_schedule;
use App\Models\County;
use App\Models\Pick_Point;
use App\Models\Product;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

class BusassignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $pageConfigs = ['pageHeader' => false];

        $items = Product::select('id', 'name')->orderBy('name', 'desc')->get();

        $product_name = "";

        $id = ($request->id) ? $request->id : '';

        $query=Booking::orderBy('id', 'desc');

        if ($id !='' ) {

            $products = Product::select('name', 'pickup_point_id', 'booked_seat', 'date_concert','counties_id')->where(['id' => $id])->first();

            $total_booking=$query->where(['product_id' => $id])->sum('number_of_seats');

            $product_name = $products->name;

            $buses = Bus::where('status', 1)->get();

            $routes = Route::where('status', 1)->orderBy('route_name', 'desc')->get();

            $flag="";

            if ($request->get('route_id')) {

                if ($request->get('route_id')=='all') {

                    $pickup_point = explode(',', $products->pickup_point_id);

                    $flag=$request->get('route_id');
                    
                }else{

                $routes = Route::where(['id' => $request->route_id])->orderBy('route_name', 'desc')->first();

                $pickup_point = explode(',', $routes->pickup_point_id);

                $route_point = [];

                foreach ($pickup_point as $pointkey => $point) {
                    if (in_array($point, explode(',', $products->pickup_point_id))) {
                        $route_point[] = $point;
                    }
                }

                $pickup_point = $route_point;

                if (count($pickup_point)>0) {

                    $county=Pick_Point::select('counties_id')->where('counties_id',$pickup_point)->first();

                    $total_booking=$query->where(['county_id' => $county->counties_id])->count();
                }else{
                    $total_booking=0;
                }
                                    
                }
            } else {
                $pickup_point = array_map('trim',explode(',', $products->pickup_point_id));
            }

            $date = explode(',', $products->date_concert);

            usort($date, function ($a, $b) {
                return strtotime($a) - strtotime($b);
            });

            $store_arr = [];

            foreach ($date as $k => $val) {

            $points = Pick_Point::select('id', 'counties_id', 'name')->whereIn('id', $pickup_point)->get();

                foreach ($points as $key => $value) {
                    if (!Bus_schedule::select('pickup_point_id')->where(['pickup_point_id' => $value->id, 'product_id' => $id])->exists()) {
                        $points[$key]['seat_count'] = Booking::where(['product_id' => $id, 'pickup_id' => $value->id])->sum('number_of_seats');
                        $county =  County::select('id', 'name')->where('id', $value->counties_id)->first();
                        $points[$key]['county_name'] = $county->name;
                        $points[$key]['date_concert'] = trim($val);
                        $points[$key]['product_id'] = $id;
                        $routes_val = Route::select('route_name', 'id', 'pickup_point_id')->orderBy('route_name', 'desc')->get();
                        $route_data = array();
                        if ($routes_val->count() > 0) {
                            foreach ($routes_val as $i => $item) {
                                $pick_arr = explode(',', $item->pickup_point_id);
                                if (in_array($value->id, $pick_arr)) {
                                    $route_data[] = array('route_id' => $item->id, 'route_name' => $item->route_name);
                                }
                            }
                        }

                        $points[$key]['routes'] = $route_data;
                        $points[$key]['bus_assign'] = Bus_schedule::where(['product_id' => $id, 'pickup_point_id' => $value->id])->get();
                    }
                }

                $store_arr[] = $points;
            }

            $points = $store_arr;

            if ($request->get('route_id')) {
                $route_id = $request->get('route_id');
                if ($points[0]->count() > 0) {
                    $status = 1;
                } else {
                    $status = 0;
                }

                return view("content.bus_assignment.filter-bus-assign-list", compact('buses','total_booking', 'points', 'routes', 'route_id', 'status','items','flag'));
            } else {
                return view('/content/bus_assignment/assign', compact('pageConfigs', 'total_booking','buses', 'points', 'routes', 'id', 'product_name','items'));
            }
        } else {
            return view('content.bus_assignment.assign', compact('pageConfigs', 'items'));
        }
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
        $requested_data = $request->except(['_token', '_method']);


        $request->validate(
            [
                'route_name.*' => 'required|string',
                'date_concert.*' => 'required|string',
                'buses.*' => 'required|string',
                'route_checkbox' => 'required',
            ],
            [
                'route_name.*.required'      => 'Route Name is required.',
                'date_concert.*.required'      => 'Concert Date is required.',
                'buses.*.required' => 'Bus Number is required.',
                // 'route_checkbox.*.required' => 'this is field is required.',
            ]
        );
        // dd($requested_data);
        // print_r($request->route_checkbox);
        // die;

        $route = $request->route_name;
        $date_concert = $request->date_concert;
        $county = $request->counties_id;
        $pickup = $request->pickup_point_id;
        $seat_count = $request->seat_count;
        $buses = $request->buses;

        for ($i = 0; $i < count($date_concert); $i++) {
            $route_name = $route[$i];
            $products = $request->product_id;
            $date = $date_concert[$i];
            $bus = $buses[$i];
            $counties = $county[$i];
            $pickup_point = $pickup[$i];
            $seat = $seat_count[$i];

            $bus_schedule_exist = Bus_schedule::where(['product_id' => $products, 'pickup_point_id' => $pickup_point, 'schedule_date' => $date])->first();

            $data_array = array(
                'route_name' => $route_name,
                'booked_seat' => $seat,
                'schedule_date' => $date,
                'pickup_point_id' => $pickup_point,
                'product_id' => $products,
                'bus_id' => $bus
            );

            if (!$bus_schedule_exist) {
                $data_array['created_by'] = Auth::user()->id;
                Bus_schedule::insert($data_array);
            } else {
                Bus_schedule::where('id', $bus_schedule_exist->id)->update($data_array);
            }
        }

        $response = array('status' => 200, 'redirect' => true, 'url' => url('product'), 'msg' => 'Data saved successfully...!');

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

        $pageConfigs = ['pageHeader' => false];

        $bus_assign = Bus_schedule::where(['product_id' => $id])->orderBy('pickup_point_id', 'asc')->get();

        foreach ($bus_assign as $key => $value) {
            $value->bus_id = Bus::select('bus_number')->where('id', $value->bus_id)->first();
            $value->pickup_point_id = Pick_Point::select('name', 'counties_id')->where('id', $value->pickup_point_id)->first();
            $value->counties_id = County::select('name')->where('id', $value->pickup_point_id['counties_id'])->first();
        }

        if ($bus_assign->count() > 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        return view('/content/bus_assignment/assign-list', compact('pageConfigs', 'bus_assign', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function add_schedule(Request $request, $id)
    {
        $requested_data = $request->except(['_token', '_method']);

        try {

            if (empty($request->get('route_name'))) {
                $response = array('status' => 400, 'pickup_id' => $request->get('pickup_point_id'), 'route_name' => 'Route Name is required.');
                return json_encode($response);
            }

            if (empty($request->get('schedule_date'))) {
                $response = array('status' => 400, 'pickup_id' => $request->get('pickup_point_id'), 'date_concert' => 'Concert Date is required.');
                return json_encode($response);
            }

            if (empty($request->get('buses'))) {
                $response = array('status' => 400, 'pickup_id' => $request->get('pickup_point_id'), 'buses' => $request->get('buses'), 'buses' => 'Bus Number is required.');
                return json_encode($response);
            }

            $record = Bus_schedule::where(['product_id' => $request->get('product_id'), 'pickup_point_id' => $request->get('pickup_point_id')])->find($id);

            if (empty($record)) {

                $store = new Bus_schedule;

                $store->route_name = $request->get('route_name');

                $store->route_id = $request->get('route_id');

                $store->booked_seat = $request->get('booked_seat');

                $store->schedule_date = $request->get('schedule_date');

                $store->pickup_point_id = $request->get('pickup_point_id');

                $store->product_id = $request->get('product_id');

                $store->bus_id = $request->get('buses');

                $store->created_by = Auth::user()->id;

                $store->save();
            } else {

                $record->route_name = ($request->get('route_name')) ? $request->get('route_name') : "";

                $record->route_id = $request->get('route_id');

                $record->bus_id = $request->get('buses');

                $record->schedule_date = $request->get('schedule_date');

                $record->save();
            }

            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => "'Something went wrong...!'");
        }

        return json_encode($response);
    }
}
