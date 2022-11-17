<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Password_reset;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DateTime;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class APIUserController extends Controller
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
        $rules = [];
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email', Rule::unique('users', 'email')],
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    'min:10',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/',      // must contain at least one digit
                    'regex:/[@$!%*#?&]/', // must contain a special character
                ]
            ],
            [
                'password.regex' => 'Password should be at least 10 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)',
                'email.ends_with' => 'Enter Email id ending with @wiespl.com only'
            ]
        );
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->messages() as $key => $value) {
                if ($key == 'email' || $key == 'password') {
                    $key = 'msg';
                    $result = array("status" =>  200, 'msg' => is_array($value) ? implode(',', $value) : $value);
                    return $result;
                } else {
                    $errors[$key] = is_array($value) ? implode(',', $value) : $value;
                }
                //implode is for when you have multiple errors for a same key
                //like email should valid as well as unique
            }
            $result = array("status" => count($errors) ? 422 : 200, 'data' => $errors);
            return $result;
        }
        try {
            $user = strstr($request->email, '@', true); // As of PHP 5.3.0
            $store  = new User();
            $store->name = $user;
            $store->email = $request->email;
            $store->password = Hash::make($request->password);
            $store->type = 'User';
            $store->save();
            $token = $store->createToken('mytoken');
            \Mail::to($request->email)->send(new \App\Mail\registerMail($store));

            $response = array('status' => 200, 'msg' => 'User created successfully...!', 'token' => $token->plainTextToken);
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => $th->getMessage());
        }
        return response()->json($response);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id)->makehidden(['created_at','updated_at']);

        if ($user) {
            $response = array('status' => 200, 'data' => $user);
        } else {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return response()->json($response);
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
        $request->validate(
            [
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'email' => 'required|email|unique:users',
            ]
        );
        try {
            $update  = User::find($id);
            $update->firstname = $request->firstname;
            $update->lastname = $request->lastname;
            $update->email = $request->email;
            $update->save();
            DB::commit();
            $response = array('status' => 200, 'msg' => 'User profile updated successfully');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return response()->json($response);
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

    public function userLogin(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required|min:8'
            ]
        );
        try {
            $employee  = User::select('id', 'name', 'email', 'password')->where(['email' => $request->email])->first();
            if ($employee) {
                if (Hash::check($request->password, $employee->password)) {
                    $token = $employee->createToken('mytoken');
                    $response = array('status' => 200, 'user' => $employee, 'token' => $token->plainTextToken);
                } else {
                    $response = array('status' => 500, 'msg' => 'Invalid password!');
                }
            } else {
                $response = array('status' => 500, 'msg' => "Employee not exist...!");
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'error' => $th->getMessage());
        }
        return response()->json($response);
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email'
            ],
            [
                'email.required' => 'Email is required'
            ]
        );

        try {
            $employee  = User::select('id', 'name', 'email', 'user_timestamp')->where(['email' => $request->email])->first();
            if ($employee) {

                $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%^*()';

                $token= substr(str_shuffle(str_repeat($pool, 5)), 0, 50);

                // $pwd_store = Password_reset::create([
                //     'email' => $request->email,
                //     'token' => $token,
                //     'created_at' => Carbon::now()
                // ]);

                $pwd_store=new Password_reset();
                $pwd_store->email=$request->email;
                $pwd_store->token=$token;
                $pwd_store->created_at=Carbon::now();
                $pwd_store->save();

                // date_default_timezone_set('Asia/Kolkata');
                // $update = User::find($employee->id);
                // $update->user_timestamp = strtotime('now');
                // $update->save();

                \Mail::to($pwd_store->email)->send(new \App\Mail\SendMail($pwd_store));

                $response = array('status' => 200, 'msg' => 'reset password link created successfully');
            } else {
                $response = array('status' => 500, 'msg' => "user not exist...!");
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'errors' => $th->getMessage());
        }
        return response()->json($response);
    }

    public function reset_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => ['required'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ], ['password.regex' => 'Password should be at least 10 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)']);
        try {
            $tokenData  = Password_reset::where(['token'=>$request->token])->first();
            if ($tokenData) {
                $update = User::where('email',$request->email)->first();
                if ($update) {
                    $update->password = Hash::make($request->password);
                    $update->update();
                    $response = array('status' => 200, 'msg' => 'Password changed successfully...!');
                } else {
                    $response = array('status' => 500, 'msg' => 'User not exist...!');
                }
            } else {
                $response = array('status' => 500, 'msg' => "User not exist...!");
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return response()->json($response);
    }

    public function update_password(Request $request, $id)
    {
        $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'current_password' => ['required'],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ], ['password.regex' => 'Password should be at least 10 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)']);
        if ($request->current_password == $request->password) {
            $response = array("message" =>  'The given data was invalid.', 'erros' => array('password' => array('password should be different from current password')));
            return response()->json($response, 422);
        }
        try {
            $update  = User::find($id);
            if ($update) {
                if (Hash::check($request->current_password, $update->password)) {
                    $update->firstname = $request->firstname;
                    $update->lastname = $request->lastname;
                    $update->email = $request->email;
                    $update->password = Hash::make($request->password);
                    $update->save();
                    $response = array('status' => 200, 'msg' => 'Password changed successfully...!');
                } else {
                    $response = array('status' => 500, 'msg' => 'Invalid current password!');
                }
            } else {
                $response = array('status' => 500, 'msg' => "User not exist...!");
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return response()->json($response);
    }

    public function user_update_password(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ], ['password.regex' => 'Password should be at least 10 characters, contain upper case, lower case, numbers and special characters (!@£$%^&)']);
        date_default_timezone_set("Asia/Kolkata");
        $id = base64_decode($request->id);
        $user  = User::find($id);
        if (empty($user)) {
            $response = array("message" =>  'The given data was invalid.', 'erros' => array('erros' => array('invalid user')));
            return response()->json($response, 422);
        }
        $token1 = trim(preg_replace("/\s+/", " ", base64_decode($request->token)));
        $token2 = date("d-m-Y h:i:sa", $user->user_timestamp);
        if ($token1 != $token2) {
            $response = array("message" =>  'The given data was invalid.', 'erros' => array('token' => array('token is invalid')));
            return response()->json($response, 422);
        }
        try {
            $current = date("d-m-Y h:i:sa", strtotime('now'));
            $start = strtotime($token1);
            $end =  strtotime($current);
            $hours = intval(($end - $start) / 3600);
            if ($hours >= 1) {
                $response = array("message" =>  'The given data was invalid.', 'errors' => array('errors' => array('token is out dated')));
                return response()->json($response, 422);
            } else {
                if ($user) {
                    $user->password = Hash::make($request->password);
                    $user->save();
                    $response = array('status' => 200, 'msg' => 'Password changed successfully...!');
                } else {
                    $response = array('status' => 500, 'msg' => 'Invalid current password!');
                }
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return response()->json($response, 200);
    }

    public function emailChecker(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email'
            ],
            [
                'email.required' => 'Username or email required'
            ]
        );

        try {
            $employee  = User::where(['email' => $request->email])->first();
            if ($employee) {
                $response = array('status' => 200, 'data' => $employee);
            } else {
                $response = array('status' => 500, 'data' => FALSE);
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'data' => 'Something went wrong...!');
        }
        return response()->json($response);
    }

    public function sendQR(Request $request)
    {
        $details=$request->email;
       
        // \Mail::to($request->email)->send(new \App\Mail\EventBooked($details));

        $qrCodeAsPng = QrCode::format('png')->size(500)->generate("https://github.com/konsav/email-templates/");

        // $res='<img src="'.$message->embedData($qrCodeAsPng,"nameForAttachment.png").'" />';

        // $res=$message->embedData($qrCodeAsPng, 'nameForAttachment.png');

        $response = array('status' => 200, 'data' => base64_encode($qrCodeAsPng));

        // return view('content.seo.qr');
        return response()->json($response, 200);
    }
}
