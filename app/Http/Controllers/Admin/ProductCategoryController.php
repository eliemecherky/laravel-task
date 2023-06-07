<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use DataTables;;

class ProductCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Categories List|Categories Read|Categories Add|Categories Edit|Categories Delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:Categories Add', ['only' => ['create', 'store']]);
        $this->middleware('permission:Categories Edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Categories Delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        $categories = ProductCategory::all();

        return view('admin.product-categories.index', compact('categories'));
    }

    public function getCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductCategory::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url_show = route('categories.show', $row->id);
                    $url_update = route('categories.edit', $row->id);

                    $actionBtn = '';

                    if (auth()->user()->can('Categories Read')) {
                        $actionBtn .= '<a class="btn btn-info btn-sm mr-2" href="' . $url_show . '">Show</a>';
                    }

                    if (auth()->user()->can('Categories Edit')) {
                        $actionBtn .= '<a href="' . $url_update . '" class="edit btn btn-success btn-sm mr-2" data-id="' . $row->id . '">Edit</a>';
                    }

                    if (auth()->user()->can('Categories Delete')) {
                        $actionBtn .= '<a href="javascript:void(0)" class="edit btn btn-danger btn-sm deleteCategory" data-id="' . $row->id . '">Delete</a>';
                    }

                    return $actionBtn;
                })
                ->editColumn('status', function (ProductCategory $row) {
                    if ($row->status == 1) {
                        return "<span class='btn btn-success btn-sm'>Active</span>";
                    } else {
                        return "<span class='btn btn-danger btn-sm'>InActive</span>";
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('admin.product-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:product_categories,slug',
        ]);


        $inputs = $request->all();
        $inputs["status"] = isset($inputs["status"]) ? 1 : 0;
        $category = ProductCategory::create($inputs);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $category = ProductCategory::find($id);
        return view('admin.product-categories.show', compact('category'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $category = ProductCategory::findOrFail($id);
        return view('admin.product-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        request()->validate([
            'name' => 'required|max:255',
            'slug' => 'required|unique:product_categories,slug,' . $id,
        ]);

        $category = ProductCategory::findOrFail($id);
        $inputs = $request->all();
        $inputs["status"] = isset($inputs["status"]) ? 1 : 0;
        $category->update($inputs);

        return redirect()->route('categories.index')
            ->with('success', 'category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {

        $id = $request->id;
        $category = ProductCategory::find($id);
        if ($category) {
            $category->delete();
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
