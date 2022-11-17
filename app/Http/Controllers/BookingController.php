<?php

namespace App\Http\Controllers;

use App\Models\Attendee_info;
use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\Pick_Point;
use App\Models\Product;
use App\Models\Route_sku;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnSelf;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request);
        $pageConfigs = ['pageHeader' => false];

        $query = Booking::orderBy('id', 'desc');

        $event_id="";
        $pickup_point_id="";
        $status="";

        if (!empty($request->event_id) && !empty($request->pickup_point) && !empty($request->filter_status)) {
            $query->where(['product_id'=>$request->event_id,'pickup_id'=>$request->pickup_point,'status'=>$request->filter_status]);
            $event_id=$request->event_id;
            $pickup_point_id=$request->pickup_point;
            $status=$request->filter_status;
        }
        // if(!empty($request->event_id) && $request->event_id=='all'){
        //     $event_id=$request->event_id;
        // }
        if(!empty($request->event_id)){
            $query->where(['product_id'=>$request->event_id]);
            $event_id=$request->event_id;
        }
        if(!empty($request->pickup_point)){
            $query->where(['pickup_id'=>$request->pickup_point]);
            $pickup_point_id=$request->pickup_point;
        }
        if(!empty($request->filter_status)){
            $query->where(['status'=>$request->filter_status]);
            $status=$request->filter_status;
        }
        if(!empty($request->event_id) && !empty($request->filter_status)){
            $query->where(['product_id'=>$request->event_id,'status'=>$request->filter_status]);
            $event_id=$request->event_id;
            $status=$request->filter_status;
        }
        if(!empty($request->event_id) && !empty($request->pickup_point)){
            $query->where(['product_id'=>$request->event_id,'pickup_id'=>$request->pickup_point]);
            $event_id=$request->event_id;
            $pickup_point_id=$request->pickup_point;
        }

        if(!empty($request->filter_status) && !empty($request->pickup_point)){
            $query->where(['status'=>$request->filter_status,'pickup_id'=>$request->pickup_point]);
            $status=$request->filter_status;
            $pickup_point_id=$request->pickup_point;
        }

        $results = $query->get();

        $products = Product::select('id', 'name', 'date_concert')->where(['status' => 1])->orderBy('name','asc')->get();

        $pick_point = Pick_Point::select('id', 'name')->where(['status' => 1])->orderBy('name','asc')->get();

        foreach ($results as $key => $value) {

            $value->payment=Payment::select('status')->where(['booking_id'=>$value->id])->first()->toArray();

            $value->sku=Route_sku::select('sku_name')->where(['product_id'=>$value->product_id,'pickup_point_id'=>$value->pickup_id])->first();

        }

        return view('content.booking.index', compact('pageConfigs','results','products','event_id','pickup_point_id','status','pick_point'));
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
        //
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
        $result =  Attendee_info::find($id);
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
                'attendee_name' => 'required|string',
                'attendee_number' => 'required|string',
            ],
            [
                'attendee_name.required'      => 'Attendee Name is required.',
                'attendee_number.required'      => 'Attendee Number is required.',
            ]
        );
        try {
            $update  = Attendee_info::find($id);
            foreach ($requested_data as $key => $data) {
                $update->$key = $data;
            }
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
        try {
            Booking::find($id)->delete($id);

            $response = array('status' => 200, 'msg' => 'Record deleted successfully!');

        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => 'Something went wrong...!');

        }
        return json_encode($response);
    }

    public function attendee_info(Request $request, $id)
    {
        $pageConfigs = ['pageHeader' => false];

        $results = Attendee_info::where(['booking_id' => $id])->get();

        $booking = Booking::where(['id' => $id])->first();

        $booking->coupon_name = Coupon::select('promo_code','minimum_amount')->where(['id' => $booking->coupon_id])->first();

        $booking->pickup_point = Pick_Point::select('id','name')->where('counties_id',$booking->county_id)->orderBy('name','desc')->get()->toArray();

        $booking->product_name = Product::select('id','name')->orderBy('name','desc')->get()->toArray();

        $booking->route_sku = Route_sku::select('id','sku_name')->where(['product_id'=>$booking->product_id,'pickup_point_id' => $booking->pickup_id])->orderBy('sku_name','desc')->get()->toArray();
        
        foreach ($results as $key => $value) {

            $value->amount=$booking->total_amount;

        }
        return view('content.booking.attendee_info', compact('pageConfigs','results','booking'));
    }
}
