<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Hash;

class UserController extends Controller
{
    //

    function __construct()
    {
        $this->middleware('permission:Users List|Users Add|Users Edit|Users Delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:Users Add', ['only' => ['create', 'store']]);
        $this->middleware('permission:Users Edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Users Delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function getUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $url_show = route('users.show', $row->id);
                    $url_update = route('users.edit', $row->id);

                    $actionBtn = '';

                    if (auth()->user()->can('Users Read')) {
                        $actionBtn .= '<a class="btn btn-info btn-sm mr-2" href="' . $url_show . '">Show</a>';
                    }

                    if (auth()->user()->can('Users Edit')) {
                        $actionBtn .= '<a href="' . $url_update . '" class="edit btn btn-success btn-sm mr-2" data-id="' . $row->id . '">Edit</a>';
                    }

                    if (auth()->user()->can('Users Delete')) {
                        $actionBtn .= '<a href="javascript:void(0)" class="edit btn btn-danger btn-sm deleteUser" data-id="' . $row->id . '">Delete</a>';
                    }

                    return $actionBtn;
                })

                ->editColumn('roles', function (User $user) {
                    if (!empty($user->getRoleNames())) {
                        foreach ($user->getRoleNames() as $v) {

                            $role = $v;
                            return "<span class='btn btn-success btn-sm'>" . $role . "</span>";
                        }
                    }
                })

                ->rawColumns(['action', 'roles'])
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
        $roles = Role::pluck('name', 'name')->all();
        $permissions = Permission::all();
        return view('admin.users.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',

        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }

        // $user->update($request->all());
        $user->save();

        $user->assignRole($request->input('roles'));

        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        $permissions = Permission::all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('admin.users.edit', compact('user', 'roles', 'userRole', 'permissions'));
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
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',

        ]);


        $request["status"] = isset($request["status"]) && $request["status"] === "on" ? 1 : 0;

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');


        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
        }

        // $user->update($request->all());
        $user->save();
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        $user->syncPermissions($request->input('permissions', []));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request)
    {

        $id = $request->id;
        $user = User::find($id);
        if ($user) {
            $user->delete();
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
