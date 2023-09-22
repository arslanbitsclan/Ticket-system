<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Department;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contact.index');
    }
    
    public function fetch_contact_ajax(Request $request)
    {
        $columns = array(
            0 => 'id',
            1 => 'first_name',
            2 => 'last_name',
            3 => 'organization_id',
            4 => 'email',
            5 => 'phone',
            6 => 'created_at',
            7 => 'id'
        );
        
        $query = Contact::join('organizations', 'organizations.id', 'contacts.organization_id')
            ->selectRaw('organizations.name as organization_name, contacts.first_name, contacts.last_name, contacts.email, contacts.id, contacts.phone, contacts.created_at')
            ->whereNull(['contacts.deleted_at', 'organizations.deleted_at']);
        $totalData = $query->count();
        $totalFiltered = $totalData;

        if (!empty($request['search']['value'])) {
            $search_value = $request['search']['value'];
            $query = $query->where(function ($q) use ($search_value) {
                $q->where("contacts.first_name", "like", "%" . $search_value . "%")
                    ->orWhere("contacts.last_name", "like", "%" . $search_value . "%")
                    ->orWhere("contacts.phone", "like", "%" . $search_value . "%")
                    ->orWhere("organizations.name", "like", "%" . $search_value . "%")
                    ->orWhere("contacts.email", "like", "%" . $search_value . "%");
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
        
        foreach ($result as $contact) {
            $row = [
                "<div class='text-start'> " . (($request['order'][0]['dir'] == 'desc' && $request['order'][0]['column'] == 0) ? $sr_no-- : ++$sr_no) . " </div>", //If sortingl is ascending then increment, if sorting is descinding then decrement

                view('contact.table-cols', ["contact" => $contact, "col" => "first_name"])->render(),
                
                view('contact.table-cols', ["contact" => $contact, "col" => "last_name"])->render(),
                
                view('contact.table-cols', ["contact" => $contact, "col" => "organization_id"])->render(),
                
                view('contact.table-cols', ["contact" => $contact, "col" => "email"])->render(),
                
                view('contact.table-cols', ["contact" => $contact, "col" => "phone"])->render(),
                
                view('contact.table-cols', ["contact" => $contact, "col" => "created_at"])->render(),

                view('contact.table-cols', ["contact" => $contact, "col" => "actions"])->render(),

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
        $organizations = Organization::all();
        $countries = Country::all();
        $departments = Department::all(); 
        return view('contact.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        $request->validated();
        try{
            $contact = Contact::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' =>  $request->email,
                'phone' =>  $request->phone,
                'address' =>  $request->address,
                'city' =>  $request->city,
                'country' =>  $request->country,
                'organization_id' =>  $request->organization_id
            ]);
            
            if($contact){
                return redirect()->route('contact.index')->with('success','Contact has been added successfully');
            }else{
                return redirect()->route('contact.index')->with('warning','Please try again!!');
            } 
        }
        catch (Exception $e){
            dd($e);
            return redirect()->route('contact.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
    public function edit(Contact $contact)
    {
        $organizations = Organization::all();
        $countries = Country::all();
        $departments = Department::all();
        return view('contact.edit',get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'first_name' => 'required|min:2|max:255',
            'last_name' => 'required|min:2|max:255',
            'email' => 'required|max:255',
            'phone' => 'required|min:7|max:12',
            'city' => 'required',
            'address' => 'required',
            'country' => 'required',
            'organization_id' => 'required'  
        ]);
            
        try{
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->address = $request->address;
            $contact->city = $request->city;
            $contact->country = $request->country;
            $contact->organization_id = $request->organization_id;
            if( $contact->update() ){
                return redirect()->route('contact.index')->with('success','Contact Updated Successfully');
            }else{
                return redirect()->route('contact.index')->with('warning','There was some problem updating User');
            }
        }
        catch (Exception $e){
            return redirect()->route('contact.index')->with('warning', 'Something Went Wrong! Please Try Again Later');
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
        $contact  = Contact::find($request->id);
        $contact->delete();
        return response()->json([
            'success' => true
        ]);
    }
}
