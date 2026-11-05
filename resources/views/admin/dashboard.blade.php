@extends('admin.layouts.app')
@push('backend_style')
<style>
.dashboard-stat {position:relative;overflow:hidden;min-height:145px;color:#fff;border:0;box-shadow:0 3px 12px #22335516}
.dashboard-stat::after {content:'';position:absolute;inset:55% 0 0;background:#0000000d;clip-path:polygon(0 30%,20% 50%,40% 10%,65% 40%,83% 0,100% 35%,100% 100%,0 100%)}
.dashboard-stat .card-body {position:relative;z-index:1;display:flex;flex-direction:column;justify-content:space-between;gap:20px}
.dashboard-stat .stat-value {font-size:clamp(1.25rem,2vw,1.8rem);font-weight:600;overflow-wrap:anywhere;text-align:right}
.dashboard-stat .stat-label {font-size:.8rem;font-weight:700;text-transform:uppercase;color:#fff}
.dashboard-stat i {font-size:2.3rem;color:#fff}
.dashboard-stat.green {background:#91c52a}.dashboard-stat.red {background:#f45460}.dashboard-stat.orange {background:#ffb900}.dashboard-stat.purple {background:#a366ec}.dashboard-stat.blue {background:#438bc5}
.dashboard-chart {height:360px;width:100%}.dashboard-filter-bar {background:#4d83ad;border-radius:6px;padding:16px;color:#fff}
.dashboard-filter-bar .form-label {color:#fff}.dashboard-chart-empty {padding:3rem 1rem;text-align:center;color:#8592a3}
@media(max-width:575px){.dashboard-chart{height:310px}.dashboard-stat{min-height:125px}}
</style>
@endpush
@section('admin-content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h4 class="mb-0"><i class="bx bx-grid-alt me-1" aria-hidden="true"></i>Dashboard</h4>
        <div class="d-flex flex-wrap gap-2" aria-label="Dashboard period">
            @foreach ($periods as $key => $label)
                <a class="btn btn-sm {{ !$report['customRange'] && $report['period'] === $key ? 'btn-primary' : 'btn-label-primary' }}" href="{{ route('admin.dashboard', array_merge(request()->only(['source', 'staff']), ['period' => $key])) }}" @if (!$report['customRange'] && $report['period'] === $key) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
        </div>
    </div>
    @if ($errors->any())<div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form method="GET" action="{{ route('admin.dashboard') }}" class="dashboard-filter-bar mb-4">
        <input type="hidden" name="period" value="{{ $report['period'] }}">
        <div class="row g-3 align-items-end">
            <div class="col-12 col-sm-6 col-xl-3"><label class="form-label" for="dashboard-source">Order Source</label><select id="dashboard-source" name="source" class="form-select"><option value="">All Sources</option>@foreach (['Website', 'GSale', 'App'] as $source)<option @selected(($filters['source'] ?? '') === $source)>{{ $source }}</option>@endforeach</select></div>
            <div class="col-12 col-sm-6 col-xl-3"><label class="form-label" for="dashboard-staff">Sale By</label><select class="form-select" name="staff" id="dashboard-staff"><option value="">All Staff</option>@foreach ($staff as $person)<option value="{{ $person->id }}" @selected(($filters['staff'] ?? '') == $person->id)>{{ $person->name }}</option>@endforeach</select></div>
            <div class="col-6 col-xl-2"><label class="form-label" for="dashboard-from">From Date</label><input class="form-control" id="dashboard-from" name="from" type="date" min="1900-01-01" max="2100-12-31" value="{{ $report['customRange'] ? $report['from'] : '' }}"></div>
            <div class="col-6 col-xl-2"><label class="form-label" for="dashboard-to">To Date</label><input class="form-control" id="dashboard-to" name="to" type="date" min="1900-01-01" max="2100-12-31" value="{{ $report['customRange'] ? $report['to'] : '' }}"></div>
            <div class="col-12 col-xl-2 d-flex gap-2"><button class="btn btn-dark" type="submit"><i class="bx bx-search me-1" aria-hidden="true"></i>Search</button><a class="btn btn-light" href="{{ route('admin.dashboard') }}">Reset</a></div>
        </div>
    </form>
    <p class="text-muted small mb-4">{{ $report['rangeLabel'] }} (Asia/Dhaka). Sales include order totals except currently cancelled or returned orders. Customer and product counts show records created in this period; source and staff filters apply to orders. This Week starts on Monday.</p>
    @php
        $stats = $report['stats'];
        $tiles = [
            ['label' => 'Total Sales', 'value' => '৳' . number_format($stats['sales_amount'], 2) . ' (' . number_format($stats['sales_count']) . ')', 'color' => 'green', 'icon' => 'bx-line-chart'],
            ['label' => 'Total GSale', 'value' => '৳' . number_format($stats['gsale_amount'], 2) . ' (' . number_format($stats['gsale_count']) . ')', 'color' => 'red', 'icon' => 'bx-store'],
            ['label' => 'Total Website Sales', 'value' => '৳' . number_format($stats['website_amount'], 2) . ' (' . number_format($stats['website_count']) . ')', 'color' => 'blue', 'icon' => 'bx-globe'],
            ['label' => 'Total Cancelled Orders', 'value' => number_format($stats['cancelled']), 'color' => 'orange', 'icon' => 'bx-x-circle'],
            ['label' => 'Total Returned Orders', 'value' => number_format($stats['returned']), 'color' => 'purple', 'icon' => 'bx-undo'],
            ['label' => 'Total Customers', 'value' => number_format($stats['customers']), 'color' => 'orange', 'icon' => 'bx-group'],
            ['label' => 'Total Products', 'value' => number_format($stats['products']), 'color' => 'green', 'icon' => 'bx-package'],
        ];
    @endphp
    <div class="row g-4 mb-5">
        @foreach ($tiles as $tile)
            <div class="col-12 col-sm-6 col-xl-3"><div class="card dashboard-stat {{ $tile['color'] }} h-100"><div class="card-body"><div class="d-flex align-items-center justify-content-between gap-3"><i class="bx {{ $tile['icon'] }}" aria-hidden="true"></i><span class="stat-value">{{ $tile['value'] }}</span></div><span class="stat-label">{{ $tile['label'] }}</span></div></div></div>
        @endforeach
    </div>
    <div class="card mb-4"><div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3"><h5 class="mb-0"><span class="text-primary">DATEWISE</span> SALE / ORDER</h5><div class="d-flex align-items-center gap-3"><label for="dashboard-chart-metric" class="visually-hidden">Chart metric</label><select class="form-select form-select-sm" id="dashboard-chart-metric"><option value="amount">Sales Amount (BDT)</option><option value="quantity">Order Count</option></select><button type="button" class="btn btn-sm btn-label-secondary dashboard-chart-toggle" data-target="dashboard-date-panel" aria-expanded="true">Hide</button></div></div>
        <div class="card-body" id="dashboard-date-panel"><div id="dashboard-daily-chart" class="dashboard-chart" role="img" aria-label="Datewise sales and order chart"></div><p class="text-muted small mb-0 mt-3">Daily totals for {{ $report['rangeLabel'] }}. Hover to see sales amount and order count. Drag to zoom.</p></div>
    </div>
    <div class="card mb-4"><div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-3"><h5 class="mb-0"><span class="text-primary">MONTHLY</span> SALE / ORDER</h5><button type="button" class="btn btn-sm btn-label-secondary dashboard-chart-toggle" data-target="dashboard-month-panel" aria-expanded="true">Hide</button></div>
        <div class="card-body" id="dashboard-month-panel"><div class="row g-4"><div class="col-12 col-xl-6"><div id="dashboard-monthly-chart" class="dashboard-chart" role="img" aria-label="Monthly sales and order column chart"></div></div><div class="col-12 col-xl-6"><div id="dashboard-monthly-pie" class="dashboard-chart" role="img" aria-label="Monthly sales and order distribution pie chart"></div></div></div><p class="text-muted small mb-0 mt-3">Each month includes only dates inside the selected period. Cancelled and returned orders are excluded.</p></div>
    </div>
    <details class="card"><summary class="card-header">View Monthly Data</summary><div class="table-responsive"><table class="table"><thead><tr><th>Month</th><th>Orders</th><th>Sales Amount (BDT)</th></tr></thead><tbody>@foreach ($report['monthly'] as $month)<tr><td>{{ $month['date'] }}</td><td>{{ number_format($month['quantity']) }}</td><td>{{ number_format($month['amount'], 2) }}</td></tr>@endforeach</tbody></table></div></details>
@endsection
@push('backend_script')
    <script>window.dashboardData = @json(['daily' => $report['daily'], 'monthly' => $report['monthly']]);</script>
    <script src="https://cdn.canvasjs.com/ga/canvasjs.min.js"></script>
    <script src="{{ asset('backend/assets/js/admin-dashboard.js') }}"></script>
@endpush
