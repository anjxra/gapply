@extends('layouts.app')
@section('title', 'Platform Analytics')

@section('content')
<div class="page-wrapper">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 class="page-title">Platform Analytics</h1>
            <p class="page-subtitle">Job performance, application funnel, market intelligence &amp; platform health.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Dashboard
        </a>
    </div>

    {{-- ═══ KPI Strip ═══ --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(155px,1fr));gap:1rem;margin-bottom:2rem;">
        @php
            $kpis = [
                ['label'=>'Total Jobs Posted','value'=>$totalJobs,'icon'=>'<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>','color'=>'var(--accent)'],
                ['label'=>'Open Positions','value'=>$openJobs,'icon'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>','color'=>'var(--success)'],
                ['label'=>'Total Applications','value'=>$totalApps,'icon'=>'<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>','color'=>'#7c3aed'],
                ['label'=>'Acceptance Rate','value'=>$acceptRate.'%','icon'=>'<polyline points="20 6 9 17 4 12"/>','color'=>'var(--success)'],
                ['label'=>'Avg Apps / Job','value'=>$avgAppsPerJob,'icon'=>'<line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>','color'=>'#0369a1'],
                ['label'=>'Job Engagement','value'=>$engagementRate.'%','icon'=>'<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>','color'=>'#d97706'],
            ];
        @endphp
        @foreach($kpis as $k)
        <div class="stat-card" style="align-items:flex-start;gap:.75rem;">
            <div class="stat-label" style="flex:1;min-width:0;font-size:.72rem;line-height:1.4;">{{ $k['label'] }}</div>
            <div class="stat-value" style="color:{{ $k['color'] }};flex-shrink:0;font-size:1.1rem;">{{ $k['value'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- ═══ ROW 1: Job Performance ═══ --}}
    <div style="margin-bottom:.75rem;">
        <h2 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-faint);margin-bottom:1rem;">
            Job Performance Metrics
        </h2>
    </div>
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:1rem;margin-bottom:1.5rem;">

        {{-- Job Status Donut --}}
        <div class="card">
            <div class="card-body">
                <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">Job Status Split</div>
                <div style="position:relative;height:180px;display:flex;align-items:center;justify-content:center;">
                    <canvas id="chartJobStatus"></canvas>
                    <div style="position:absolute;text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:var(--text);">{{ $totalJobs }}</div>
                        <div style="font-size:.68rem;color:var(--text-faint);font-weight:500;">Total</div>
                    </div>
                </div>
                <div style="display:flex;gap:1rem;justify-content:center;margin-top:.75rem;flex-wrap:wrap;">
                    <div style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;color:var(--text-muted);">
                        <div style="width:10px;height:10px;border-radius:50%;background:#4f46e5;flex-shrink:0;"></div> Open ({{ $openJobs }})
                    </div>
                    <div style="display:flex;align-items:center;gap:.35rem;font-size:.75rem;color:var(--text-muted);">
                        <div style="width:10px;height:10px;border-radius:50%;background:#e5e7eb;flex-shrink:0;"></div> Closed ({{ $closedJobs }})
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Jobs by Applications --}}
        <div class="card">
            <div class="card-body">
                <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">Top Jobs by Applications</div>
                @if($topJobsByApps->where('applications_count','>',0)->isEmpty())
                    <div style="text-align:center;padding:2rem;color:var(--text-faint);font-size:.8125rem;">No applications yet — check back once candidates start applying.</div>
                @else
                    <div style="height:180px;">
                        <canvas id="chartTopJobs"></canvas>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Employment Type Distribution --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body">
            <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">Jobs by Employment Type</div>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:.75rem;">
                @foreach($jobsByType as $row)
                @php
                    $pct = $totalJobs > 0 ? round(($row->count / $totalJobs) * 100) : 0;
                    $colors = ['Full-time'=>'#4f46e5','Part-time'=>'#0369a1','Contract'=>'#d97706','Internship'=>'#16a34a'];
                    $color = $colors[$row->employment_type] ?? '#6b7280';
                @endphp
                <div style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.6rem;">
                        <span style="font-size:.78rem;font-weight:600;color:var(--text);">{{ $row->employment_type }}</span>
                        <span style="font-size:.78rem;font-weight:700;color:{{ $color }};">{{ $row->count }} jobs</span>
                    </div>
                    <div style="height:4px;background:var(--border);border-radius:99px;overflow:hidden;">
                        <div style="width:{{ $pct }}%;height:100%;background:{{ $color }};border-radius:99px;"></div>
                    </div>
                    <div style="font-size:.7rem;color:var(--text-faint);margin-top:.35rem;">{{ $pct }}% of all postings</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ ROW 2: Application Analytics ═══ --}}
    <div style="margin-bottom:.75rem;">
        <h2 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-faint);margin-bottom:1rem;">
            Application Funnel &amp; Trends
        </h2>
    </div>
    <div style="display:grid;grid-template-columns:1fr 2fr;gap:1rem;margin-bottom:1.5rem;">

        {{-- Application Status Donut --}}
        <div class="card">
            <div class="card-body">
                <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">Application Status</div>
                <div style="position:relative;height:180px;display:flex;align-items:center;justify-content:center;">
                    <canvas id="chartAppStatus"></canvas>
                    <div style="position:absolute;text-align:center;">
                        <div style="font-size:1.5rem;font-weight:800;color:var(--text);">{{ $totalApps }}</div>
                        <div style="font-size:.68rem;color:var(--text-faint);font-weight:500;">Total</div>
                    </div>
                </div>
                <div style="display:flex;gap:.75rem;justify-content:center;margin-top:.75rem;flex-wrap:wrap;">
                    <div style="font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:.3rem;"><div style="width:9px;height:9px;border-radius:50%;background:#f59e0b;"></div>Pending {{ $pendingApps }}</div>
                    <div style="font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:.3rem;"><div style="width:9px;height:9px;border-radius:50%;background:#16a34a;"></div>Accepted {{ $acceptedApps }}</div>
                    <div style="font-size:.73rem;color:var(--text-muted);display:flex;align-items:center;gap:.3rem;"><div style="width:9px;height:9px;border-radius:50%;background:#dc2626;"></div>Rejected {{ $rejectedApps }}</div>
                </div>
            </div>
        </div>

        {{-- 7-Day Application Trend --}}
        <div class="card">
            <div class="card-body">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                    <div style="font-size:.8rem;font-weight:700;color:var(--text);">Application Trend (Last 7 Days)</div>
                    <div style="font-size:.72rem;color:var(--text-muted);">Daily submissions</div>
                </div>
                <div style="height:180px;"><canvas id="chartTrend"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Funnel Progress Bars --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body">
            <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1.25rem;">Application Conversion Funnel</div>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                @php
                    $funnel = [
                        ['label'=>'Received','count'=>$totalApps,'color'=>'#4f46e5','pct'=>100],
                        ['label'=>'Pending Review','count'=>$pendingApps,'color'=>'#f59e0b','pct'=>$totalApps>0?round(($pendingApps/$totalApps)*100):0],
                        ['label'=>'Accepted','count'=>$acceptedApps,'color'=>'#16a34a','pct'=>$totalApps>0?round(($acceptedApps/$totalApps)*100):0],
                        ['label'=>'Rejected','count'=>$rejectedApps,'color'=>'#dc2626','pct'=>$totalApps>0?round(($rejectedApps/$totalApps)*100):0],
                    ];
                @endphp
                @foreach($funnel as $f)
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.3rem;">
                        <span style="font-size:.8rem;font-weight:500;color:var(--text);">{{ $f['label'] }}</span>
                        <span style="font-size:.8rem;font-weight:700;color:{{ $f['color'] }};">{{ $f['count'] }} ({{ $f['pct'] }}%)</span>
                    </div>
                    <div style="height:6px;background:var(--border);border-radius:99px;overflow:hidden;">
                        <div style="width:{{ $f['pct'] }}%;height:100%;background:{{ $f['color'] }};border-radius:99px;transition:width .6s ease;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ═══ ROW 3: Market Intelligence ═══ --}}
    <div style="margin-bottom:.75rem;">
        <h2 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-faint);margin-bottom:1rem;">
            Market &amp; Competitive Intelligence
        </h2>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">

        {{-- Top Locations --}}
        <div class="card">
            <div class="card-body">
                <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;margin-right:.25rem;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Top Hiring Locations
                </div>
                @if($topLocations->isEmpty())
                    <div style="text-align:center;padding:1.5rem;color:var(--text-faint);font-size:.8rem;">No location data yet.</div>
                @else
                    @php $maxLoc = $topLocations->max('count') ?: 1; @endphp
                    <div style="display:flex;flex-direction:column;gap:.7rem;">
                        @foreach($topLocations as $loc)
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:.25rem;">
                                <span style="font-size:.78rem;color:var(--text);font-weight:500;">{{ $loc->location }}</span>
                                <span style="font-size:.78rem;color:var(--accent);font-weight:700;">{{ $loc->count }}</span>
                            </div>
                            <div style="height:4px;background:var(--border);border-radius:99px;">
                                <div style="width:{{ round(($loc->count/$maxLoc)*100) }}%;height:100%;background:var(--accent);border-radius:99px;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Acceptance Rate by Type --}}
        <div class="card">
            <div class="card-body">
                <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline;margin-right:.25rem;"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    Acceptance Rate by Type
                </div>
                @if($typeAcceptance->isEmpty())
                    <div style="text-align:center;padding:1.5rem;color:var(--text-faint);font-size:.8rem;">No applications data yet.</div>
                @else
                    @php
                        $typeColors = ['Full-time'=>'#4f46e5','Part-time'=>'#0369a1','Contract'=>'#d97706','Internship'=>'#16a34a'];
                    @endphp
                    <div style="display:flex;flex-direction:column;gap:.7rem;">
                        @foreach($typeAcceptance as $ta)
                        @php $c = $typeColors[$ta['type']] ?? '#6b7280'; @endphp
                        <div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:.25rem;">
                                <span style="font-size:.78rem;color:var(--text);font-weight:500;">{{ $ta['type'] }}</span>
                                <span style="font-size:.78rem;font-weight:700;color:{{ $c }};">{{ $ta['rate'] }}%</span>
                            </div>
                            <div style="height:4px;background:var(--border);border-radius:99px;">
                                <div style="width:{{ $ta['rate'] }}%;height:100%;background:{{ $c }};border-radius:99px;"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Market insight callouts --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
        @php
            $dominant = $jobsByType->first();
            $staleAlert = $staleJobs > 0;
        @endphp
        <div style="background:var(--info-bg);border:1px solid var(--info-border);border-radius:var(--radius);padding:1rem;">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--info-text);margin-bottom:.35rem;">Most Demanded Type</div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--text);">{{ $dominant->employment_type ?? '—' }}</div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:.2rem;">{{ $dominant->count ?? 0 }} postings listed</div>
        </div>
        <div style="background:{{ $staleAlert ? 'var(--error-bg)' : 'var(--success-bg)' }};border:1px solid {{ $staleAlert ? 'var(--error-border)' : 'var(--success-border)' }};border-radius:var(--radius);padding:1rem;">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:{{ $staleAlert ? 'var(--danger)' : 'var(--success)' }};margin-bottom:.35rem;">Stale Listings</div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--text);">{{ $staleJobs }}</div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:.2rem;">Open jobs older than 7 days with no applicants</div>
        </div>
        <div style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-faint);margin-bottom:.35rem;">Platform Activity</div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--text);">{{ $engagementRate }}%</div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:.2rem;">Jobs that received at least one application</div>
        </div>
        <div style="background:var(--surface-alt);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;">
            <div style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--text-faint);margin-bottom:.35rem;">Accept Rate</div>
            <div style="font-size:1.1rem;font-weight:800;color:var(--text);">{{ $acceptRate }}%</div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:.2rem;">Of all submitted applications were accepted</div>
        </div>
    </div>

    {{-- ═══ ROW 4: Platform Health ═══ --}}
    <div style="margin-bottom:.75rem;">
        <h2 style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--text-faint);margin-bottom:1rem;">
            Platform Health
        </h2>
    </div>
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1rem;margin-bottom:1.5rem;">

        {{-- Top Employers Table --}}
        <div class="card">
            <div class="card-body" style="padding-bottom:0;">
                <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">Employer Activity Ranking</div>
                <table class="gapply-table">
                    <thead><tr><th>Employer</th><th>Jobs</th><th>Applications</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($topEmployers as $emp)
                        <tr>
                            <td>
                                <div style="font-weight:600;color:var(--text);font-size:.8rem;">{{ $emp->name }}</div>
                                <div style="font-size:.72rem;color:var(--text-muted);">{{ $emp->email }}</div>
                            </td>
                            <td><span class="text-strong">{{ $emp->job_count }}</span></td>
                            <td><span class="text-strong">{{ $emp->app_count }}</span></td>
                            <td><span class="badge {{ $emp->status === 'active' ? 'badge-active' : 'badge-disabled' }}">{{ ucfirst($emp->status) }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" style="text-align:center;color:var(--text-faint);padding:2rem;">No employer data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- User Health --}}
        <div class="card">
            <div class="card-body">
                <div style="font-size:.8rem;font-weight:700;color:var(--text);margin-bottom:1rem;">User Distribution</div>
                <div style="position:relative;height:160px;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                    <canvas id="chartUsers"></canvas>
                    <div style="position:absolute;text-align:center;">
                        <div style="font-size:1.25rem;font-weight:800;color:var(--text);">{{ $totalEmployers + $totalApplicants }}</div>
                        <div style="font-size:.65rem;color:var(--text-faint);">Users</div>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:.5rem;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.78rem;">
                        <span style="display:flex;align-items:center;gap:.4rem;color:var(--text-muted);"><div style="width:9px;height:9px;border-radius:50%;background:#4f46e5;"></div>Employers</span>
                        <strong>{{ $totalEmployers }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.78rem;">
                        <span style="display:flex;align-items:center;gap:.4rem;color:var(--text-muted);"><div style="width:9px;height:9px;border-radius:50%;background:#7c3aed;"></div>Applicants</span>
                        <strong>{{ $totalApplicants }}</strong>
                    </div>
                    <hr class="divider" style="margin:.35rem 0;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.78rem;">
                        <span style="color:var(--success);">Active</span>
                        <strong style="color:var(--success);">{{ $activeUsers }}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.78rem;">
                        <span style="color:var(--danger);">Disabled</span>
                        <strong style="color:var(--danger);">{{ $disabledUsers }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ── Theme-aware colors ─────────────────────────────────────────────────
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const gridColor  = isDark ? 'rgba(255,255,255,.07)' : 'rgba(0,0,0,.06)';
    const labelColor = isDark ? '#9ca3af' : '#6b7280';
    const tooltipBg  = isDark ? '#1a1a1e' : '#fff';
    const tooltipBdr = isDark ? '#2d2d38' : '#e5e7eb';

    Chart.defaults.font.family = "'Montserrat', sans-serif";
    Chart.defaults.font.size   = 11;

    const donutOpts = (labels, data, colors) => ({
        type: 'doughnut',
        data: { labels, datasets: [{ data, backgroundColor: colors, borderWidth: 2, borderColor: isDark ? '#1a1a1e' : '#fff', hoverOffset: 4 }] },
        options: {
            cutout: '65%',
            plugins: { legend: { display: false }, tooltip: { backgroundColor: tooltipBg, borderColor: tooltipBdr, borderWidth: 1, titleColor: isDark?'#f3f4f6':'#111827', bodyColor: labelColor } },
            animation: { animateRotate: true, duration: 800 }
        }
    });

    // Job Status
    new Chart(document.getElementById('chartJobStatus'), donutOpts(
        ['Open', 'Closed'],
        [{{ $openJobs }}, {{ $closedJobs }}],
        ['#4f46e5', isDark ? '#374151' : '#e5e7eb']
    ));

    // Application Status
    new Chart(document.getElementById('chartAppStatus'), donutOpts(
        ['Pending', 'Accepted', 'Rejected'],
        [{{ $pendingApps }}, {{ $acceptedApps }}, {{ $rejectedApps }}],
        ['#f59e0b', '#16a34a', '#dc2626']
    ));

    // Users
    new Chart(document.getElementById('chartUsers'), donutOpts(
        ['Employers', 'Applicants'],
        [{{ $totalEmployers }}, {{ $totalApplicants }}],
        ['#4f46e5', '#7c3aed']
    ));

    // 7-Day Trend Line
    new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trend->pluck('date')) !!},
            datasets: [{
                label: 'Applications',
                data: {!! json_encode($trend->pluck('count')) !!},
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79,70,229,.08)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#4f46e5',
                pointRadius: 4,
                pointHoverRadius: 6,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: tooltipBg, borderColor: tooltipBdr, borderWidth: 1, titleColor: isDark?'#f3f4f6':'#111827', bodyColor: labelColor } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: labelColor } },
                y: { grid: { color: gridColor }, ticks: { color: labelColor, stepSize: 1 }, beginAtZero: true }
            }
        }
    });

    // Top Jobs by Applications - Horizontal Bar
    @if($topJobsByApps->where('applications_count','>',0)->isNotEmpty())
    new Chart(document.getElementById('chartTopJobs'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topJobsByApps->where('applications_count','>',0)->pluck('title')) !!},
            datasets: [{
                label: 'Applications',
                data: {!! json_encode($topJobsByApps->where('applications_count','>',0)->pluck('applications_count')) !!},
                backgroundColor: 'rgba(79,70,229,.8)',
                borderRadius: 4,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { backgroundColor: tooltipBg, borderColor: tooltipBdr, borderWidth: 1, titleColor: isDark?'#f3f4f6':'#111827', bodyColor: labelColor } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: labelColor, stepSize: 1 }, beginAtZero: true },
                y: { grid: { display: false }, ticks: { color: labelColor } }
            }
        }
    });
    @endif
</script>
@endpush
@endsection
