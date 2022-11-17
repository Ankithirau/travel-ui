<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendee_info;
use App\Models\Booking;
use App\Models\County;
use App\Models\Pick_Point;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function orders_list(Request $request, $user_id)
    {
        try {

            $orders = Booking::where(['bookings.users_id' => $user_id])
                ->join('products', 'products.id', '=', 'bookings.product_id')
                ->join('payment_history', 'payment_history.booking_id', '=', 'bookings.id')
                ->select('payment_history.status as payment_status', 'bookings.id as booking_id', 'bookings.order_id', 'bookings.order_id', 'bookings.total_amount', 'bookings.date_concert as concert_date','bookings.booking_date', 'products.name as event_name')
                ->get()->makeHidden(['fare_amount', 'status', 'created_at', 'updated_at']);

            foreach ($orders as $list) {
                $list->attendee_count = Attendee_info::where('booking_id', $list->booking_id)->count();
            }

            if ($orders) {
                $response = array('status' => 200, 'data' =>  $orders);
            } else {
                $response = array('status' => 500, 'data' => 'No Order Found');
            }
        } catch (\Throwable $th) {

            $response = array('status' => 500, 'data' => 'Something went wrong...!', 'Exception Code' => $th->getCode(), 'Exception String' => $th->__toString());
        }

        return response()->json($response, 200);
    }

    public function orders_details(Request $request, $booking_id)
    {
        try {
            $orders_details = Booking::where(['bookings.id' => $booking_id])
                ->join('products','products.id','=','bookings.product_id')
                ->join('payment_history', 'payment_history.booking_id', '=', 'bookings.id')
                ->select('bookings.*','bookings.booking_info as booking_note','products.name as event_name','payment_history.status as payment_status')
                ->get()->makeHidden(['created_at', 'updated_at','booking_info']);

            foreach ($orders_details as $details) {

                $details->county_name = County::select('name')->where('id', $details->county_id)->first();

                $details->pickup_point_name = Pick_Point::select('name')->where('id', $details->pickup_id)->first();

                $details->attendee_info=Attendee_info::where(['booking_id'=>$details->id])->orderBy('attendee_name','desc')->get()->makeHidden(['created_at', 'updated_at']);

                foreach ($details->attendee_info as $info) {
                    $info->ticket_qr= base64_encode(QrCode::format('png')->size(500)->generate("https://github.com/konsav/email-templates/"));
                }
            }

            if ($orders_details) {
                $response = ['status' => 200, 'data' => !empty($orders_details) ? $orders_details[0] : []];
            } else {
                $response = ['status' => 500, 'data' => 'No Order Found'];
            }
        } catch (\Throwable $th) {

            $response = ['status' => 500, 'data' => 'Something went wrong...!', 'Exception Code' => $th->getCode(), 'Exception String' => $th->__toString()];
        }

        return response()->json($response, 200);
    }

    public function check_in_user(Type $var = null)
    {
        # code...
    }
}
