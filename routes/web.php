<?php

use App\Livewire\AdminDashboard;
use App\Livewire\Dashboard;
use App\Livewire\HrLogin;
use App\Livewire\HomeDashboard;
use App\Livewire\AddEmployeeDetails;
use App\Livewire\AnalyticsHub;
use App\Livewire\AnalyticsHubViewAll;
use App\Livewire\EmployeeAsset;
use App\Livewire\GrantLeaveBalance;
use App\Livewire\UpdateEmployeeDetails;
use App\Livewire\EmployeeDirectory;
use App\Livewire\EmployeeLeave;
use App\Livewire\EmployeeProfile;
use App\Livewire\Feeds;
use App\Livewire\HrAttendanceInfo;
use App\Livewire\HrAttendanceOverviewNew;
use App\Livewire\HrMainOverview;
use App\Livewire\ParentDetails;
use App\Livewire\PositionHistory;
use App\Livewire\WhoIsInChartHr;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware(['checkauth'])->group(function () {
    Route::get('/hrlogin', HrLogin::class)->name('hrlogin');
});

Route::middleware(['auth:hr'])->group(function () {
    // Root route, protected by auth:hr middleware
    // Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/', function () {
        return redirect('/hr/dashboard');
    });
    Route::get('/hr/dashboard', HomeDashboard::class)->name('admin-home');
    // Group routes under the 'hr' prefix
    Route::prefix('hr')->group(function () {
        //like this  Route: /hr/hello
                Route::get('/hello', Dashboard::class)->name('hello');
                Route::get('/add-employee-details/{employee?}', AddEmployeeDetails::class)->name('add-employee-details');
                Route::get('/update-employee-details', UpdateEmployeeDetails::class)->name('update-employee-details');
                Route::get('/update-employee-leavesa', GrantLeaveBalance::class)->name('update-employee-leaves');
        //HR Employee Related Routes
               Route::get('/hrFeeds', Feeds::class)->name('hrfeeds');
               Route::get('/information', EmployeeProfile::class)->name('employee-profile');
               Route::get('/asset', EmployeeAsset::class)->name('employee-asset');
               Route::get('/history', PositionHistory::class)->name('position-history');
               Route::get('/parent', ParentDetails::class)->name('parent-details');


                Route::get('/user/hremployeedirectory', EmployeeDirectory::class)->name('employee-directory');
                Route::get('/user/analytics-hub', AnalyticsHub::class)->name('analytics-hub');
                Route::get('/user/analytics-hub-viewall', AnalyticsHubViewAll::class)->name('analytics-hub-viewall');
                Route::get('/user/main-overview', HrMainOverview::class)->name('main-overview');
                Route::get('/user/attendance-overview', HrAttendanceOverviewNew::class)->name('attendance-overview');
                Route::get('/user/who-is-in-chart-hr', WhoIsInChartHr::class)->name('who-is-in-chart-hr');
                Route::get('/user/attendance-info', HrAttendanceInfo::class)->name('attendance-info');
                Route::get('/calendar/information/employee-leave', EmployeeLeave::class)->name('employee-leave');
    });




});


