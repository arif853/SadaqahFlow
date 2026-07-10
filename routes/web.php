<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\KhedmotController;
use App\Http\Controllers\Admin\MembersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProgramTypeController;
use App\Http\Controllers\Admin\FundCollectionController;
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
// Admin maintenance shortcuts — must be authenticated and admin-level.
// (Previously public, which let anyone thrash the cache anonymously.)
Route::middleware('auth')->group(function () {
    Route::get('/storage_link', function () {
        abort_unless(auth()->user()->isAdminLevel(), 403);
        Artisan::call('storage:link');
        return redirect()->back()->with('status', ['type' => 'success', 'message' => 'Storage linked successfully.']);
    })->name('maintenance.storage-link');

    Route::get('/cache_clear', function () {
        abort_unless(auth()->user()->isAdminLevel(), 403);
        Artisan::call('optimize:clear');
        return redirect()->back()->with('status', ['type' => 'success', 'message' => 'Cache cleared.']);
    })->name('maintenance.cache-clear');
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {

    //Members Routes
    Route::resource('members',MembersController::class);

    //Users Routes
    Route::resource('users',UserController::class);

    // কল্যাণ / ভাড়া collection screens — registered BEFORE the khedmots resource
    // so these GET paths aren't swallowed by khedmots/{khedmot} (show).
    Route::get('khedmots/kolyan',[KhedmotController::class,'kolyanIndex'])->name('khedmots.kolyan.index');
    Route::get('khedmots/rent',[KhedmotController::class,'rentIndex'])->name('khedmots.rent.index');
    Route::get('khedmots/collection-search/{type}',[KhedmotController::class,'collectionSearch'])->name('collections.search')->middleware('throttle:30,1');

    //Khedmots Routes
    Route::resource('khedmots',KhedmotController::class);
    Route::post('khedmots/kolyan/store',[KhedmotController::class,'kolyanStore'])->name('khedmots.kolyan.store')->middleware('throttle:60,1');
    Route::post('khedmots/rent/store',[KhedmotController::class,'rentStore'])->name('khedmots.rent.store')->middleware('throttle:60,1');

    Route::post('khedmots/kolyan/update/{id}',[KhedmotController::class,'kolyanUpdate'])->name('khedmots.kolyan.update')->middleware('throttle:60,1');
    Route::post('khedmots/rent/update/{id}',[KhedmotController::class,'rentUpdate'])->name('khedmots.rent.update')->middleware('throttle:60,1');

    //Khedmots Routes
    Route::get('khedmots/khedmot-search/search',[KhedmotController::class,'search'])->name('khedmots.search')->middleware('throttle:30,1');

        //Fund receive Routes
    Route::get('fund_collections/receive/index',[FundCollectionController::class,'receiveIndex'])->name('fund.receive.index');
    Route::get('fund_collections/receive/create',[FundCollectionController::class,'receiveCreate'])->name('fund.receive.create');
    Route::post('fund/receive/store',[FundCollectionController::class,'receive'])->name('fund.receive.store');
    Route::post('fund/receive/{id}/approve', [FundCollectionController::class, 'approve'])->name('fund.receive.approve');
    Route::post('fund/receive/{id}/cancel', [FundCollectionController::class, 'cancel'])->name('fund.receive.cancel');

    //Fund pay Routes
    Route::get('fund_collections/pay/index',[FundCollectionController::class,'payIndex'])->name('fund.pay.index');
    Route::get('fund_collections/pay/create',[FundCollectionController::class,'payCreate'])->name('fund.pay.create');
    Route::post('fund_collections/pay/store',[FundCollectionController::class,'pay'])->name('fund.pay.store');

    //Members Routes
    Route::get('/members/status/{id}', [MembersController::class, 'status'])->name('members.status');
    Route::get('/members/member-search/search', [MembersController::class, 'memberSearch'])->name('members.member-search')->middleware('throttle:30,1');

    //Users Routes
    Route::get('/users/members/{id}/get-member', [UserController::class, 'getMember'])->name('users.get-member');
    Route::post('/users/members/{id}/assign', [UserController::class, 'assignMember'])->name('users.assign-member');
    Route::delete('/users/assign-member/remove/{memberId}/{userId}', [UserController::class, 'assignMemberRemove'])->name('users.assign-member.remove');
    Route::get('/users/status/{id}', [UserController::class, 'status'])->name('users.status');
    Route::get('/users/users-search/search-by-name', [UserController::class, 'usersSearchByName'])->name('users.users-search-by-name');
    // Route::post('/dashboard/users/roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('auth');
    // Route::delete('/dashboard/users/roles/{id}/delete', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('auth');

    //Roles Routes
    Route::resource('/dashboard/users/roles', RoleController::class)->middleware('auth');
    Route::get('/dashboard/users/roles/{roleId}/give-permissions',[RoleController::class, 'addPermission'])->name('roles.give-permissions')->middleware('auth');
    Route::put('/dashboard/users/roles/{roleId}/give-permissions',[RoleController::class, 'addPermissionToRole'])->name('roles.add-permission')->middleware('auth');

    //Permissions Routes
    Route::resource('/dashboard/users/permissions', PermissionController::class)->middleware('auth');
    Route::delete('/dashboard/users/permissions/bulkdelete', [PermissionController::class, 'bulkDelete'])->name('permissions.bulk_delete');

    Route::resource('dashboard/program-types',ProgramTypeController::class);
    Route::get('/program-types/status/{id}', [ProgramTypeController::class, 'status'])->name('program-types.status');


    //Reports Routes
    Route::get('/reports/user-wise-report', [ReportController::class, 'index'])->name('reports.index');
    // AJAX preview route (GET) to fetch khedmots as JSON for preview
    Route::get('/reports/user-wise-report/fetchKhedmot', [ReportController::class, 'fetchKhedmot'])->name('reports.user-wise-report.fetch');
    // Download route (POST) to generate PDF
    Route::post('/reports/user-wise-report/fetchKhedmot', [ReportController::class, 'userWiseReport'])->name('reports.user-wise-report');

    //Notification Routes (in-app bell + Web Push subscription)
    Route::post('/push-subscriptions', [NotificationController::class, 'subscribe'])->name('push.subscribe');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read', [NotificationController::class, 'markAllRead'])->name('notifications.read');

    //Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
