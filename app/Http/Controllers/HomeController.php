<?php

namespace App\Http\Controllers;

use App\Announcements;
use App\Department;
use App\Attendance;
use App\Document;
use App\holiday;
use App\Leave;
use App\Overtime;
use App\User;
use App\Item;
use App\Schedule;
use App\Icon;
use App\Award;
use DateTime;
use App\Salary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Arr;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //all announcement
        $ann = Announcements::where('emp_id', null)->where('department', null)->where('position', null)->orderBy('id','desc')->limit(2)->get();

        //private announcement
        $ann1 = Announcements::where('emp_id', Auth::user()->id)->where('department', null)->where('position', null)->orderBy('id','desc')->limit(2)->get();

        //department announcement
        $ann2 = Announcements::where('emp_id', null)->where('department', Auth::user()->department)->where('position', null)->orderBy('id','desc')->limit(2)->get();

        //position announcement
        $ann3 = Announcements::where('emp_id', null)->where('department', null)->where('position', Auth::user()->position)->orderBy('id','desc')->limit(2)->get();

        //All announcements
        $all_ann = Announcements::where('emp_id', null)->where('department', null)->where('position', null)->orderBy('id','desc')->get();
        $all_pvt = Announcements::where('emp_id', Auth::user()->id)->where('department', null)->where('position', null)->orderBy('id','desc')->get();
        $all_dept = Announcements::where('emp_id', null)->where('department', Auth::user()->department)->where('position', null)->orderBy('id','desc')->get();
        $all_pos = Announcements::where('emp_id', null)->where('department', null)->where('position', Auth::user()->position)->orderBy('id','desc')->get();

        $date_now = date('Y-m-d'); //-> date now
        $date_after_a_month = date('Y-m-d', strtotime(Carbon::now()->addMonths(1))); // date now + 1 month


        //UPCOMING WORKVERSARY
        $workversary = User::whereBetween('workversary', [$date_now, $date_after_a_month])->get();
        $upcoming_w = [];
        // foreach ($workversary as $key => $value){
        //     $upcoming_w[$key]['id'] = $value->id;
        //     $upcoming_w[$key]['name'] = $value->fname . ' ' . $value->lname;
        //     $days_left = Carbon::parse($value->workversary)->diff($date_now)->format("%a");
        //     $upcoming_w[$key]['days_left'] = $days_left == 0 ? 'Today!' : $days_left . ' days left.';
        //     $upcoming_w[$key]['workversary'] = $value->workversary;
        // }
        $upcoming_w = collect($workversary)->map(function ($value, $key) {
            return [ 'id' => $value->id, 
            'name' => $value->fname . ' ' . $value->lname, 
            'days_left' => Carbon::parse($value->workversary)->diff(now())->format("%a"), 
            'workversary' => $value->workversary, ]; 
        })
        ->sortBy('days_left')->map(function ($value) {
                $value['days_left'] = $value['days_left'] == 0 ? 'Today!' : $value['days_left']; 
                return $value; 
        });
        //END UPCOMING WORKVERSARY


        //UPCOMIGN BIRTHDAY
        $birthday = User::whereMonth('birth_date', Carbon::now()->month)->where('active','1')->get();
        $upcoming_b = [];
        foreach ($birthday as $key => $value){
            $day = Carbon::parse($value->birth_date);
            if(Carbon::now()->day <= $day->day){
                $upcoming_b[$key]['id'] = $value->id;
                $upcoming_b[$key]['name'] = $value->fname . ' ' . $value->lname;
                $day_of_birth = $day->day;
                $today = Carbon::now()->day;
                $days_left = (int)$day_of_birth - (int)$today;
                $upcoming_b[$key]['days_left'] = $days_left;
                $upcoming_b[$key]['birth_date'] = $value->birth_date;
            }
        }

        $upcoming_b = array_values(Arr::sort($upcoming_b, function ($value) {
            return $value['days_left'];
        }));       

        // For leaves function
        $date_hired = \Auth::user()->date_hired;
        $date_now = date('Y-m-d'); //-> date now
        $diffYears = \Carbon\Carbon::now()->diffInYears($date_hired);
        $leave = Leave::where('emp_id',\Auth::user()->id)->where('status', '!=', 'DELETED')->orderBy('id', 'desc')->paginate(5);

        if ($diffYears > 0){
            $x = Leave::where('emp_id',\Auth::user()->id)->orderBy('id', 'desc')->first();
            if ($x == null){
                $remaining_sick = $diffYears + 2;
                $remaining_vac = $diffYears + 2;
            }else{
                $remaining_sick = $x->sick_leaves_left;
                $remaining_vac = $x->vacation_leaves_left;
            }
        }else{
            $remaining_sick = 0;
            $remaining_vac = 0;
        }

        $rfi = Award::where('emp_id', Auth::user()->id)->where('type','rfi')->get();
        $commend = Award::where('emp_id', Auth::user()->id)->where('type','commend')->get();
        $salary = Salary::where('emp_id', Auth::user()->id)->orderBy('created_at','desc')->get();



        $ot = Overtime::where('emp_id', Auth::user()->id)->paginate(5);
        $docu = Document::where('emp_id', Auth::user()->id)->first();
        return view('home', compact('ann', 'ot', 'ann1','ann2', 'ann3','all_ann','all_pvt','all_dept','all_pos',
                                     'upcoming_b', 'upcoming_w', 'remaining_sick','remaining_vac', 'diffYears','leave','docu','commend','rfi','salary'));
    }

    public function attendtoday()
    {

    }


    public function dashboard()
    {
        $users = User::where('active','1')->orderBy('date_hired', 'asc')->paginate(100);
        $showDep = Department::distinct()->get('department_name');
        $showPos = Department::all();

        return view('admin.dashboard', compact('users', 'showDep', 'showPos'));
    }

    public function fetchdepartment()
    {
        $dep_id = Input::get('dep_id');
        $db = Department::all()->where('department_name', $dep_id);
        foreach($db as $row){
            echo '<option value="' .$row->position. '">'.$row->position.'</option>';
        }
    }


    public function homedashboard()
    {

        $users = User::all()->where('active','1');
        $department = Department::distinct()->get('department_name');
        $leave = Leave::where('status','PENDING')->get();
        $holidays = holiday::all();


        $date_now = date('Y-m-d'); //-> date now
        $date_after_a_month = date('Y-m-d', strtotime(Carbon::now()->addMonths(1))); // date now + 1 month


        //UPCOMING WORKVERSARY
        $workversary = User::whereBetween('workversary', [$date_now, $date_after_a_month])->where('active','1')->get();
        $upcoming_w = [];
        // foreach ($workversary as $key => $value){
        //     $upcoming_w[$key]['id'] = $value->id;
        //     $upcoming_w[$key]['name'] = $value->fname . ' ' . $value->lname;
        //     $days_left = Carbon::parse($value->workversary)->diff($date_now)->format("%a");
        //     $upcoming_w[$key]['days_left'] = $days_left == 0 ? 'Today!' : $days_left . ' days left.';
        //     $upcoming_w[$key]['workversary'] = $value->workversary;
        // }
        $upcoming_w = collect($workversary)->map(function ($value, $key) {
            return [ 'id' => $value->id, 
            'name' => $value->fname . ' ' . $value->lname, 
            'days_left' => Carbon::parse($value->workversary)->diff(now())->format("%a"), 
            'workversary' => $value->workversary, ]; 
        })
        ->sortBy('days_left')->map(function ($value) {
            return $value; 
        });
        //END UPCOMING WORKVERSARY


        //UPCOMIGN BIRTHDAY
        $birthday = User::whereMonth('birth_date', Carbon::now()->month)->where('active','1')->get();
        $upcoming_b = [];
        foreach ($birthday as $key => $value){
            $day = Carbon::parse($value->birth_date);
            if(Carbon::now()->day <= $day->day){
                $upcoming_b[$key]['id'] = $value->id;
                $upcoming_b[$key]['name'] = $value->fname . ' ' . $value->lname;
                $day_of_birth = $day->day;
                $today = Carbon::now()->day;
                $days_left = (int)$day_of_birth - (int)$today;
                $upcoming_b[$key]['days_left'] = $days_left;
                $upcoming_b[$key]['birth_date'] = $value->birth_date;
            }
        }

        $upcoming_b = array_values(Arr::sort($upcoming_b, function ($value) {
            return $value['days_left'];
        }));        

        


        $roster_today = DB::table('users')
                        ->join('schedules', 'users.id', '=', 'schedules.emp_id')
                        ->select('users.*', 'schedules.*')
                        ->where(date('l'), 'ON')
                        ->where('users.active','1')
                        ->limit(-1)
                        ->get();

        $roster_tomorrow = DB::table('users')
                        ->join('schedules', 'users.id', '=', 'schedules.emp_id')
                        ->select('users.*', 'schedules.*')
                        ->where(Carbon::tomorrow()->format('l'), 'ON')
                        ->where('users.active','1')
                        ->limit(-1)
                        ->get();


        $for_eval_this_month = [];

        $users_all = User::where('active', '1')->get();
        foreach ($users_all as $key => $value){
            $month_of_user = Carbon::parse($value->date_hired);
            $days_hired = Carbon::parse($value->date_hired)->diff(now())->format("%a");

            if($month_of_user->month == Carbon::now()->month || $month_of_user->month == Carbon::now()->addMonths(6)->month){
                // if($days_hired > 60){

                    $for_eval_this_month[$key]['emp_name'] = $value->fname . ' ' . $value->lname;
                    $for_eval_this_month[$key]['emp_id'] = $value->id;
                // }
            }

        }
        //get all evaluation for this month
        // dd($for_eval_this_month);

        return view('admin.homedashboard', compact('users' , 'department', 'leave', 'holidays','upcoming_w','upcoming_b','roster_today','roster_tomorrow', 'for_eval_this_month'));
    }




    public function settings()
    {
        $department = Department::distinct()->get('department_name');
        $position = Department::all();
        $holidays = holiday::all();
        $commend_icons = Icon::where('category', 'commendation')->get();
        $rfi_icons = Icon::where('category', 'rfi')->get();


        return view('admin.settings', compact('department', 'position' , 'holidays', 'commend_icons','rfi_icons'));
    }

    public function newHoliday(Request $request)
    {
        $name = $request->holiday_name;
        $timestamp = strtotime($request->holiday_date);
        $date = $request->holiday_date;
        $day = date('l', $timestamp);
        holiday::create([
            'holiday_name' => $name,
            'holiday_date' => $date,
            'holiday_day' => $day,
        ]);

        return back();
    }
}
