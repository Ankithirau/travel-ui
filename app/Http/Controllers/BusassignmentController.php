<?php

namespace App\Http\Controllers;

use App\Models\Attendee_info;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Bus_request;
use App\Models\Bus_schedule;
use App\Models\County;
use App\Models\Pick_Point;
use App\Models\Product;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        $id = ($request->id) ? $request->id : '';

        $items = Product::select('id', 'name', 'date_concert')->orderBy('name', 'desc')->get();

        $bus_lists = "";

        $status = 0;

        if ($id != '') {

            $routes = Route::where(['status' => 1])->orderBy('id', 'desc')->get();

            // $schedule = Bus_schedule::select('booking_id')->distinct()->where('product_id', $id)->get()->toArray();

            $q = Booking::select('bookings.*', 'pickup_points.name as pickup_point_id')
                ->join('pickup_points', 'pickup_points.id', '=', 'bookings.pickup_id')
                ->where(['bookings.status' => 1, 'bookings.product_id' => $id]);
            // ->whereNotIn('bookings.id', $schedule);

            $date_concert = ($request->concert_date) ? $request->concert_date : "";

            $route_id = ($request->route_id) ? $request->route_id : "";

            if (!empty($date_concert) && !empty($route_id)) {

                $route = Route::where(['status' => 1, 'id' => $route_id])->orderBy('id', 'desc')->first();

                $q->where('bookings.date_concert', $date_concert);

                $q->whereIn('bookings.pickup_id', explode(', ', $route->pickup_point_id));

                $bus_lists = Bus::select('bus_request.*', 'bus_details.*')
                    ->join('bus_request', 'bus_request.bus_id', '=', 'bus_details.id')
                    ->where(['bus_request.status' => 1, 'bus_request.product_id' => $id, 'bus_request.request_status' => 1,'bus_details.route_id'=>$route_id])->orderBy('bus_details.id', 'desc')->get();

            }

            $booking = $q->get();

            $booking_count=[];

            foreach ($booking as $k => $item) {

                $user = Bus_schedule::where('booking_id', '=', $item->id)->first();

                if ($user === null) {
                    $item->assign_status = 0;
                    $item->bus_flag_id = 0;
                } else {
                    $item->assign_status = 1;
                    $item->bus_flag_id = $user->bus_id;
                }

                $item->info = Attendee_info::where('booking_id', $item->id)->orderBy('id', 'desc')->get();

                $booking_count[]=$item->info->count();
            }


            $status = 1;

 
            return view('content.bus_assignment.assign', compact('pageConfigs', 'booking', 'id', 'items', 'routes', 'status', 'date_concert', 'route_id', 'bus_lists','booking_count','booking_count'));
        } else {
            return view('content.bus_assignment.assign', compact('pageConfigs', 'items', 'status'));
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

        $requested_data = $request->except(['_token', '_method', 'bus_id']);

        $request->validate(
            [
                'bus_id' => 'required|string',
                'booking_id' => 'required|array',
            ],
            [
                'bus_id.required'      => 'Please Select Bus.',
                'booking_id.required'      => 'Please Select Passengers.',
            ]
        );

        try {

            $bus_req = Bus_request::where(['bus_id' => $request->bus_id, 'product_id' => $request->product_id, 'request_status' => 1])->first();

            $record = Bus_schedule::whereIn('booking_id', $request->booking_id);

            $item = [];

            if ($record->exists()) {

                if (!in_array($request->bus_id, $record->pluck('bus_id')->toArray())) {

                    if ($bus_req->remaining_capacity > $record->count()) {

                        $old_bus_id = Bus_schedule::whereIn('booking_id', $request->booking_id)->first();

                        $old_bus = Bus_request::where(['bus_id' => $old_bus_id->bus_id, 'product_id' => $request->product_id, 'request_status' => 1])->first();

                        $old_bus->update(['remaining_capacity' => $record->count() + $old_bus->remaining_capacity]);

                        $new_bus_capacity = $bus_req->remaining_capacity - $record->count();

                        $bus_req->update(['remaining_capacity' => $new_bus_capacity]);

                        $record->update(['bus_id' => $request->bus_id]);

                        $result = array_diff($request->booking_id, $record->pluck('booking_id')->toArray());

                        if (count($result) > 0) {

                            $lists = Booking::select('bookings.*', 'attendee_info.*')
                                ->join('attendee_info', 'attendee_info.booking_id', '=', 'bookings.id')
                                ->where(['bookings.status' => 1])
                                ->whereIn('bookings.id', $result)->get();

                            foreach ($lists as $k => $list) {
                                $newStore = new Bus_schedule();
                                $newStore->passenger_name = $list->attendee_name;
                                $newStore->booking_id = $list->booking_id;
                                $newStore->attendee_id = $list->id;
                                $newStore->schedule_date = $list->date_concert;
                                $newStore->pickup_point_id = $list->pickup_id;
                                $newStore->product_id = $list->product_id;
                                $newStore->bus_id = $request->bus_id;
                                $newStore->route_id = $request->route_id;
                                $newStore->created_by = Auth::user()->id;
                                $newStore->save();
                            }
                        }

                        $status = 200;

                        $msg = 'Passengers assigned successfully...!';

                    } else {
                        $status = 422;

                        $msg = 'Passengers quantity is above the bus capacity.';
                    }
                } else {

                    $status = 422;

                    $msg = 'Some passengers is already in that bus.';
                }
            } else {
                $item = Booking::select('bookings.*', 'attendee_info.*')
                    ->join('attendee_info', 'attendee_info.booking_id', '=', 'bookings.id')
                    ->where(['bookings.status' => 1])
                    ->whereIn('bookings.id', $request->booking_id)->get();


                if ($bus_req->remaining_capacity > count($item)) {

                    foreach ($item as $k => $list) {
                        $store = new Bus_schedule();
                        $store->passenger_name = $list->attendee_name;
                        $store->booking_id = $list->booking_id;
                        $store->attendee_id = $list->id;
                        $store->schedule_date = $list->date_concert;
                        $store->pickup_point_id = $list->pickup_id;
                        $store->product_id = $list->product_id;
                        $store->bus_id = $request->bus_id;
                        $store->route_id = $request->route_id;
                        $store->created_by = Auth::user()->id;
                        $store->save();
                    }

                    if ($bus_req->count() > 0) {

                        $bus_req->update(['remaining_capacity' => $bus_req->total_capacity - count($item)]);
                    }

                    $status = 200;

                    $msg = 'Passengers assigned successfully...!';
                } else {

                    $status = 422;

                    $msg = 'Passengers quantity is above the bus capacity.';
                }
            }

            session()->put('bus_id', $request->bus_id);

            $response = array('status' => $status, 'msg' => $msg);
        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => "Something went wrong...!", 'errors_message' => $th->getMessage(), 'error_stack' => $th->getTraceAsString());
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
        return view('content.bus_assignment.assign-list', compact('pageConfigs', 'bus_assign', 'status'));
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

    public function assign_unique_user(Request $request)
    {
        try {
            if (empty($request->get('bus_id'))) {

                $response = array('status' => 422, 'msg' => 'Please Select Bus..');
            } else {

                $record = Bus_schedule::where(['booking_id' => $request->booking_id]);

                if ($record->exists()) {
                    if (in_array($request->bus_id, $record->pluck('bus_id')->toArray())) {

                        $status = 422;

                        $msg = 'This Passenger is already in that bus';
                    } else {

                        $count = $record->count();

                        $new_bus = Bus_request::where(['bus_id' => $request->bus_id, 'product_id' => $request->product_id, 'request_status' => 1])->first();
                        if ($new_bus->remaining_capacity < $count) {

                            $status = 422;

                            $msg = 'Passenger quantity is above the bus capacity.ss';
                        } else {

                            $new_bus->update(['remaining_capacity' => $new_bus->remaining_capacity - $count]);

                            $bus_id = $record->pluck('bus_id')->first();

                            $old_bus = Bus_request::where(['product_id' => $request->product_id, 'bus_id' => $bus_id, 'request_status' => 1])->first();

                            $old_bus->update(['remaining_capacity' => $old_bus->remaining_capacity + $count]);

                            $record->update(['bus_id' => $request->bus_id]);

                            $status = 200;

                            $msg = 'Bus Updated Successfully';
                        }
                    }
                } else {

                    $info = Booking::select('bookings.*', 'Attendee_info.*')->join('attendee_info', 'attendee_info.booking_id', '=', 'bookings.id')->where(['booking_id' => $request->booking_id])->get();

                    $form_data = array();

                    foreach ($info as $k => $list) {
                        $data_array = array(
                            'passenger_name' => $list->attendee_name,
                            'booking_id' => $list->booking_id,
                            'attendee_id' => $list->id,
                            'schedule_date' => $list->date_concert,
                            'pickup_point_id' => $list->pickup_id,
                            'product_id' => $list->product_id,
                            'bus_id' => $request->bus_id,
                            'route_id' => $request->route_id,
                            'created_by' => Auth::user()->id
                        );
                        $form_data[] = $data_array;
                    }

                    $count = DB::table('bus_schedule')->insertOrIgnore($form_data);

                    $bus_capcity = Bus_request::where(['product_id' => $request->product_id, 'bus_id' => $request->bus_id, 'request_status' => 1])->first();

                    $bus_capcity->update(['remaining_capacity' => $bus_capcity->remaining_capacity - $count]);

                    $status = 200;

                    $msg = "Bus Assigned Successfully";
                }

                $response = array('status' => $status, 'msg' => $msg);
            }
        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => "Something went wrong...!", 'error_msg' => $th->getMessage(), 'error_stack' => $th->getTraceAsString());
        }

        return json_encode($response);
    }
}
