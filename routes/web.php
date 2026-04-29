<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Employer\JobController as EmployerJobController;
use App\Http\Controllers\Employer\ApplicationController as EmployerApplicationController;
use App\Http\Controllers\Applicant\JobController as ApplicantJobController;
use App\Http\Controllers\Applicant\ApplicationController as ApplicantApplicationController;
use Illuminate\Support\Facades\Route;

// ─── Public Home / Landing Page ────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return match (auth()->user()->role) {
            'superadmin' => redirect()->route('admin.dashboard'),
            'employer'   => redirect()->route('employer.dashboard'),
            default      => redirect()->route('jobs.index'),
        };
    }
    return view('home');
})->name('home');

// ─── Authentication ─────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ─── Public Job Board ────────────────────────────────────────────────────────
Route::get('/jobs',       [ApplicantJobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [ApplicantJobController::class, 'show'])->name('jobs.show');

// ─── Applicant (must be logged in as applicant) ──────────────────────────────
Route::middleware(['auth', 'role:applicant'])->group(function () {
    Route::get('/jobs/{job}/apply',  [ApplicantApplicationController::class, 'create'])->name('applicant.apply');
    Route::post('/jobs/{job}/apply', [ApplicantApplicationController::class, 'store'])->name('applicant.apply.store');
    Route::get('/my-applications',   [ApplicantApplicationController::class, 'index'])->name('applicant.applications');
    Route::get('/notifications', function () {
        return view('applicant.notifications');
    })->name('applicant.notifications');
    Route::post('/notifications/{id}/read', function ($id) {
        $n = auth()->user()->notifications()->findOrFail($id);
        $n->markAsRead();
        return back();
    })->name('notifications.read');
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read-all');
});

// ─── Employer ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::get('/dashboard', function () {
        $jobCount  = \App\Models\Job::where('employer_id', auth()->id())->count();
        $openCount = \App\Models\Job::where('employer_id', auth()->id())->where('status', 'open')->count();
        $appCount  = \App\Models\Application::whereIn('job_id', \App\Models\Job::where('employer_id', auth()->id())->pluck('id'))->count();
        $pending   = \App\Models\Application::whereIn('job_id', \App\Models\Job::where('employer_id', auth()->id())->pluck('id'))->where('status', 'Pending')->count();
        return view('employer.dashboard', compact('jobCount', 'openCount', 'appCount', 'pending'));
    })->name('dashboard');

    Route::resource('jobs', EmployerJobController::class);
    Route::get('/applications',                        [EmployerApplicationController::class, 'index'])->name('applications.index');
    Route::patch('/applications/{application}/status', [EmployerApplicationController::class, 'updateStatus'])->name('applications.status');

    // Employer notifications
    Route::get('/notifications', function () {
        return view('employer.notifications');
    })->name('notifications');
    Route::post('/notifications/{id}/read', function ($id) {
        $n = auth()->user()->notifications()->findOrFail($id);
        $n->markAsRead();
        return back();
    })->name('notifications.read');
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.read-all');
});

// ─── Super Admin ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',               [AdminController::class, 'index'])->name('dashboard');
    Route::get('/analytics',               [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/reports',                 [AdminController::class, 'reports'])->name('reports');
    Route::post('/employers',              [AdminController::class, 'createEmployer'])->name('employers.store');
    Route::put('/users/{user}',            [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{user}/toggle',   [AdminController::class, 'toggleStatus'])->name('users.toggle');
    Route::delete('/users/{user}',         [AdminController::class, 'deleteUser'])->name('users.delete');
});

