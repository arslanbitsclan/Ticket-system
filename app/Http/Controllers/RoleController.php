<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit-role', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add-role', ['only' => ['create', 'store']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("role.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::orderbyRaw("SUBSTRING_INDEX(name, '-', -1) , SUBSTRING_INDEX(name, '-', 1) DESC")->get();
        return view("role.create", get_defined_vars() );
    }
    
    public function fetch_role_ajax(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at',
            3 => 'id',
        );
        
        $query = DB::table('roles');
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("roles.name", "like", "%" . $search_value . "%");
            });
            $totalFiltered = $query->count();
        }

        $query = $query->orderBy($columns[$request['order'][0]['column']], $request['order'][0]['dir'])
            ->offset($request->start)
            ->limit($request->length);

        $result = $query->get();
        $data = [];

        //Below code is formula to calculate and show serial no in datatable, both ascending and descending. In descending case we calculate staring sr no and each page seriol no using formula
        $page_number = round(ceil($request->start / $request->length));
        if ($request['order'][0]['dir'] == "desc" && $request['order'][0]['column'] == 0) {
            $sr_no = $totalFiltered - ($request->length * $page_number);
        } else {
            $sr_no = $request->start;
        }
        
        foreach ($result as $role) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('role.table-cols', ["role" => $role, "col" => "name"])->render(),
                
                view('role.table-cols', ["role" => $role, "col" => "created_at"])->render(),

                view('role.table-cols', ["role" => $role, "col" => "actions"])->render(),

            ];
            array_push($data, $row);
        }

        return [
            "draw" => $request->draw,
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $request->validated();
        try
        {
            $role = Role::create([
                'name' => $request->name
            ]);

            $permissions = $request->input('permission');
            foreach ($permissions as $context => $status){
                $role->givePermissionTo($context);
            }

            if ($role){
                return redirect()->route('role.index')->with('success', 'New Role has been added successfully');
            }else{
                return redirect()->route('role.index')->with('warning', 'Please try again!!');
            }
        }
        catch (Exception $e){
            return redirect()->route('role.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
        }
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
    public function edit(Role $role)
    {
        $permissions = Permission::orderbyRaw("SUBSTRING_INDEX(name, '-', -1) , SUBSTRING_INDEX(name, '-', 1) DESC")->get();
        $role_permissions = $role->permissions->pluck("name")->toArray();
        return view('role.edit', get_defined_vars() );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $validate_name = ( $role->name == $request->name ) ? false : true;
        $request->validate([
            'name' => 'required',
        ]);
        try
        {
            $role->name = $request->name;
            $permissions = $request->input('permission');
            $permissions_array = [];
            foreach ($permissions as $context => $status){
                array_push($permissions_array, $context);
            }
            $role->syncPermissions($permissions_array);

            if ( $role->update() ){
                return redirect()->route('role.index')->with('success', 'Role has been Updated Successfully');
            }else{
                return redirect()->route('role.index')->with('warning', 'Please try again!!');
            }
        }
        catch (Exception $e)
        {
            return redirect()->route('role.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
        }
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
    
    public function delete_ajax(Request $request){
        $role  = Role::find($request->id);
        $role->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
