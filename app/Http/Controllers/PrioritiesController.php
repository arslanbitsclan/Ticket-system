<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\PriorityRequest;
use App\Models\Category;
use App\Models\Priority;

class PrioritiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('priority.index');
    }
    
    public function fetch_priority_ajax(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at'
        );
        
        $query = Priority::whereNull('deleted_at');
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("name", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $priority) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('priority.table-cols', ["priority" => $priority, "col" => "name"])->render(),
                
                view('priority.table-cols', ["priority" => $priority, "col" => "created_at"])->render(),

                view('priority.table-cols', ["priority" => $priority, "col" => "actions"])->render(),

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
        return view('priority.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PriorityRequest $request)
    {
        $request->validated();
        try{
            $priority = Priority::create([
                'name' => $request->name,
            ]);
            
            if($priority){
                return redirect()->route('priority.index')->with('success','Priority has been added successfully');
            }else{
                return redirect()->route('priority.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            dd($e);
            return redirect()->route('priority.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Priority $priority)
    {

        return view('priority.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Priority $priority)
    {
        $request->validate([
            'name' => 'required|min:2|max:255' 
        ]);
            
        try{
            $priority->name = $request->name;
            if( $priority->update() ){
                return redirect()->route('priority.index')->with('success','Priority Updated Successfully');
            }else{
                return redirect()->route('priority.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('priority.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $priority  = Priority::find($request->id);
        $priority->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
