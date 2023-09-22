<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Type;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\Attachment;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Events\CustomerCreatedFromDashboard;

class FrontController extends Controller
{
    public function index(){
        $dashboard = false;
        if (Auth::check()) {
            $dashboard = true;
        }
        $departments = Department::whereNull('deleted_at')->get();
        $types = Type::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        return view('home',get_defined_vars());
    }
    
    public function knowledge_base(){
        $dashboard = false;
        if (Auth::check()) {
            $dashboard = true;
        }
        return view('knowledge-base',get_defined_vars());
    }
    
    public function faq(){
        $dashboard = false;
        if (Auth::check()) {
            $dashboard = true;
        }
        return view('faq',get_defined_vars());
    }
    
    public function privacy_policy(){
        $dashboard = false;
        if (Auth::check()) {
            $dashboard = true;
        }
        return view('privacy-policy',get_defined_vars());
    }
    
    public function terms_of_service(){
        $dashboard = false;
        if (Auth::check()) {
            $dashboard = true;
        }
        return view('terms-of-service',get_defined_vars());
    }
    
    public function open_ticket(){
        $dashboard = false;
        if (Auth::check()) {
            $dashboard = true;
        }
        $departments = Department::whereNull('deleted_at')->get();
        $types = Type::whereNull('deleted_at')->get();
        $categories = Category::whereNull('deleted_at')->get();
        return view('open_ticket',get_defined_vars());
    }
    
    public function create_ticket(Request $request){
        
        $request->validate([
            'first_name' => ['required', 'max:40'],
            'last_name' => ['required', 'max:40'],
            'phone' => ['required'],
            'subject' => ['required'],
            'department_id' => ['required', Rule::exists('departments', 'id')],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'type_id' => ['required', Rule::exists('types', 'id')],
            'email' => ['required', 'max:60', 'email'],
            'details' => ['required'],
        ]);
        
        try
        {
            $user = User::where('email', $request->email)->first();
            if(empty($user)){
                $plain_password = $this->genRendomPassword();
                $customerRole = Role::where('name', 'customer')->first();
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' =>  $request->phone,
                    'email' =>  $request->email,
                    'password' =>  Hash::needsRehash($plain_password) ? Hash::make($plain_password) : $plain_password
                ]);
                if($user){
                    $user->assignRole( $customerRole->name );
                }
            }
            
            $ticket = Ticket::create([
                'subject' => $request->subject,
                'details' => $request->details,
                'department_id' => $request->department_id,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'user_id' => $user->id,
            ]);
            $ticket->uid = floor(rand(500,999)*10000) + $ticket->id;
            $ticket->save();
            
            if($request->hasFile('files')){
                $files = $request->file('files');
                foreach($files as $file){
                    $file_path = $file->store('tickets', ['disk' => 'file_uploads']);
                    Attachment::create(['ticket_id' => $ticket->id, 'name' => $file->getClientOriginalName(), 'size' => $file->getSize(), 'path' => $file_path]);
                }
            }
            event(new CustomerCreatedFromDashboard($ticket,$plain_password));
            return redirect()->route('home')->with('success', 'Ticket has been Created Successfully');
            
        }
        catch (Exception $e)
        {
            return redirect()->route('home')->with('warning', 'Something Went Wrong! Please Try Again Later');
        }
    }

    
    private function genRendomPassword() {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 13; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
