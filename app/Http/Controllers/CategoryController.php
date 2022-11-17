<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageConfigs = ['pageHeader' => false];

        $results = Category::where(['parent_id' => null])->orderBy('id', 'DESC')->get();

        return view('content.category.index', compact('pageConfigs', 'results'));
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
                'name' => ['required', 'string', Rule::unique('categories', 'name')],
                'category_image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:500',
            ],
            [
                'name.required'      => 'Catgeory name is required.',
                'category_image.required' => 'Catgeory Image is required.',
            ]
        );

        if ($request->hasFile('category_image')) {
            $file = $request->file('category_image');
            $filename = time() . '.' . $request->category_image->extension();
            $path = public_path() . '/images/uploads/category_image';
            $file->move($path, $filename);
        }

        try {
            $store  = new Category();
            foreach ($requested_data as $key => $data) {
                $store->$key = $data;
            }
            $store->category_image = $filename;
            $store->created_by = Auth::user()->id;
            $store->category_slug = Str::slug($request->name, "-");
            $store->save();
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
        $result =  Category::find($id);
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
                'name' => ['required', 'string'],
                'category_image'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:500',
            ],
            [
                'name.required'      => 'Category name is required.',
            ]
        );

        try {

            $filename = "";

            if ($request->hasFile('category_image')) {
                $file = $request->file('category_image');
                $filename = time() . '.' . $request->category_image->extension();
                $path = public_path() . '/images/uploads/category_image';
                $file->move($path, $filename);
            }

            $update  = Category::find($id);

            foreach ($requested_data as $key => $data) {
                $update->$key = $data;
            }

            $update->category_image = !empty($filename) ? $filename : $update->category_image;

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
        Category::find($id)->delete($id);
        return json_encode([
            'status' => 200,
            'msg' => 'Record deleted successfully!'
        ]);
    }

    public function status($id)
    {
        try {
            $update  = Category::find($id);
            $update->status = !$update->status;
            $update->save();
            $response = array('status' => 200, 'msg' => 'Status updated successfully...!');
        } catch (\Throwable $th) {
            $response = array('status' => 500, 'msg' => 'Something went wrong...!');
        }
        return json_encode($response);
    }
}
