<?php

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

use App\Contact;
use App\Deduction;
use App\Document;
use App\Schedule;
use Carbon\Carbon;
use App\User;
use App\Veem;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Input\Input;
use App\Salary;

Route::get('/', function () {
    return view('welcome');
});



Route::post('/logout-podetize', function(Request $request){
    return $request;
})->name('logout-podetize');




Route::post('/employee/{id}/promote','PivotController@promote')->name('employee.promote');
Route::post('/employee/{id}/terminate','PivotController@terminate')->name('employee.terminate');
Route::post('/employee/{id}/edit-emp','PivotController@editEmp')->name('employee.editEmp');
Route::post('/employee/{id}/time','PivotController@time')->name('employee.time');
Route::post('/department/{department_name}/position','DepartmentController@newPosition')->name('department.position');
Route::post('/item/{itemid}/deduct', 'ItemController@deduct')->name('item.deduct');
Route::post('/item/{itemid}/add', 'ItemController@add')->name('item.add');
Route::post('/accept-leave', 'LeaveController@acceptleave')->name('leave.accept-leave');
Route::post('/depid', 'HomeController@fetchdepartment');
Route::resource('overtime', 'OvertimeController');
Route::post('/get-all-off', 'AttendanceController@getAllOff')->name('attendance.getalloff');
Route::post('/get-all-attendance', 'AttendanceController@getAllAttendance')->name('attendance.getallattendance');
Route::post('/get-all-off', 'AttendanceController@getAllOff')->name('attendance.getalloff');
Route::post('/get-all-ot', 'AttendanceController@getAllOt')->name('attendance.getallot');
Route::post('/parse-date-for-display', 'AttendanceController@parseDateForDisplay')->name('attendance.parseDateForDisplay');

Auth::routes();

Route::group(['middleware' => ['auth' , 'admin']], function () {

    Route::resource('contact', 'ContactController');
    Route::resource('veem', 'VeemController');
    Route::resource('employee', 'PivotController');
    Route::resource('item', 'ItemController');
    Route::resource('leave', 'LeaveController');
    Route::resource('attendance', 'AttendanceController');
    Route::resource('department', 'DepartmentController');
    Route::resource('deduction', 'DeductionController');
    Route::resource('announcements', 'AnnouncementsController');
    Route::resource('salary', 'SalaryController');
    Route::resource('schedule', 'ScheduleController');
    Route::resource('documents', 'DocumentController');
    Route::resource('documents', 'DocumentController');
    Route::resource('icon', 'IconController');
    Route::resource('award', 'AwardController');
    Route::POST('/setPosition', 'DepartmentController@setPosition')->name('setPosition');
    Route::POST('/delPosition', 'DepartmentController@delPosition')->name('delPosition');
    Route::POST('/editDeduction', 'DeductionController@editDeduction')->name('editDeduction');
    Route::POST('/newHoliday', 'HomeController@newHoliday')->name('newHoliday');
    Route::get('/deductions', 'DeductionController@index')->name('deductions');
    Route::get('/attendance', 'AttendanceController@index')->name('attendance');
    Route::get('/settings', 'HomeController@settings')->name('settings');
    Route::get('/leave', 'LeaveController@index')->name('leave');
    Route::get('/employee/{id}/accountability', 'PivotController@accountability')->name('employee.accountability');
    Route::get('/cash-advance', 'DeductionController@showCA')->name('ded.showCA');
    Route::get('/cash-advance/{id}', 'DeductionController@storeCA')->name('ded.storeCA');
    Route::post('/cash-advance-edit/{id}', 'DeductionController@editCA')->name('ded.editCA');
    Route::post('/cash-advance-delete/{id}', 'DeductionController@deleteCA')->name('ded.delCA');
    Route::get('/get-inventory-list', 'ItemController@getList');
    Route::get('/homedashboard', 'HomeController@homedashboard')->name('homedashboard');
    Route::get('/employees', 'HomeController@dashboard')->name('dashboard');
    Route::get('/payroll', 'PayrollController@index')->name('payroll');
    Route::POST('/setPosition', 'DepartmentController@setPosition')->name('setPosition');
    Route::POST('/delPosition', 'DepartmentController@delPosition')->name('delPosition');
    Route::POST('/setHoliday', 'PivotController@setHoliday')->name('setHoliday');
    Route::POST('/delHoliday', 'PivotController@delHoliday')->name('delHoliday');
    Route::POST('/editDeduction', 'DeductionController@editDeduction')->name('editDeduction');
    Route::post('/employee/{id}/promote','PivotController@promote')->name('employee.promote');
    Route::post('/employee/{id}/terminate','PivotController@terminate')->name('employee.terminate');
    Route::post('/employee/{id}/edit-emp','PivotController@editEmp')->name('employee.editEmp');
    Route::post('/employee/{id}/time','PivotController@time')->name('employee.time');
    Route::post('/department/{department_name}/position','DepartmentController@newPosition')->name('department.position');
    Route::post('/item/{itemid}/deduct', 'ItemController@deduct')->name('item.deduct');
    Route::post('/item/{itemid}/add', 'ItemController@add')->name('item.add');
    Route::post('/accept-leave', 'LeaveController@acceptleave')->name('leave.accept-leave');
    Route::post('/depid', 'HomeController@fetchdepartment');
    Route::post('/OTstatus/{id}', 'PivotController@OTstatus')->name('OT.status');
    Route::post('/seekdate', 'AttendanceController@seekdate')->name('attendance.seekdate');
    Route::post('/accept-ot', 'OvertimeController@accept')->name('overtime.accept');
    Route::post('/reject-ot', 'OvertimeController@reject')->name('overtime.reject');
    Route::post('/filter-ot', 'OvertimeController@filter')->name('overtime.filter');
    Route::post('/new-salary-record','SalaryController@customStore')->name('salary.customStore');
    Route::post('/check-time-off', 'ScheduleController@checkOff')->name('schedule.check');

    Route::post('/mark-absent-by-date', 'AttendanceController@markAbsentByDate')->name('attendance.markAbsentByDate');
    Route::post('/workversary-update', 'PivotController@workversaryUpdate')->name('workversarry.update');
    Route::post('/accept-leave', 'LeaveController@acceptleave')->name('leave.accept');
    Route::post('/deac-employee/{id}', 'PivotController@deacEmployee')->name('employee.deacEmployee');
    Route::post('/reac-employee/{id}', 'PivotController@reacEmployee')->name('employee.reacEmployee');
    Route::post('/count-leaves', 'LeaveController@countLeave');
    Route::get('/employee-archive', 'PivotController@employeeArchive')->name('employee.employeeArchive');
    Route::post('/filter-employee-list', 'PivotController@filterEmployeeList');
    Route::post('/search-by-name', 'PivotController@searchByName');
    Route::post('/alphabet-employee-list', 'PivotController@alphabetEmployeeList');
    Route::post('/filter-leave', 'LeaveController@filterLeave');
    Route::post('/greet-birthday', 'PivotController@employeeGreetBirthday')->name('employee.greet-birthday');
    Route::post('/leave-search-by-name', 'LeaveController@leaveSearchByName');
    Route::get('/awards-and-rfi', 'PivotController@awardsAndRfi')->name('zz');
    Route::get('/awards-rfi', 'AwardController@showrfi');
    
    Route::post('users/create-new', function (Request $request) {


        $request->validate([
            // 'username' => 'required|unique:users',
            'username' => 'required',
            'department' => 'required',
            'position' => 'required',
        ]);

        $workversary = Carbon::parse($request->hired)->addYear();
        $parsed_work = date('Y-m-d', strtotime($workversary));


        $user = User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'date_hired' => $request->hired,
            'workversary' => $parsed_work,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'weeks_of_training' => $request->weeks_of_training,
            'emp_status' => $request->employment_type == 'FULL' ? 'Full time' : 'Part time',
            'department' => $request->department,
            'position' => $request->position,
            'priority' => 'LO',
            'active' => '1',
            'referred_by' => $request->referred_by,
            'birth_date' => $request->birthday,
            'daily_rate' => $request->daily_rate,
            'bi_weekly_rate' => $request->bi_weekly_rate,
            'monthly_rate' => $request->monthly_rate,
        ]);

        Veem::create([
            'user_id' => $user->id,
            'veem_email' => $request->veem_email,
            'complete_bank_account_name' => $request->complete_bank_account_name,
            'veem_mobile_number' => $request->veem_mobile_number
        ]);

        Contact::create([
            'user_id' => $user->id,
            'email' => $request->email,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'emergency_contact_person' => $request->emergency_contact_person,
            'emergency_contact_number' => $request->emergency_contact_number
        ]);

        Salary::create([
            'emp_id' => $user->id,
            'daily_rate' => $request->daily_rate,
            'bi_weekly_rate' => $request->bi_weekly_rate,
            'monthly_rate' => $request->monthly_rate,
            'add_or_ded_daily' => '0',
            'add_or_ded_biweekly' => '0',
            'add_or_ded_monthly' => '0',
            'status' => 'INCREASE',
            'note' => 'First Salary'
        ]);

        Document::create([
            'emp_id' => $user->id
        ]);

        Schedule::create([
            'emp_id' => $user->id,
            'Monday' => 'ON',
            'Tuesday' => 'ON',
            'Wednesday' => 'ON',
            'Thursday' => 'ON',
            'Friday' => 'ON',
            'Saturday' => 'OFF',
            'Sunday' => 'OFF'
        ]);



        return redirect()->route('dashboard', $user->id)->with('success', 'SUCCESSFULLY ADDED NEW EMPLOYEE: '. $request->fname . ' ' . $request->lname);

    })->name('users.create');
    
});


Route::group(['middleware' => ['auth', 'user']], function () {

    Route::post('/employee/attendance', 'PivotController@employeeAttendance')->name('employee.attendance');
    Route::post('/user-update', 'PivotController@selfUpdate')->name('selfUpdate');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::post('/overtime/{id}','PivotController@fileOvertime')->name('employee.fileOvertime');
    Route::post('/file-overtime','PivotController@fileOvertime')->name('fileOvertime');
    Route::post('/profilePic', 'PivotController@profilePic')->name('profilePic');
    Route::post('/check-workversary', 'PivotController@checkWorkversary')->name('schedule.check');
    Route::post('/file-leave', 'PivotController@fileLeave')->name('leave.file');


});





