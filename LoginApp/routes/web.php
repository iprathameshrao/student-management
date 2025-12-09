<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\resourceController;
use App\Http\Controllers\EmailController;

use App\Http\Controllers\StudentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('createUser', [UserController::class, 'create'])->name('create.user');
Route::post('createUser', [UserController::class, 'store'])->name('create.user.store');
  

Route::get('login', [AuthController::class,'loginForm'])->name('login.page');
Route::post('login', [AuthController::class,'loginCheck'])->name('login.check');

Route::get('teacher/dashboard',[DashboardController::class,'teacher'])->name('teacher.dashboard')->middleware('auth');
Route::get('admin/dashboard',[DashboardController::class,'admin'])->name('admin.dashboard')->middleware('auth');
Route::get('student/dashboard',[DashboardController::class,'student'])->name('student.dashboard')->middleware('auth');



Route::post('/logout',[AuthController::class,'logout'])->name('logout');



//Route::resource('students', resourceController::class)->middleware('auth');

Route::get('students',[resourceController::class,'index'])->name('students.index')->middleware('auth');
Route::post('students',[resourceController::class,'store'])->name('students.store')->middleware('auth');

Route::get('students/create',[resourceController::class,'create'])->name('students.create')->middleware('auth');

// it will show single student details
//Route::get('students/{student}',[resourceController::class,'show'])->name('students.show')->middleware('auth');




Route::get('/students/edit',[resourceController::class,'editForm'])->name('students.edit')->middleware('auth');
// it will show single student details with protecttion.
Route::get('/students/view',[resourceController::class,'show'])->name('students.view')->middleware('auth');



// 
//Route::post('students/{student}/edit',[resourceController::class,'edit'])->name('students.edit') ->middleware(['signed', 'auth']);

Route::patch('students/{student}',[resourceController::class,'update'])->name('students.update')->middleware('auth');

Route::delete('students/{student}',[resourceController::class,'destroy'])->name('students.destroy')->middleware('auth');
Route::post('students/deleteAll',[resourceController::class,'deleteAll'])->name('students.deleteAll')->middleware('auth');


Route::post('/students/deleteAll', [resourceController::class, 'deleteAll'])->name('students.deleteAll');

// Temporary signed POST route for direct delete via URL (checks signature & requires auth)
Route::post('students/{id}/delete-link', [resourceController::class, 'deleteViaLink'])
    ->name('students.delete.link')
    ->middleware('auth');

Route::get('check-session', function () {
    
    return session()->all();
});

Route::get('set-session',function(Request $request){
    session()->flash('status','login successful');
    session(['name'=>'Siddharth Rao']);
    session(['id'=>'16']);
    
    // $request->session()->put(key: 'name',value: 'prathamesh rao');
    //     $request->session()->put(key:'id',value:'123');
    return redirect('check-session');
});
Route::get('destroy-session',function(Request $request){
    //$request->session()->forget('teacher_name');
     $request->session()->flush();
});

Route::view('sendMail','mailForm');
Route::post('send-mail',[EmailController::class,'sendEmail']);

// Route::get('hello',function(){
//     return view('about');
// })->middleware(['agecheck','countrycheck']);

//  Route::view('hello','about')->middleware('custom_group1');


 Route::middleware(['custom_group1'])->group(function () {
 Route::view('hello','about');
 Route::view('about','about');
});

Route::get('/teacher/all',[UserController::class,'index'])->name('teachers.all');
Route::get('/students/all',[StudentController::class,'index'])->name('students.all');