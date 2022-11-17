<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\County;
use App\Models\Event;
use App\Models\Inventory;
use App\Models\Pick_Point;
use App\Models\Product;
use App\Models\Product_variation;
use App\Models\Route_sku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $results = Product::select('id','name','image','status')->orderBy('name', 'ASC')->get();

        return view('content.events.list', compact('pageConfigs', 'results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];

        $points = Pick_Point::where(['status' => 1])->orderBy('name', 'DESC')->get();

        $county = County::where(['status' => 1])->orderBy('name', 'DESC')->get();

        $events = Event::where(['status' => 1])->orderBy('name', 'DESC')->get();

        $category = Category::where(['status' => 1])->orderBy('name', 'DESC')->get();

        return view('content.events.index', compact('pageConfigs', 'points', 'county', 'events', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return response()->json(trim($request->description));

        $requested_data = $request->except(['_token', '_method']);

        $request->validate(
            [
                'name' => 'required|string',
                'description' => 'required|string',
                'shortdesc' => 'required|string',
                'counties_id' => 'required',
                'sku' => 'required|string',
                'pickup_point_id' => 'required',
                'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
                'date_concert' => 'required',
                'check_ins_per_ticket' => 'required',
                'category_id' => 'required',
                'check_ins_per_ticket' => 'required',
                'meta_title' => 'required|string',
                'meta_tag' => 'required|string',
                'meta_description' => 'required|string',
            ],
            [
                'name.required' => 'Event name is required.',
                'sku.required' => 'Event Sku is required.',
                'pickup_point_id.required'    => 'Event Pickup Point is required.',
                'counties_id.required'    => 'Event Counties is required.',
                'image.required' => 'Event Image is required.',
                'category_id.required'    => 'Event Category is required.',
                'check_ins_per_ticket.required'    => 'Event check ins per ticket is required.',
                'date_concert.required'      => 'Event Date is required.',
                'meta_title.required' => 'Event Meta Title is required.',
                'meta_tag.required' => 'Event Meta Tag is required.',
                'meta_description.required' => 'Event Meta Description is required.',
            ]
        );


        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $request->image->extension();
            $path = public_path() . '/images/uploads/event_image';
            $file->move($path, $filename);
        }

        try {

            $store  = new Product();
            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }

            $store->counties_id = implode(', ', $requested_data['counties_id']);

            $store->pickup_point_id = trim(implode(', ', $requested_data['pickup_point_id']));

            // $store->pickup_point_id = trim(substr(str_replace('all', '', implode(', ', $requested_data['pickup_point_id'])),1));

            $store->allow_ticket_check_out = (isset($requested_data['allow_ticket_check_out'])) ? true : false;

            $store->image = $filename;

            $store->created_by = Auth::user()->id;

            $store->save();

            $concert_date = explode(', ', $requested_data['date_concert']);

            $pickup_point = array_filter($requested_data['pickup_point_id']);

            $routeSKU = $requested_data['sku'];

            $date_num = 1;

            $str = "";

            foreach ($concert_date as $date) {
                $point_num = 1;
                foreach ($pickup_point as $point) {
                    $route_store  = new Route_sku();
                    $str = Pick_Point::join('counties', 'counties.id', '=', 'pickup_points.counties_id')->where('pickup_points.id', $point)->first(['counties.name']);
                    $name = strtoupper($str->name[0]) . strtoupper(substr($str->name, -1));
                    $route_store->sku_name = $routeSKU . '-' . 'D' . $date_num . '-' . $name . $point_num++;
                    $route_store->product_id = $store->id;
                    $route_store->pickup_point_id = $point;
                    $route_store->created_by = Auth::user()->id;
                    $route_store->save();
                }
                $date_num++;
            }
            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'ss' => $th->getMessage());
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
        $result = Product::find($id);

        $points = Pick_Point::where(['id' => $id, 'status' => 1])->orderBy('id', 'DESC')->get();

        $county = County::where(['status' => 1])->orderBy('id', 'DESC')->get();

        $events = Event::where(['id' => $result->event_id])->orderBy('id', 'DESC')->get();

        $category = Category::where(['status' => 1])->orderBy('id', 'DESC')->get();

        return view("product.view", compact('result', 'points', 'county', 'events', 'category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageConfigs = ['pageHeader' => false];

        $result = Product::find($id);

        $pickup_points = [];

        foreach (explode(',', $result->pickup_point_id) as $key => $value) {

            $pickup_points[] = Pick_Point::where(['id' => trim($value)])->orderBy('id', 'DESC')->first();
        }

        $counties = County::where(['status' => 1])->orderBy('id', 'DESC')->get();

        $events = Event::where(['status' => 1])->orderBy('id', 'DESC')->get();

        $categories = Category::where(['status' => 1])->where('parent_id', '=', null)->orderBy('id', 'DESC')->get();


        // $pickup_points = Pick_Point::where(['status' => 1])->orderBy('id', 'DESC')->get();

        // $arr_point = explode(',', $result->pickup_point_id);

        // $arr_county = explode(',', $result->counties_id);

        // $arr_category = explode(',', $result->category_id);

        // foreach ($arr_point as $key => $value) {

        //     $pickup_point[] = Pick_Point::where(['id' => $value])->get();
        // }

        // foreach ($arr_county as $key => $value) {
        //     $county[] = County::where(['id' => $value])->get();
        // }

        // foreach ($arr_category as $key => $value) {
        //     $category[] = Category::where(['id' => $value])->get();
        // }

        return view('content.events.edit', compact('pageConfigs', 'result', 'pickup_points',  'counties', 'events',  'categories'));
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
                'name' => 'required|string',
                'description' => 'required|string',
                'shortdesc' => 'required|string',
                'counties_id' => 'required',
                'sku' => 'required|string',
                'date_concert' => 'required',
                'category_id' => 'required',
                'check_ins_per_ticket' => 'required',
                'meta_title' => 'required|string',
                'meta_tag' => 'required|string',
                'meta_description' => 'required|string',
            ],
            [
                'name.required' => 'Event name is required.',
                'sku.required' => 'Event Sku is required.',
                'image.required' => 'Event Image is required.',
                'counties_id.required'    => 'Event Counties is required.',
                'category_id.required'    => 'Event Category is required.',
                'check_ins_per_ticket.required'    => 'Event check ins per ticket is required.',
                'date_concert.required'      => 'Event Date is required.',
                'meta_title.required' => 'Event Meta Title is required.',
                'meta_tag.required' => 'Event Meta Tag is required.',
                'meta_description.required' => 'Event Meta Description is required.',
            ]
        );

        $store = Product::find($id);

        try {

            $concert_date = explode(', ', $requested_data['date_concert']);

            $pickup_point = array_filter($requested_data['pickup_point_id']);

            $routeSKU = $requested_data['sku'];

            $date_num = 1;

            $str = "";

            $sku = Route_sku::select('pickup_point_id')->where('product_id', $id)->get()->toArray();

            $newSKU = [];

            foreach ($sku as $k => $v) {
                $newSKU[] = $v['pickup_point_id'];
            }

            $new_point = [];

            foreach ($pickup_point as $key => $value) {

                if (!in_array($value, $newSKU)) {

                    $new_point[] = $value;
                }
            }

            foreach ($concert_date as $date) {
                $point_num = 1;
                foreach (array_diff($new_point, array("all")) as $point) {
                    $route_store  = new Route_sku();
                    $str = Pick_Point::join('counties', 'counties.id', '=', 'pickup_points.counties_id')->where('pickup_points.id', $point)->first(['counties.name']);
                    $name = strtoupper($str->name[0]) . strtoupper(substr($str->name, -1));
                    $route_store->sku_name = $routeSKU . '-' . 'D' . $date_num . '-' . $name . $point_num++;
                    $route_store->product_id = $store->id;
                    $route_store->pickup_point_id = $point;
                    $route_store->created_by = Auth::user()->id;
                    $route_store->save();
                }
                $date_num++;
            }

            // foreach ($concert_date as $date) {
            //     $point_num=1;
            //     foreach ($pickup_point as $point) {
            //         $route_store  = new Route_sku();
            //         $str=Pick_Point::join('counties','counties.id','=','pickup_points.counties_id')->where('pickup_points.id', $point)->first(['counties.name']);
            //         $name= strtoupper($str->name[0]).strtoupper(substr($str->name,-1));
            //         $route_store->sku_name=$routeSKU.'-'.'D'.$date_num.'-'.$name.$point_num++;
            //         $route_store->product_id=$store->id;
            //         $route_store->pickup_point_id =$point;
            //         $route_store->created_by =Auth::user()->id;
            //         $route_store->save();
            //     } 
            //     $date_num++; 
            // }

            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }

            $store->counties_id = implode(', ', $requested_data['counties_id']);
            $store->pickup_point_id = implode(', ', $requested_data['pickup_point_id']);
            $store->allow_ticket_check_out = (isset($requested_data['allow_ticket_check_out'])) ? true : false;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '.' . $request->image->extension();
                $path = public_path() . '/images/uploads/event_image';
                $file->move($path, $filename);
                $store->image = $filename;
            } else {
                $store->image = $store->image;
            }
            $store->created_by = Auth::user()->id;
            $store->save();

            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => $th->getMessage());
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
            Product::find($id)->delete($id);
            $response = array('status' => 200, 'msg' => 'Record deleted successfully!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return json_encode($response);
    }

    public function status($id)
    {
        try {
            $update  = Product::find($id);
            $update->status = !$update->status;
            $update->save();
            $response = array('status' => 200, 'msg' => 'Status updated successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return json_encode($response);
    }

    public function product_variation(Request $request)
    {
        $pageConfigs = ['pageHeader' => false];

        $products = Product::select('id', 'name')->orderBy('name', 'desc')->get();

        $results = [];

        $product_name = "";

        $product_id = ($request->id) ? $request->id : "";

        if (!empty($request->id)) {
            $product = Product::find($request->id);

            $product_name = $product->name;

            $date_concert = explode(',', $product->date_concert);
            $response = [];
            foreach ($date_concert as $concert) {
                $counties_id = explode(',', $product->counties_id);
                foreach ($counties_id as $key => $value) {
                    $counties = County::where(['id' => $value])->get();
                    foreach ($counties as $key => $county) {
                        $pick = Pick_Point::where(['counties_id' => $county->id])->get();
                        // $variation[] = Product_variation::where(['counties_id' => $county->id, 'product_id' => $id,'produ'])->get();
                        $results[] = array('date_concert' => $concert, 'county' => $county, 'pick_point' => $pick, 'product' => $product);
                    }
                }
            }
        }

        return view('content.events.variations', compact('pageConfigs', 'products', 'results', 'product_name', 'product_id'));
    }

    public function add_variation(Request $request, $id)
    {

        $requested_data = $request->except(['_token', '_method']);

        // if (empty($request->get('stock_quantity'))) {
        //     $response = array('status' => 400, 'pickup_id' => $request->get('pickup_id'), 'stock_quantity' => $request->get('stock_quantity'), 'stock_quantity' => 'stock quantity is required.');
        //     return json_encode($response);
        // }

        if (empty($request->get('price'))) {
            $response = array('status' => 400, 'pickup_id' => $request->get('pickup_id'), 'price' => 'Price is required.');
            return json_encode($response);
        }

        $record = Product_variation::find($id);

        if (empty($record)) {

            $store = new product_variation;

            $store->date_concert = $request->get('date');

            $store->price = $request->get('price');

            $store->counties_id = $request->get('county_id');

            $store->pickup_point_id = $request->get('pickup_id');

            $store->product_id = $request->get('product');

            $store->stock_quantity = $request->get('stock_quantity');

            $store->created_by = Auth::user()->id;

            $store->save();
        } else {

            $record->price = $request->get('price');

            // $record->stock_quantity = $request->get('stock_quantity');

            $record->save();
        }


        if (!empty($record) || !empty($store)) {
            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } else {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }

        return json_encode($response);
    }

    public function add_variation_by_form(Request $request)
    {
        $requested_data = $request->except(['_token', '_method']);

        $request->validate(
            [
                'price.*' => 'required',
                'stock_quantity.*' => 'required',
            ],
            [
                'price.*.required'    => 'The price is required.',
                'stock_quantity.*.required'    => 'The stock quantity is required.'
            ]
        );

        try {
            $dates = $request->date;
            $products = $request->product_id;
            $prices = $request->price;
            $counties = $request->county_id;
            $pickups = $request->pickup_id;
            $quantity = $request->stock_quantity;

            for ($i = 0; $i < count($dates); $i++) {
                $date = $dates[$i];
                $product_id = $products[$i];
                $price = $prices[$i];
                $counties_id = $counties[$i];
                $pickup_id = $pickups[$i];
                $stock_quantity = (!empty($quantity[$i])) ? $quantity[$i] : 0;

                $variation_exist = Product_variation::where(['product_id' => $product_id, 'counties_id' => $counties_id, 'pickup_point_id' => $pickup_id, 'date_concert' => $date])->first();
                $data_array = array(
                    'date_concert' => $date,
                    'product_id' => $product_id,
                    'price' => $price,
                    'counties_id' => $counties_id,
                    'pickup_point_id' => $pickup_id,
                    'stock_quantity' => $stock_quantity
                );
                if (!$variation_exist) {
                    $data_array['created_by'] = Auth::user()->id;
                    Product_variation::insert($data_array);
                } else {
                    Product_variation::where('id', $variation_exist->id)->update($data_array);
                }
            }
            $response = array('status' => 200, 'msg' => 'Data saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!','err'=>$th->getTraceAsString());
        }
        return json_encode($response);
    }

    public function product_inventory(Request $request)
    {
        $pageConfigs = ['pageHeader' => FALSE];

        $points = "";

        $product_id = (isset($request->id))?$request->id:"";

        $variations = [];

        $products = Product::where(['status' => 1])->get();

        $inventory_list="";


        if (!empty($product_id)) {

            $inventory_list=Inventory::where(['status'=>1,'product_id'=>$product_id])->orderBy('id','desc')->get();

            $single_products = Product::where(['status' => 1, 'id' => $product_id])->first();

            $date = array_map('trim', explode(',', $single_products->date_concert));

            $points = Pick_Point::select('pickup_points.*', 'counties.name as county_name')
                ->join('counties', 'counties.id', '=', 'pickup_points.counties_id')
                ->where('pickup_points.status', 1)->whereIn('pickup_points.id', array_map('trim', explode(',', $single_products->pickup_point_id)))->get();

            foreach ($date as $k => $val) {

                foreach ($points as $key => $value) {

                    $variations[] = ['id' => $value->id, 'name' => 'Date of Concert::' . "\x20" . date("D j F, Y", strtotime($val)) . "\x20" . ' ,County you wish to Travel from::' . "\x20" . $value->county_name . "\x20" . ' ,Pickup Points and Departure Times::' . "\x20" . $value->name, 'date' => $val];
                }
            }

            $product_id = $request->id;
        }

        return view('content.events.inventory', compact('pageConfigs', 'points', 'products', 'product_id', 'variations','inventory_list'));
    }

    public function store_inventory(Request $request)
    {
        $requested_data = $request->except(['_token']);

        $request->validate(
            [
                'group_name' => 'required|string',
                'total_seat' => 'required|integer',
                'pickup_point' => 'required',
            ]
        );

        $store = new Inventory();

        try {
            $store  = new Inventory();
            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }
            $store->pickup_point = implode(' ,', $request->pickup_point);
            $store->date_concert = implode(' ,', $request->date_concert);
            $store->created_by = Auth::user()->id;
            $store->save();

            $response = array('status' => 200, 'msg' => 'record saved successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!', 'th' => $th->getMessage());
        }
        return json_encode($response);
    }

    public function update_inventory(Request $request)
    {
        $requested_data = $request->except(['_method','_token','group_id']);

        $id=$request->group_id;

        $pickup_date=[];

        $pickup_point=[];

        foreach ($request->pickup_point as $key => $value) {
            $val = explode('#',$value);
            $pickup_point[]=$val[0];
            $pickup_date[]=$val[1];
        }

        try {
            $update=Inventory::find($id);
            foreach ($requested_data as $k => $item) {
                $update->$k=$item;
            }

            $update->pickup_point = implode(' ,', $pickup_point);

            $update->date_concert = implode(' ,', $pickup_date);

            $update->created_by = Auth::user()->id;

            $update->save();

            $response = array('status' => 200, 'msg' => 'record updated successfully...!');

        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!','th' => $th->getMessage());
        }
        return json_encode($response);
    }
}
