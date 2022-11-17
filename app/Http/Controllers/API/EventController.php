<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\County;
use App\Models\Event;
use App\Models\Pick_Point;
use App\Models\Product;
use App\Models\Product_variation;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query =  Product::select('id', 'name', 'image', 'category_id');

        if (!empty($request->get('category_id'))) {
            $query->where('category_id', $request->get('category_id'));
        }

        $results = $query->get();

        foreach ($results as $key => $event) {

            $event->image = asset('uploads/event_image') . '/' . $event->image;
            if (!empty($request->get('category_id'))) {
                $cat = Category::select('name')->find($request->get('category_id'));
                $event->category_name = $cat->name;
            } else {
                $event->category_name = 'ALL EVENT';
            }

            $variation = Product_variation::where(['product_id' => $event->id])->get();

            $min_price = ($variation->min('price')) ? $variation->min('price') : 'N/A';

            $max_price = ($variation->max('price')) ? $variation->max('price') : 'N/A';

            $event->price = $min_price  . '-' . $max_price;
        }

        if ($results->count() > 0) {

            $response = array('status' => 200, 'data' => $results);
        } else {

            $response = array('status' => 500, 'msg' => 'No Record Founds');
        }
        return response()->json($response);
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

    public function select_county_by_point(Request $request, $id)
    {
        try {
            $pickup = Pick_Point::where(['id' => $id])->first()->makeHidden(['created_by', 'status', 'created_at', 'updated_at']);

            $county = County::where('id', $pickup->counties_id)->first()->makeHidden(['created_by', 'status', 'created_at', 'updated_at']);

            if ($county->count() > 0) {
                $response = array('status' => 200, 'data' => $county);
            } else {
                $response = array('status' => 500, 'msg' => 'No Record Found');
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return response()->json($response);
    }

    public function county_by_Id($id)
    {
        $county = County::where(['id' => $id])->get()->makeHidden(['created_by', 'status', 'created_at', 'updated_at']);

        if ($county->count() > 0) {
            $response = array('status' => 200, 'data' => $county);
        } else {
            $response = array('status' => 500, 'msg' => 'No Record Found');
        }

        return response()->json($response);
    }

    public function get_price(Request $request, $product_id, $county_id, $point_id)
    {
        try {
            $price = Product_variation::select('price', 'stock_quantity')->where(['product_id' => $product_id, 'counties_id' => $county_id, 'pickup_point_id' => $point_id])->first();

            if ($price->count() > 0) {
                $response = array('status' => 200, 'data' => $price);
            } else {
                $response = array('status' => 500, 'msg' => 'No Record Found');
            }
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return response()->json($response);
    }

    public function book_product(Request $request)
    {
        $requested_data = $request->except(['_token']);
        $request->validate(
            [
                'product_id' => 'required|string',
                'booking_date' => 'required|date',
                'county_id' => 'required|string',
                'pickup_id' => 'required|string',
                'event_id' => 'required|string',
                'created_by' => 'required|string',
                'booking_amount' => 'required|string',
                'no_of_seats_booked' => 'required|string',
            ],
            [
                'product_id.required'      => 'Product id is required.',
                'booking_date.required'      => 'Booking Date is required.',
                'county_id.required'      => 'County is required.',
                'pickup_id.required'      => 'Pickup Point id is required.',
                'created_by.required'      => 'Created By is required.',
                'booking_amount.required'      => 'Booking Amount is required.',
                'no_of_seats_booked.required'      => 'No of Seats Booked is required.',
            ]
        );

        try {
            $store  = new Booking();
            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }
            $store->save();
            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return response()->json($response);
    }

    public function product_detail(Request $request, $id)
    {
        $result =  Product::find($id);

        $variation = Product_variation::where(['product_id' => $result->id])->get();

        $min_price = ($variation->min('price')) ? $variation->min('price') : 'N/A';

        $max_price = ($variation->max('price')) ? $variation->max('price') : 'N/A';

        $result->price = $min_price  . '-' . $max_price;

        $result->image = asset('uploads/event_image') . '/' . $result->image;

        $result->date_concert = explode(',', $result->date_concert);

        $county = explode(',', $result->counties_id);

        $result->counties = County::whereIn('id', $county)->get();

        $points = explode(',', $result->pickup_point_id);

        $result->pickup_point = Pick_Point::whereIn('id', $points)->get();

        if ($result) {

            $response = array('status' => 200, 'result' => $result);
        } else {

            $response = array('status' => 400, 'msg' => 'Something went wrong...!');
        }
        return response()->json($response);
    }

    public function search_product(Request $request, $search)
    {

        $result = Product::select('id', 'name', 'image')->where('name', 'LIKE', '%' . $search . '%')->where('status', 1)->get();

        foreach ($result as $key => $product) {

            $product->image = asset('uploads/event_image') . '/' . $product->image;

            $variation = Product_variation::where(['product_id' => $product->id])->get();

            $min_price = ($variation->min('price')) ? $variation->min('price') : 'N/A';

            $max_price = ($variation->max('price')) ? $variation->max('price') : 'N/A';

            $product->price = $min_price  . '-' . $max_price;
        }

        if (!empty($result)) {
            $response = array('status' => 200, 'result' => $result);
        } else {
            $response = array('status' => 400, 'msg' => 'No Record Found...!');
        }

        return response()->json($response);
    }

    public function createPaymentIntent(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('constants.stripe_secret'));

        $intent = \Stripe\PaymentIntent::create([
            'description' => 'Payment Collected on behalf of ShopOnline.com',
            'shipping' => [
                'name' => 'Jenny Rosen',
                'address' => [
                    'line1' => '510 Townsend St',
                    'postal_code' => '98140',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'country' => 'US',
                ],
                'phone' => '9977945612',
            ],
            'amount' => 1099,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            "metadata" => [
                "additional_information" => "test msg",
                "user_email" => "test@gmail.com",
                'user_id' => 1,
                "product_id" => 23,
                "county_id" => 1,
                "pickup_id" => 12,
                "event_id" => 1,
                "attendeeName" => "john1, john2",
                "attendeeNumber" => "997794153, 997794154",
            ],
        ]);

        $client_secret = $intent->client_secret;

        return response()->json($client_secret);
    }

    public function resetmsg(Request $request)
    {
        if (is_array($request->get('attendee_details'))) {
            $k = 0;
            $arr = [];
            $jem = [];
            foreach ($request->get('attendee_details') as $key => $value) {
                $arr[$k] = $value['attendee_name'];
                $jem[$k] = $value['attendee_number'];
                // $arr[$k]['number'] = $value['attendee_number'];
                $k++;
            }
            // $input = array("attendee_name", "attendee_number", "attendee_name", "attendee_number");
            // $data = [];
            // foreach ($input as $value) {
            //     $assign = "50"; /* The data just temp */
            //     $data[][$value] = $assign;
            // }

            // return response()->json($data);
            // die;
            $meta = [
                "metadata" =>
                [
                    "additional_information" => "test msg",
                    "user_email" => "test@gmail.com",
                    'user_id' => 1,
                    "product_id" => 23,
                    "county_id" => 1,
                    "pickup_id" => 12,
                    "event_id" => 1,
                    "attendeeName" => implode(', ', $arr),
                    "attendeeNumber" => implode(', ', $jem)
                    // $response,
                    // "attendeeName" => "john1",
                    // "attendeeNumber" => "997794153",
                ]
            ];
            // foreach ($request->get('attendee_details') as $key => $value) {
            //     $response[]['attendee_name'] = $value['attendee_name'];
            //     $response[]['attendee_number'] = $value['attendee_number'];
            // }
        } else {
            $response = 'no record found';
        }

        // return Response::json(['msg' => var_dump($meta)]);
        return Response::json(['msg' => $meta]);
    }
}
