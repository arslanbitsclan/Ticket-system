<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Survey;
use App\Models\Evaluation;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SurveyRequest;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit-survey', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add-survey', ['only' => ['create', 'store']]);
        $this->middleware('permission:delete-survey', ['only' => ['destroy', 'delete_ajax']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('survey.index');
    }
    
    public function fetch_survey_ajax(Request $request)
    {
        $columns = array(
            0 => 'uid',
            1 => 'subject',
            2 => 'customer.first_name',
            3 => 'customer.phone',
            4 => 'assign.first_name',
            5 => 'call_type',
            6 => 'created_at',
            7 => 'id',
        );
        
        $query = Survey::join('users as customer', 'customer.id', 'survey.user_id')
            ->join('users as assign', 'assign.id', 'survey.assigned_to')
            ->selectRaw('survey.id, survey.uid as uid, survey.created_at, CONCAT(customer.first_name, " ",customer.last_name) AS customer_name, CONCAT(assign.first_name, " ",assign.last_name) AS assign_name, customer.phone as phone, survey.call_type as call_type, survey.subject')
            ->whereNull(['customer.deleted_at', 'assign.deleted_at', 'survey.deleted_at']);
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("survey.uid", "like", "%" . $search_value . "%")
                    ->orWhere(DB::raw('CONCAT(assign.first_name, " ",assign.last_name)'), "like", "%" . $search_value . "%")
                    ->orWhere("customer.phone", "like", "%" . $search_value . "%")
                    ->orWhere(DB::raw('CONCAT(customer.first_name, " ",customer.last_name)'), "like", "%" . $search_value . "%");
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
        
        foreach ($result as $survey) {
            $row = [
      
                view('survey.table-cols', ["survey" => $survey, "col" => "uid"])->render(),
                
                view('survey.table-cols', ["survey" => $survey, "col" => "subject"])->render(),
                
                view('survey.table-cols', ["survey" => $survey, "col" => "customer_name"])->render(),
                
                view('survey.table-cols', ["survey" => $survey, "col" => "phone"])->render(),
                
                view('survey.table-cols', ["survey" => $survey, "col" => "assign_name"])->render(),
                
                view('survey.table-cols', ["survey" => $survey, "col" => "call_type"])->render(),

                view('survey.table-cols', ["survey" => $survey, "col" => "created_at"])->render(),

                view('survey.table-cols', ["survey" => $survey, "col" => "actions"])->render(),

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
        $customers = User::role('customer')->whereNull('deleted_at')->get();
        $assigned_to = $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['customer']);
        })->whereNull('deleted_at')->get();
        $evaluations = Evaluation::whereNull('deleted_at')->get();
        
        return view('survey.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SurveyRequest $request)
    {
        $user = Auth()->user();
        $request->validated();
        try{

            $survey = Survey::create([
                'user_id' => $request->user_id,
                'assigned_to' => $request->assigned_to,
                'call_type' => $request->call_type,
                'subject' => $request->subject,
                'created_by' => $user['id'] 
            ]);
            
            if($survey){
                $survey->uid = floor(rand(500,999)*10000) + $survey->id;
                $survey->save();
                
                foreach($request->answer as $evaluation => $answers){
                    foreach($answers as $k => $answer){
                        SurveyAnswer::create([
                            "survey_uid" => $survey->uid,
                            "evaluation_question_id" => $k,
                            "answer" => $answer,
                            "score" => $request->score[$evaluation][$k] 
                        ]); 
                    }      
                }
                // add event when survey created
                
                return redirect()->route('survey.index')->with('success','Survey has been added successfully');
            }else{
                return redirect()->route('survey.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            return redirect()->route('survey.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Survey $survey)
    {
        //dd($survey->answers[0]->evaluationQuestion->evaluation);
        $customers = User::role('customer')->whereNull('deleted_at')->get();
        $assigned_to = $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['customer']);
        })->whereNull('deleted_at')->get();
        $evaluations = Evaluation::whereNull('deleted_at')->get();
        return view('survey.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey)
    {
        $request->validate([
            'user_id' => 'required',
            'assigned_to' => 'required',
            "call_type" => "required",
            "subject" => "required",
            "answer" => "required",
            "score" => "required"
        ]);
            
        try{
            $survey->user_id = $request->user_id;
            $survey->assigned_to = $request->assigned_to;
            $survey->call_type = $request->call_type;
            $survey->subject = $request->subject;
            if( $survey->update() ){
                SurveyAnswer::where('survey_uid', $survey->uid)->delete();
                foreach($request->answer as $evaluation => $answers){
                    foreach($answers as $k => $answer){
                        SurveyAnswer::create([
                            "survey_uid" => $survey->uid,
                            "evaluation_question_id" => $k,
                            "answer" => $answer,
                            "score" => $request->score[$evaluation][$k] 
                        ]); 
                    }      
                }
                
                
                return redirect()->route('survey.index')->with('success','Survey Updated Successfully');
            }else{
                return redirect()->route('survey.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('survey.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $survey  = Survey::find($request->id);
        SurveyAnswer::where('survey_uid', $survey->uid)->delete();
        $survey->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
