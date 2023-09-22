<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrioritiesController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\SurveyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Auth Routes
Route::get('login', [LoginController::class,'index'])->name("login");
Route::post('login', [LoginController::class,'login'])->name("login");
Route::get('logout', [LoginController::class,'logout'])->name("logout");


Route::get('/',[FrontController::class,'index'])->name('home');
Route::post('/create_ticket',[FrontController::class,'create_ticket'])->name('front.create_ticket');
Route::get('/open_ticket',[FrontController::class,'open_ticket'])->name('front.open_ticket');
Route::get('/knowledge-base',[FrontController::class,'knowledge_base'])->name('front.knowledge-base');
Route::get('/faq',[FrontController::class,'faq'])->name('front.faq');
Route::get('/privacy-policy',[FrontController::class,'privacy_policy'])->name('front.privacy-policy');
Route::get('/terms-of-service',[FrontController::class,'terms_of_service'])->name('front.terms-of-service');

Route::group(['middleware' => ['auth']], function () {
    
    Route::get('dashboard',[DashboardController::class,'index'])->name('dashboard');

    Route::resource('role', RoleController::class);
    Route::post('fetch_role',[RoleController::class,'fetch_role_ajax'])->name('role.fetch');
    Route::post('delete_role',[RoleController::class,'delete_ajax'])->name('role.delete-ajax');

    Route::resource('permission', PermissionController::class);
    Route::post('fetch_permission',[PermissionController::class,'fetch_permission_ajax'])->name('permission.fetch');
    Route::post('delete_permission',[PermissionController::class,'delete_ajax'])->name('permission.delete-ajax');

    Route::resource('user', UserController::class);
    Route::post('fetch_user',[UserController::class,'fetch_user_ajax'])->name('user.fetch');
    Route::post('delete_user',[UserController::class,'delete_ajax'])->name('user.delete-ajax');
    
    Route::resource('customer', CustomerController::class);
    Route::post('fetch_customer',[CustomerController::class,'fetch_customer_ajax'])->name('customer.fetch');
    Route::post('delete_customer',[CustomerController::class,'delete_ajax'])->name('customer.delete-ajax');

    Route::resource('organization', OrganizationController::class);
    Route::post('fetch_organization',[OrganizationController::class,'fetch_organization_ajax'])->name('organization.fetch');
    Route::post('delete_organization',[OrganizationController::class,'delete_ajax'])->name('organization.delete-ajax');
    
    Route::resource('contact', ContactController::class);
    Route::post('fetch_contact',[ContactController::class,'fetch_contact_ajax'])->name('contact.fetch');
    Route::post('delete_contact',[ContactController::class,'delete_ajax'])->name('contact.delete-ajax');
    
    Route::resource('category', CategoryController::class);
    Route::post('fetch_category',[CategoryController::class,'fetch_category_ajax'])->name('category.fetch');
    Route::post('delete_category',[CategoryController::class,'delete_ajax'])->name('category.delete-ajax');

    Route::resource('status', StatusController::class);
    Route::post('fetch_status',[StatusController::class,'fetch_status_ajax'])->name('status.fetch');
    Route::post('delete_status',[StatusController::class,'delete_ajax'])->name('status.delete-ajax');

    Route::resource('priority', PrioritiesController::class);
    Route::post('fetch_priority',[PrioritiesController::class,'fetch_priority_ajax'])->name('priority.fetch');
    Route::post('delete_priority',[PrioritiesController::class,'delete_ajax'])->name('priority.delete-ajax');
    
    Route::resource('department', DepartmentController::class);
    Route::post('fetch_department',[DepartmentController::class,'fetch_department_ajax'])->name('department.fetch');
    Route::post('delete_department',[DepartmentController::class,'delete_ajax'])->name('department.delete-ajax');

    Route::resource('evaluation', EvaluationController::class);
    Route::post('fetch_evaluation',[EvaluationController::class,'fetch_evaluation_ajax'])->name('evaluation.fetch');
    Route::post('delete_evaluation',[EvaluationController::class,'delete_ajax'])->name('evaluation.delete-ajax');

    Route::resource('type', TypeController::class);
    Route::post('fetch_type',[TypeController::class,'fetch_type_ajax'])->name('type.fetch');
    Route::post('delete_type',[TypeController::class,'delete_ajax'])->name('type.delete-ajax');

    Route::resource('ticket', TicketController::class);
    Route::post('fetch_ticket',[TicketController::class,'fetch_ticket_ajax'])->name('ticket.fetch');
    Route::post('delete_ticket',[TicketController::class,'delete_ajax'])->name('ticket.delete-ajax');
    Route::post('remove_attachment_ticket',[TicketController::class,'remove_attachment'])->name('ticket.delete-attachment');
    
    Route::resource('survey', SurveyController::class);
    Route::post('fetch_survey',[SurveyController::class,'fetch_survey_ajax'])->name('survey.fetch');
    Route::post('delete_survey',[SurveyController::class,'delete_ajax'])->name('survey.delete-ajax');
});

