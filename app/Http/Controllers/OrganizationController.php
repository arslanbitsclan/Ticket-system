<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Country;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationRequest;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('organization.index');
    }
    
    public function fetch_organization_ajax(Request $request)
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
        
        $query = Organization::whereNull('deleted_at');
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("name", "like", "%" . $search_value . "%")
                    ->orWhere("email", "like", "%" . $search_value . "%")
                    ->orWhere("phone", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $organization) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('organization.table-cols', ["organization" => $organization, "col" => "name"])->render(),
                
                view('organization.table-cols', ["organization" => $organization, "col" => "email"])->render(),
                
                view('organization.table-cols', ["organization" => $organization, "col" => "phone"])->render(),
                
                view('organization.table-cols', ["organization" => $organization, "col" => "created_at"])->render(),

                view('organization.table-cols', ["organization" => $organization, "col" => "actions"])->render(),

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
        return view('organization.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationRequest $request)
    {
        $request->validated();
        try{
            $organization = Organization::create([
                'name' => $request->name,
                'email' =>  $request->email,
                'phone' =>  $request->phone,
                'address' =>  $request->address,
                'city' =>  $request->city,
                'country' =>  $request->country,
                'region' =>  $request->region,
                'postal_code' =>  $request->postal_code
            ]);
            
            if($organization){
                return redirect()->route('organization.index')->with('success','Organization has been added successfully');
            }else{
                return redirect()->route('organization.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            return redirect()->route('organization.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Organization $organization)
    {
        $countries = Country::all();
        return view('organization.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organization $organization)
    {
         $request->validate([
            'name' => 'required|min:2|max:255',
            'email' => 'required|max:255',
            'phone' => 'required|min:7|max:12',
            'city' => 'required',
            'address' => 'required',
            'country' => 'required',
            'region' => 'required',
            'postal_code' => 'required'
        ]);
            
        try{
            $organization->name = $request->name;
            $organization->email = $request->email;
            $organization->phone = $request->phone;
            $organization->address = $request->address;
            $organization->city = $request->city;
            $organization->country = $request->country;
            $organization->region = $request->region;
            $organization->postal_code = $request->postal_code;
            if( $organization->update() ){
                return redirect()->route('organization.index')->with('success','Organization Updated Successfully');
            }else{
                return redirect()->route('organization.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('organization.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $organization  = Organization::find($request->id);
        $organization->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
