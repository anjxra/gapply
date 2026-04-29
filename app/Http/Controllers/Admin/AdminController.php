<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Separate count queries for stat cards (unaffected by pagination)
        $employerCount  = User::where('role', 'employer')->count();
        $applicantCount = User::where('role', 'applicant')->count();

        $stats = [
            'total_employers'   => $employerCount,
            'active_employers'  => User::where('role', 'employer')->where('status', 'active')->count(),
            'total_applicants'  => $applicantCount,
            'active_applicants' => User::where('role', 'applicant')->where('status', 'active')->count(),
            'all_accounts'      => $employerCount + $applicantCount,
        ];

        // Paginate independently — emp_page / app_page keeps them from colliding
        $employers  = User::where('role', 'employer')->latest()->paginate(8, ['*'], 'emp_page')->withQueryString();
        $applicants = User::where('role', 'applicant')->latest()->paginate(8, ['*'], 'app_page')->withQueryString();

        return view('admin.dashboard', compact('employers', 'applicants', 'stats'));
    }

    public function createEmployer(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'employer',
            'status'   => 'active',
        ]);

        return redirect()->route('admin.dashboard')->with('success', "Employer account created for {$request->email}.");
    }

    public function updateUser(Request $request, User $user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Cannot edit a super admin account.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', "Account for {$user->email} has been updated.");
    }

    public function toggleStatus(User $user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Cannot change the status of a super admin account.');
        }

        $user->update([
            'status' => $user->status === 'active' ? 'disabled' : 'active',
        ]);

        $msg = $user->status === 'active' ? 'enabled' : 'disabled';
        return redirect()->route('admin.dashboard')->with('success', "Account for {$user->email} has been {$msg}.");
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Cannot delete a super admin account.');
        }

        $email = $user->email;
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', "Account for {$email} has been deleted.");
    }

    public function analytics()
    {
        // ── Job Performance ──────────────────────────────────────────────────
        $totalJobs  = Job::count();
        $openJobs   = Job::where('status', 'open')->count();
        $closedJobs = Job::where('status', 'closed')->count();

        $jobsByType = Job::selectRaw('employment_type, count(*) as count')
            ->groupBy('employment_type')->orderByDesc('count')->get();

        $topJobsByApps = Job::withCount('applications')
            ->with('employer')
            ->orderByDesc('applications_count')
            ->take(8)->get();

        // ── Application Funnel ───────────────────────────────────────────────
        $totalApps    = Application::count();
        $pendingApps  = Application::where('status', 'Pending')->count();
        $acceptedApps = Application::where('status', 'Accepted')->count();
        $rejectedApps = Application::where('status', 'Rejected')->count();
        $acceptRate   = $totalApps > 0 ? round(($acceptedApps / $totalApps) * 100, 1) : 0;
        $avgAppsPerJob = $totalJobs > 0 ? round($totalApps / $totalJobs, 1) : 0;

        // Jobs with zero applications (stale — open & no apps & 7+ days old)
        $staleJobs = Job::where('status', 'open')
            ->doesntHave('applications')
            ->where('created_at', '<', now()->subDays(7))
            ->count();

        // ── 7-Day Application Trend ──────────────────────────────────────────
        $trend = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $trend->push([
                'date'  => now()->subDays($i)->format('M d'),
                'count' => Application::whereDate('created_at', $date)->count(),
            ]);
        }

        // ── Market Intelligence ──────────────────────────────────────────────
        $topLocations = Job::selectRaw('location, count(*) as count')
            ->where('status', 'open')
            ->groupBy('location')
            ->orderByDesc('count')
            ->take(6)->get();

        // Acceptance rate by employment type
        $typeAcceptance = DB::table('applications')
            ->join('jobs', 'applications.job_id', '=', 'jobs.id')
            ->selectRaw('jobs.employment_type,
                count(*) as total,
                sum(case when applications.status = "Accepted" then 1 else 0 end) as accepted')
            ->groupBy('jobs.employment_type')
            ->get()
            ->map(fn($r) => [
                'type' => $r->employment_type,
                'rate' => $r->total > 0 ? round(($r->accepted / $r->total) * 100, 1) : 0,
            ]);

        // ── Platform Health ──────────────────────────────────────────────────
        $totalEmployers  = User::where('role', 'employer')->count();
        $totalApplicants = User::where('role', 'applicant')->count();
        $activeUsers     = User::whereIn('role', ['employer','applicant'])->where('status','active')->count();
        $disabledUsers   = User::whereIn('role', ['employer','applicant'])->where('status','disabled')->count();

        $topEmployers = DB::table('users')
            ->leftJoin('jobs', 'users.id', '=', 'jobs.employer_id')
            ->leftJoin('applications', 'jobs.id', '=', 'applications.job_id')
            ->where('users.role', 'employer')
            ->selectRaw('users.name, users.email, users.status,
                count(DISTINCT jobs.id) as job_count,
                count(DISTINCT applications.id) as app_count')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.status')
            ->orderByDesc('job_count')
            ->take(6)->get();

        // Jobs with ≥1 application (engagement rate)
        $engagedJobs   = Job::has('applications')->count();
        $engagementRate = $totalJobs > 0 ? round(($engagedJobs / $totalJobs) * 100) : 0;

        return view('admin.analytics', compact(
            'totalJobs', 'openJobs', 'closedJobs',
            'jobsByType', 'topJobsByApps',
            'totalApps', 'pendingApps', 'acceptedApps', 'rejectedApps',
            'acceptRate', 'avgAppsPerJob', 'staleJobs',
            'trend', 'topLocations', 'typeAcceptance',
            'totalEmployers', 'totalApplicants',
            'activeUsers', 'disabledUsers',
            'topEmployers', 'engagementRate'
        ));
    }

    public function reports()
    {
        // Totals via separate queries (not limited by paginator)
        $totalJobs = Job::count();
        $totalApps = Application::count();

        $jobs = Job::with(['employer', 'applications.applicant'])
            ->latest()
            ->paginate(8);

        return view('admin.reports', compact('jobs', 'totalJobs', 'totalApps'));
    }
}
