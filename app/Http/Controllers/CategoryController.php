<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.index');
    }
    
    public function fetch_category_ajax(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'created_at'
        );
        
        $query = Category::whereNull('deleted_at');
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
        
        foreach ($result as $category) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('category.table-cols', ["category" => $category, "col" => "name"])->render(),
                
                view('category.table-cols', ["category" => $category, "col" => "created_at"])->render(),

                view('category.table-cols', ["category" => $category, "col" => "actions"])->render(),

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
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $request->validated();
        try{
            $contact = Category::create([
                'name' => $request->name,
            ]);
            
            if($contact){
                return redirect()->route('category.index')->with('success','Category has been added successfully');
            }else{
                return redirect()->route('category.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            dd($e);
            return redirect()->route('category.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Category $category)
    {

        return view('category.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|min:2|max:255' 
        ]);
            
        try{
            $category->name = $request->name;
            if( $category->update() ){
                return redirect()->route('category.index')->with('success','Category Updated Successfully');
            }else{
                return redirect()->route('category.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('category.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $category  = Category::find($request->id);
        $category->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
