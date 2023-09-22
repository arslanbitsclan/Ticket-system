<?php

namespace App\Http\Controllers;

use App\Events\AssignedUser;
use Exception;
use App\Models\Type;
use App\Models\User;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Attachment;
use App\Models\Department;
use App\Events\TicketCreate;
use App\Events\TicketUpdate;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\File;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit-ticket', ['only' => ['edit', 'update']]);
        $this->middleware('permission:add-ticket', ['only' => ['create', 'store']]);
        $this->middleware('permission:delete-ticket', ['only' => ['destroy', 'delete_ajax']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ticket.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function fetch_ticket_ajax(Request $request)
    {
        $columns = array(
            0 => 'uid',
            1 => 'subject',
            2 => 'users.first_name',
            3 => 'priorities.name',
            4 => 'status.name',
            5 => 'created_at',
            6 => 'id',
        );
        
        $query = Ticket::join('users', 'users.id', 'tickets.user_id')
            ->join('priorities', 'priorities.id', 'tickets.priority_id')
            ->join('status', 'status.id', 'tickets.status_id')
            ->selectRaw('tickets.id, tickets.uid as uid, tickets.created_at, CONCAT(users.first_name, " ",users.last_name) AS user_name, priorities.name as priority_name, status.name as status_name, tickets.subject')
            ->whereNull(['users.deleted_at', 'priorities.deleted_at', 'status.deleted_at']);
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("tickets.uid", "like", "%" . $search_value . "%")
                    ->orWhere("users.first_name", "like", "%" . $search_value . "%")
                    ->orWhere("users.last_name", "like", "%" . $search_value . "%")
                    ->orWhere("priorities.name", "like", "%" . $search_value . "%")
                    ->orWhere("status.name", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $ticket) {
            $row = [
      
                view('ticket.table-cols', ["ticket" => $ticket, "col" => "uid"])->render(),
                
                view('ticket.table-cols', ["ticket" => $ticket, "col" => "subject"])->render(),
                
                view('ticket.table-cols', ["ticket" => $ticket, "col" => "user_name"])->render(),
                
                view('ticket.table-cols', ["ticket" => $ticket, "col" => "priority_name"])->render(),
                
                view('ticket.table-cols', ["ticket" => $ticket, "col" => "status_name"])->render(),

                view('ticket.table-cols', ["ticket" => $ticket, "col" => "created_at"])->render(),

                view('ticket.table-cols', ["ticket" => $ticket, "col" => "actions"])->render(),

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
    
    public function create()
    {
        $customers = User::role('customer')->whereNull('deleted_at')->get();
        $priorities = Priority::whereNull('deleted_at')->get();
        $status = Status::whereNull('deleted_at')->get();
        $departments = Department::whereNull('deleted_at')->get();
        $assigned_to = $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['customer']);
        })->whereNull('deleted_at')->get();
        $types = Type::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        
        return view('ticket.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        $user = Auth()->user();
        $request->validated();
        try{
            
            $ticket = Ticket::create([
                'user_id' => $request->user_id,
                'priority_id' => $request->priority_id,
                'status_id' => $request->status_id,
                'department_id' => $request->department_id,
                'assigned_to' => $request->assigned_to,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'subject' => $request->subject,
                'details' => $request->details,
                'created_by' => $user['id'] 
            ]);
            
            if($ticket){
                if($request->hasFile('files')){
                    $files = $request->file('files');
                    foreach($files as $file){
                        $file_path = $file->store('tickets', ['disk' => 'file_uploads']);
                        Attachment::create(['ticket_id' => $ticket->id, 'name' => $file->getClientOriginalName(), 'size' => $file->getSize(), 'path' => $file_path]);
                    }
                }
                
                $ticket->uid = floor(rand(500,999)*10000) + $ticket->id;
                $ticket->save();
                
                // add event when ticket created
                event(new TicketCreate($ticket));
                
                // add event when ticket is assigned
                if(!empty($ticket->assigned_to)){
                    event(new AssignedUser($ticket));
                }
                
                return redirect()->route('ticket.index')->with('success','Ticket has been added successfully');
            }else{
                return redirect()->route('ticket.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            return redirect()->route('ticket.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Ticket $ticket)
    {
        $customers = User::role('customer')->whereNull('deleted_at')->get();
        $priorities = Priority::whereNull('deleted_at')->get();
        $status = Status::whereNull('deleted_at')->get();
        $departments = Department::whereNull('deleted_at')->get();
        $assigned_to = $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['customer']);
        })->whereNull('deleted_at')->get();
        $types = Type::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        
        return view('ticket.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {      
        try{
            
            $closed_status = Status::where('slug', 'like', '%close%')->first();

            $update_message = null;
            if($closed_status && ($ticket->status_id != $closed_status->id) && $request->status_id == $closed_status->id){
                $update_message = 'The ticket has been closed.';
            }elseif($ticket->status_id != $request->status_id){
                $update_message = 'The status has been changed for this ticket.';
            }
            if($ticket->priority_id != $request->priority_id){
                $update_message = 'The priority has been changed for this ticket.';
            }
            
            $assigned = (!empty($request->assigned_to) && ($ticket->assigned_to != $request->assigned_to))??false;
        
            $ticket->user_id = $request->user_id;
            $ticket->priority_id = $request->priority_id;
            $ticket->status_id = $request->status_id;
            $ticket->department_id = $request->department_id;
            $ticket->assigned_to =$request->assigned_to;
            $ticket->category_id = $request->category_id;
            $ticket->type_id = $request->type_id;
            $ticket->subject = $request->subject;
            $ticket->details = $request->details;
            if( $ticket->update() ){
                
                if(!empty($update_message)){
                    event(new TicketUpdate($ticket,$update_message));
                }
                
                if($assigned){
                    event(new AssignedUser($ticket));
                }
                
        
                if($request->hasFile('files')){
                    $files = $request->file('files');
                    foreach($files as $file){
                        $file_path = $file->store('tickets', ['disk' => 'file_uploads']);
                        Attachment::create(['ticket_id' => $ticket->id, 'name' => $file->getClientOriginalName(), 'size' => $file->getSize(), 'path' => $file_path]);
                    }
                }
                return redirect()->route('ticket.index')->with('success','Ticket Updated Successfully');
            }else{
                return redirect()->route('ticket.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            dd($e);
            return redirect()->route('ticket.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $ticket  = Ticket::find($request->id);
        $ticket->delete();
        return response()->json([
            'success' => true
        ]);
    }
    
    public function remove_attachment(Request $request){
        $attachment  = Attachment::findOrFail($request->id);
        File::delete('files/'.$attachment->path);
        $attachment->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
