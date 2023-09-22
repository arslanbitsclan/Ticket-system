<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\StatusRequest;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Support\Str;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('status.index');
    }
    
    public function fetch_status_ajax(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'slug',
            3 => 'created_at'
        );
        
        $query = Status::whereNull('deleted_at');
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("name", "like", "%" . $search_value . "%")
                    ->orWhere("slug", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $status) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('status.table-cols', ["status" => $status, "col" => "name"])->render(),

                view('status.table-cols', ["status" => $status, "col" => "slug"])->render(),
                
                view('status.table-cols', ["status" => $status, "col" => "created_at"])->render(),

                view('status.table-cols', ["status" => $status, "col" => "actions"])->render(),

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
        // $organizations = Organization::all();
        // $countries = Country::all();
        // $departments = Department::all(); 
        return view('status.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatusRequest $request)
    {
        $request->validated();
        try{



            $status = Status::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
            
            if($status){
                return redirect()->route('status.index')->with('success','Status has been added successfully');
            }else{
                return redirect()->route('status.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            dd($e);
            return redirect()->route('status.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Status $status)
    {

        return view('status.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        $request->validate([
            'name' => 'required|min:2|max:255' 
        ]);
            
        try{
            $status->name = $request->name;
            $status->slug = Str::slug($request->name);
            if( $status->update() ){
                return redirect()->route('status.index')->with('success','Status Updated Successfully');
            }else{
                return redirect()->route('status.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('status.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $status  = Status::find($request->id);
        $status->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
