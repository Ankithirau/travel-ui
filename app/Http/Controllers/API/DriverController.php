<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
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
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function driver_login(Request $request)
    {
        $request->validate(
            [
                'username' => 'required',
                'password' => 'required|min:6'
            ]
        );
        try {
            $employee  = Bus::select('dusername as username' , 'dpassword','id as bus_id','route_id','operator_id','status')->where(['dusername' => $request->username])->first();
            if ($employee) {
                if (Hash::check($request->password, $employee->dpassword)) {
                    $token = $employee->createToken('mytoken')->plainTextToken;
                    // $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
                    // $token = substr(str_shuffle($chars), 0, 35);
                    if ($employee->status==1) {
                        // $employee->where('dusername', $request->username)->update([
                        //     'tokens' => $token
                        // ]);
                        $response = array('status' => 200, 'user' => $employee,'token'=>$token);
                    }else{

                        $response = array('status' => 200, 'user' => 'user is inactive');
                    }
                    
                } else {
                    $response = array('status' => 500, 'msg' => 'Invalid password!');
                }
            } else {
                $response = array('status' => 500, 'msg' => "User not exist...!");
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'Exception Code' => $th->getMessage(), 'Exception String' => $th->__toString());
        }
        return response()->json($response);
    }

    public function bus_event_lists(Request $request)
    {
        $request->validate(
            [
                'bus_id' => 'required|integer'
            ]
        );
        try {


            $details=Bus_request::where(['bus_id'=>$request->bus_id,'request_status'=>'accepted'])
            ->whereDate('request_date', '=',date('Y-m-d'))
            ->join('products','products.id','=','bus_request.product_id')
            ->leftjoin('bus_details','bus_details.id','=','bus_request.bus_id')
            ->select('products.name as event_name','bus_details.route_id')
            ->get()->makehidden(['created_at','updated_at']);

            $arr=[];

            foreach ($details as $item) {
                $arr[]=Route::where('id',$item->route_id)->get()->makehidden(['created_at','updated_at']);
            }

            $points=[];
            $county=[];

            foreach ($arr as $k=> $lists) {
                $points[]=Pick_Point::whereIn('id',explode(', ',$lists[$k]['pickup_point_id']))->get()->makehidden(['created_at','updated_at']);
                $county[]=County::whereIn('id',explode(', ',$lists[$k]['counties_id']))->get()->makehidden(['created_at','updated_at']);
            }

            foreach ($details as $obj) {
                $obj->pickup_points=!empty($points[0])?$points[0]:[];
                $obj->county_name=!empty($county[0])?$county[0]:[];
            }

            $response = array('status' => 200, 'data' => !empty($details[0])? $details[0]: []);

        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'Exception Code' => $th->getCode(), 'Exception String' => $th->__toString());
        }
        return response()->json($response);
    }

    public function bus_attendee_list(Request $request)
    {
        $request->validate(
            [
                'bus_id' => 'required|integer',
                
            ]
        );
        try {

            $data=Bus_schedule::where('bus_id',$request->bus_id)->orderBy('check_in_status','desc')->get()->makehidden(['created_at','updated_at']);

            if($data->count()>0){
                $response = array('status' => 200, 'data' => $data);
            }else{
                $response = array('status' => 500, 'msg' => 'No Record Found...!');
            }
            
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'Exception Code' => $th->getCode(), 'Exception String' => $th->__toString());
        }
        return response()->json($response);
    }

    public function attendee_details(Request $request)
    {
        $request->validate(
            [
                'bus_id' => 'required|integer',
                'attendee_id' => 'required|integer',
            ]
        );
        try {
            $user_checker=Bus_schedule::where(['bus_id'=>$request->bus_id])->first();
            if ($user_checker->bus_id==$request->bus_id) {
                if ($user_checker->schedule_date==date('Y-m-d')) {
                    $info=Attendee_info::where(['id'=>$request->attendee_id])->get()->makehidden(['id','created_at','updated_at']);
                    foreach ($info as $val) {
                        $val->event_details=Booking::where(['bookings.id'=>$val->booking_id])->join('products','products.id','=','bookings.product_id')->select('products.name','products.date_concert as concert_date')->get();
                        $val->pickup_point=Pick_Point::where(['id'=>$user_checker->pickup_point_id])->select('name')->get();
                        $val->attendee_id=$val->id;
                    }
                    $response = array('status' => 200, 'data' => $info);
                }else{
                    $response = array('status' => 500, 'msg' => 'Booking is not exist for this date..!');
                }
            }else{
                $response = array('status' => 500, 'msg' => 'User not belong to this bus..!');
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'Exception Code' => $th->getMessage(), 'Exception String' => $th->__toString());
        }
        return response()->json($response);
    }

    public function ticket_checkIn(Request $request)
    {
        $request->validate(
            [
                'bus_id' => 'required|integer',
                'attendee_id' => 'required|integer',
            ]
        );
        try {
            $user_checker=Bus_schedule::where(['bus_id'=>$request->bus_id,'attendee_id'=>$request->attendee_id])->first();
            if ($user_checker->bus_id==$request->bus_id) {
                if ($user_checker->schedule_date==date('Y-m-d')) {

                    if ($user_checker->check_in_status==1) {
                        $response = array('status' => 500, 'msg' => 'You already Checked on this bus..!');

                    }else{
                        $user_checker->where(['bus_id'=>$request->bus_id,'attendee_id'=>$request->attendee_id])->update([
                            'check_in_status' => 1
                        ]);
                        $response = array('status' => 200,'msg'=>'User checked in successfully...!', 'data' => $user_checker->makeHidden(['created_at','updated_at']));
                    }

                }else{
                    $response = array('status' => 500, 'msg' => 'Booking is not exist for this date..!');
                }
            }else{
                $response = array('status' => 500, 'msg' => 'User not belong to this bus..!');
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'Exception Code' => $th->getMessage(), 'Exception String' => $th->__toString());
        }
        return response()->json($response);
    }
}
