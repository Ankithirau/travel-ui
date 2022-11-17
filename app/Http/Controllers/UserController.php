<?php

namespace App\Http\Controllers;

use App\Models\Attendee_info;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $results = User::where(['type' => 'User'])->orderBy('id', 'desc')->get();

        return view('content.user.user', compact('pageConfigs', 'results'));
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
        $pageConfigs = ['pageHeader' => false];

        $results = User::find($id);

        return view('/content/user/setting', compact('pageConfigs', 'results'));
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

    public function status($id)
    {
        try {
            $update  = User::find($id);
            $update->status = !$update->status;
            $update->save();
            $response = array('status' => 200, 'msg' => 'Status updated successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!','errors'=>$th->getMessage());
        }
        return json_encode($response);
    }
    //Update password
    public function update_password(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:10',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ], ['password.regex' => 'Password should be at least 10 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)']);
        try {
            $update  = User::find($id);
            if ($update) {
                if (Hash::check($request->current_password, $update->password)) {
                    $update->password = Hash::make($request->password);
                    $update->save();
                    $response = array('status' => 200, 'msg' => 'Password changed successfully...!');
                } else {
                    $response = array('status' => 500, 'msg' => 'Invalid old password!');
                }
            } else {
                $response = array('status' => 500, 'msg' => "User not exist...!");
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return json_encode($response);
    }
    public function attendee_info(Request $request)
    {
        $pageConfigs = ['pageHeader' => false];

        $results = attendee_info::orderBy('id', 'desc')->get();

        $query = attendee_info::select("attendee_info.attendee_name as passenger_name", "attendee_info.attendee_number as passenger_number", "bookings.booking_email as passenger_email","bookings.order_id", "products.name as event_name","products.image")
            ->join("bookings", "bookings.id", "=", "attendee_info.booking_id")
            ->join("products", "products.id", "=", "bookings.product_id");

        $id=!empty($request->event_id)?$request->event_id:"";

        if (!empty($id)) {
           $results=$query->where('bookings.product_id','=',$request->event_id)->orderBy('bookings.id','asc')->get();
        }else{
            $results=$query->orderBy('bookings.id','asc')->get();
        }

        $events=Product::where(['status'=>1])->orderBy('name','asc')->get();

        return view('content.user.attendee', compact('pageConfigs', 'results','events','id'));
    }
}
