<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Bus_request;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $results = User::where('type', '!=', 'Admin')->where('type', '!=', 'User')->orderBy('id', 'DESC')->get();

        return view('content.operator.index', compact('pageConfigs', 'results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];

        return view('content.operator.create', compact('pageConfigs'));
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
                'name' => ['required', 'string', Rule::unique('users', 'name')],
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => 'required|string',
                'mobile' => 'required|integer',
                'status' => 'required|string',
                'address' => 'required|string',
                'country' => 'required|string',
                'pincode' => 'required|integer',
            ],
            [
                'name.required'      => 'Operator Name is required.',
                'email.required'      => 'Operator Email is required.',
                'mobile.required'      => 'Operator Phone is required.',
                'country.required'      => 'Operator County is required.',
                'pincode.required'      => 'Operator Zipcode is required.',
                'status.required'      => 'Operator Status is required.',
                'email.required'      => 'Operator Email is required.',
                'password.required'      => 'Operator Password is required.',
            ]
        );
        try {
            $store  = new User();
            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }
            $store->type = 'Operator';
            $store->password = Hash::make($request->password);
            $store->save();
            $response = array('status' => 200, 'redirect' => true, 'url' => url('admin/operator'), 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => $th);
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
        $result =  User::find($id);

        return view("operator.view", compact('result'));
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

            $result =  User::select('name', 'email', 'password', 'mobile', 'address', 'country', 'pincode', 'status')->find($id);

            $response = array('status' => 200, 'result' => $result);
        } catch (\Throwable $th) {

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
                'name' => ['required', 'string'],
                'email' => ['required', 'email'],
                'mobile' => 'required|integer',
                'status' => 'required|string',
                'address' => 'required|string',
                'country' => 'required|string',
                'pincode' => 'required|integer',
            ],
            [
                'name.required'      => 'Operator Name is required.',
                'email.required'      => 'Operator Email is required.',
                'mobile.required'      => 'Operator Phone is required.',
                'country.required'      => 'Operator County is required.',
                'pincode.required'      => 'Operator Zipcode is required.',
                'status.required'      => 'Operator Status is required.',
                'email.required'      => 'Operator Email is required.'
            ]
        );
        try {
            $update  = User::find($id);
            foreach ($requested_data as $key => $data) {
                $update->$key = $data;
            }
            // $update->password = Hash::make($request->password);
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
        User::find($id)->delete($id);

        return json_encode([
            'status' => 200,
            'msg' => 'Record deleted successfully!'
        ]);
    }

    public function status($id)
    {
        try {
            $update  = User::find($id);
            $update->status = !$update->status;
            $update->save();
            $response = array('status' => 200, 'msg' => 'Status updated successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return json_encode($response);
    }

    public function opeartor_request(Request $request)
    {
        $pageConfigs = ['pageHeader' => false];

        $events = Product::where(['status' => 1])->orderBy('id', 'DESC')->get();

        $status="";

        $status_event="";
        
        $status_date="";

        if (!empty($request->event_id)) {
            $results = Bus_request::select('bus_request.*', 'products.name as event_name', 'products.image', 'bus_details.bus_number')
                ->join('products', 'bus_request.product_id', '=', 'products.id')
                ->join('bus_details', 'bus_request.bus_id', '=', 'bus_details.id')
                ->where(['bus_request.operator_id' => Auth::id(), 'bus_request.product_id' => $request->event_id])
                ->get();
            $status_event=$request->event_id;
        }elseif(!empty($request->filter_date)){
            $results = Bus_request::select('bus_request.*', 'products.name as event_name', 'products.image', 'bus_details.bus_number')
                ->join('products', 'bus_request.product_id', '=', 'products.id')
                ->join('bus_details', 'bus_request.bus_id', '=', 'bus_details.id')
                ->where('bus_request.operator_id', Auth::id())
                ->where('bus_request.request_date', 'like', '%' . $request->filter_date . '%')
                ->get();
            $status_date=$request->filter_date;                
        }elseif(!empty($request->status)){
            $results = Bus_request::select('bus_request.*', 'products.name as event_name', 'products.image', 'bus_details.bus_number')
                ->join('products', 'bus_request.product_id', '=', 'products.id')
                ->join('bus_details', 'bus_request.bus_id', '=', 'bus_details.id')
                ->where(['bus_request.operator_id'=> Auth::id(),'bus_request.request_status'=>(int) $request->status])
                ->get();
            $status=$request->status;
        } else {
            $results = Bus_request::select('bus_request.*', 'products.name as event_name', 'products.image', 'bus_details.bus_number')
                ->join('products', 'bus_request.product_id', '=', 'products.id')
                ->join('bus_details', 'bus_request.bus_id', '=', 'bus_details.id')
                ->where(['bus_request.operator_id' => Auth::id()])
                ->get();
        }

        return view('content.Operator.operator_request', compact('pageConfigs', 'results', 'events','status','status_event','status_date'));
    }

    public function admin_request(Request $request)
    {
        if (Auth::user()->type == 'Admin') {

            $pageConfigs = ['pageHeader' => false];

            $events = Product::where(['status' => 1])->orderBy('id', 'DESC')->get();

            $operators = User::where(['type' => 'operator', 'status' => 1])->orderBy('id', 'DESC')->get();

            $results = Bus_request::select('bus_request.*', 'products.name as event_name', 'bus_details.bus_number', 'users.name as operator_name')
                ->join('products', 'bus_request.product_id', '=', 'products.id')
                ->join('bus_details', 'bus_request.bus_id', '=', 'bus_details.id')
                ->join('users', 'bus_request.operator_id', '=', 'users.id')
                ->get();

            return view('content.Operator.admin_request', compact('pageConfigs', 'events', 'operators', 'results'));
        } else {
            return redirect('admin/home');
        }
    }

    public function bus_list(Request $request)
    {
        try {

            $results = Bus::select('bus_number', 'id')->where(['operator_id' => $request->id])->orderBy('id', 'DESC')->get();

            $response = array('status' => 200, 'data' => $results);
        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return json_encode($response);
    }

    public function admin_request_submit(Request $request)
    {
        $requested_data = $request->except(['_token', '_method', 'request_time']);

        $request->validate(
            [
                'product_id' => ['required', 'string'],
                'operator_id' => ['required', 'string'],
                'bus_id' => ['required', 'string'],
                'request_date' => 'required|date_format:Y-m-d',
                // 'request_time'=>'required',

            ],
            [
                'product_id.required'      => 'Event is required.',
                'operator_id.required'      => 'Operator is required.',
                'bus_id.required'      => 'Bus is required.',
                'request_date.required'      => 'Request Date is required.',
                // 'request_time.required'      => 'Request Time is required.',
            ]
        );

        try {
            $store  = new Bus_request();
            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }
            $store->save();
            $response = array('status' => 200, 'msg' => 'Request Send successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'errors' => $th);
        }
        return json_encode($response);
    }

    public function operator_request(Request $request, $id)
    {
        try {

            $update  = Bus_request::find($id);

            $all_request = Bus_request::select('id', 'request_date')->orderBy('id', 'desc')->get();

            foreach ($all_request as $key => $value) {
                if ($value->request_date == $update->request_date) {
                    if ($value->id == $id) {
                        $update->request_status = $request->status;
                        $update->save();
                    } else {
                        $other_update  = Bus_request::find($value->id);
                        $other_update->request_status = 4;
                        $other_update->save();
                    }
                }
            }

            $response = array('status' => 200, 'msg' => ucfirst('Request accepted successfully...!'));
        } catch (\Throwable $th) {

            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return json_encode($response);
    }
}
