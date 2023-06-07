<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Storage;
use Illuminate\Support\Facades\App;

class ProductController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:Products List|Products Read|Products Add|Products Edit|Products Delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:Products Add', ['only' => ['create', 'store']]);
        $this->middleware('permission:Products Edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Products Delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.products.index');
    }

    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url_show = route('products.show', $row->id);

                    $actionBtn = '';

                    if (auth()->user()->can('Products Read')) {
                        $actionBtn .= '<a class="btn btn-info btn-sm mr-2" href="' . $url_show . '">Show</a>';
                    }

                    if (auth()->user()->can('Products Edit')) {
                        $actionBtn .= '<a href="/products/' . $row->id . '/edit/ " class="edit btn btn-success btn-sm mr-2" data-id="' . $row->id . '">Edit</a>';
                    }

                    if (auth()->user()->can('Products Delete')) {
                        $actionBtn .= '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm deleteProduct" data-bs-toggle="modal" data-bs-target="" data-id="' . $row->id . '">Delete</a>';
                    }

                    return $actionBtn;
                })

                ->editColumn('status', function (Product $product) {
                    if ($product->status == 1) {
                        return '<span class="btn btn-success btn-sm">Active</span>';
                    } else {

                        return '<span class="btn btn-danger btn-sm">InActive</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductCategory::where('status', 1)->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        request()->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'price' => "required|numeric",
            'description' => "required",
            'product_category_id' => "required|exists:product_categories,id",
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->product_category_id = $request->product_category_id;
        $product->status = $request->status ? 1 : 0;

        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = ProductCategory::where('status', 1)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // dd($product);
        request()->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id,
            'price' => "required|numeric",
            'description' => "required",
            'product_category_id' => "required|exists:product_categories,id",
        ]);


        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->product_category_id = $request->product_category_id;
        $product->status = $request->status ? 1 : 0;

        $product->save();

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {

        $id = $request->id;
        $product = Product::find($id);
        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
