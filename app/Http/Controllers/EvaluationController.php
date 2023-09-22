<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Country;
use App\Models\Evaluation;
use Illuminate\Support\Str;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\EvaluationQuestion;
use App\Http\Requests\EvaluationRequest;
use App\Http\Requests\OrganizationRequest;

class EvaluationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('evaluation.index');
    }
    
    public function fetch_evaluation_ajax(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'type',
            4 => 'created_at',
            6 => 'id'
        );
        
        $query = Evaluation::whereNull('deleted_at');
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("type", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $evaluation) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('evaluation.table-cols', ["evaluation" => $evaluation, "col" => "type"])->render(),
                
                view('evaluation.table-cols', ["evaluation" => $evaluation, "col" => "created_at"])->render(),

                view('evaluation.table-cols', ["evaluation" => $evaluation, "col" => "actions"])->render(),

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
        return view('evaluation.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluationRequest $request)
    {
        $request->validated();
        try{
            $evaluation = Evaluation::create([
                'type' => $request->type,
            ]);
            
            if($evaluation){
                foreach($request->question as $question){
                    EvaluationQuestion::create([
                        'evaluation_id' => $evaluation->id,
                        'question' => $question,
                    ]);
                }
                return redirect()->route('evaluation.index')->with('success','Evaluation has been added successfully');
            }else{
                return redirect()->route('evaluation.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            return redirect()->route('evaluation.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Evaluation $evaluation)
    {
        return view('evaluation.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'type' => 'required|min:2|max:255',
            'question' => 'required'
        ]);
            
        try{
            $evaluation->type = $request->type;
            if( $evaluation->update() ){
                EvaluationQuestion::where('evaluation_id', $evaluation->id)->delete();
                foreach($request->question as $question){
                    EvaluationQuestion::create([
                        'evaluation_id' => $evaluation->id,
                        'question' => $question,
                    ]);
                }
                
                
                return redirect()->route('evaluation.index')->with('success','Evaluation Updated Successfully');
            }else{
                return redirect()->route('evaluation.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('evaluation.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $evaluation  = Evaluation::find($request->id);
        $evaluation->delete();
        EvaluationQuestion::where('evaluation_id', $request->id)->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
