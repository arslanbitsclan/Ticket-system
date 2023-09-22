<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PermissionRequest;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:edit-permission', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add-permission', ['only' => ['create', 'store']]);
        $this->middleware('permission:delete-permission', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('permissions.index');
    }
    
    public function fetch_permission_ajax(Request $request){
        $columns = array(
            0 => 'name',
            1 => 'created_at',
            2 => 'id',
        );
        
        $query = DB::table('permissions')->orderbyRaw("SUBSTRING_INDEX(name, '-', -1) , SUBSTRING_INDEX(name, '-', 1) DESC");
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("permissions.name", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $permissions) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('permissions.table-cols', ["permissions" => $permissions, "col" => "name"])->render(),
                
                view('permissions.table-cols', ["permissions" => $permissions, "col" => "created_at"])->render(),

                view('permissions.table-cols', ["permissions" => $permissions, "col" => "actions"])->render(),

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $validated = $request->validated();
        if($validated){
            $permission = Permission::create([
                'name' => $request->name,
            ]);
        

            if($permission)
            {
                return redirect()->route('permission.index')->with('success','New Permission has been added successfully');
            }
            else
            {
                return redirect()->route('permission.create')->with('warning','Please try again!!');
            }
        } else
        {
            return redirect()->route('permission.create')->with('warning','Please try again!!');
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
    
    public function delete_ajax(Request $request){
        $permission  = Permission::find($request->id);
        $permission->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
