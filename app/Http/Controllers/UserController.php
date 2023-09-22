<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }
    
    
    public function fetch_user_ajax(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'first_name',
            2 => 'last_name',
            3 => 'email',
            4 => 'created_at',
            5 => 'id',
            6 => 'id'
        );
        
        $query = User::whereNull('deleted_at');
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("users.first_name", "like", "%" . $search_value . "%")
                    ->orWhere("last_name", "like", "%" . $search_value . "%")
                    ->orWhere("email", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $user) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('user.table-cols', ["user" => $user, "col" => "first_name"])->render(),
                
                view('user.table-cols', ["user" => $user, "col" => "last_name"])->render(),
                
                view('user.table-cols', ["user" => $user, "col" => "email"])->render(),
                
                view('user.table-cols', ["user" => $user, "col" => "role"])->render(),
                
                view('user.table-cols', ["user" => $user, "col" => "created_at"])->render(),

                view('user.table-cols', ["user" => $user, "col" => "actions"])->render(),

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
        $countries = Country::all();
        $roles = Role::all();
        return view('user.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $request->validated();
        try{
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' =>  $request->phone,
                'email' =>  $request->email,
                'password' =>  Hash::needsRehash($request->password) ? Hash::make($request->password) : $request->password,
                'city' =>  $request->city,
                'address' =>  $request->address,
                'country_id' =>  $request->country_id
            ]);
            
            if($user){
                $user->assignRole( $request->role );
                // add event when user created
                
                return redirect()->route('user.index')->with('success','User has been added successfully');
            }else{
                return redirect()->route('user.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            return redirect()->route('user.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(User $user)
    {
        $countries = Country::all();
        $roles = Role::all();
        return view('user.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|max:255',
            'phone' => 'required|min:7|max:12',
            'city' => 'required',
            'address' => 'required',
            'country' => 'required',
            'role' => 'required'  
        ]);
            
        try{
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->country_id = $request->country;
            $user->syncRoles( $request->role );
            if( $user->update() ){
                return redirect()->route('user.index')->with('success','User Updated Successfully');
            }else{
                return redirect()->route('user.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('user.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $user  = User::find($request->id);
        $user->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
