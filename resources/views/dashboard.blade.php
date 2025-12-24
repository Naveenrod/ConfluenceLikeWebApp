@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600">Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6 lg:col-span-3">
            <h2 class="text-xl font-semibold mb-4">Weekly Activity (Spaces & Pages)</h2>
            <p class="text-sm text-gray-500 mb-4">Number of spaces and pages created per week (last 6 weeks).</p>
            <div class="h-72">
                <canvas id="weeklyActivityChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Spaces</h2>
            @if($recentSpaces->count() > 0)
                <ul class="space-y-2">
                    @foreach($recentSpaces as $space)
                        <li>
                            <a href="{{ route('spaces.show', $space->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-folder mr-2"></i>{{ $space->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No spaces yet. <a href="{{ route('spaces.create') }}" class="text-blue-600">Create one</a></p>
            @endif
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Pages</h2>
            @if($recentPages->count() > 0)
                <ul class="space-y-2">
                    @foreach($recentPages as $page)
                        <li>
                            <a href="{{ route('pages.show', $page->id) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-alt mr-2"></i>{{ $page->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500">No pages yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('weeklyActivityChart');
    if (!ctx) return;

    const chartData = @json($chartData ?? ['labels' => [], 'spaces' => [], 'pages' => []]);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Spaces',
                    data: chartData.spaces,
                    backgroundColor: 'rgba(37, 99, 235, 0.6)', // blue-600
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                },
                {
                    label: 'Pages',
                    data: chartData.pages,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)', // emerald-500
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                    },
                },
            },
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
        },
    });
});
</script>
@endpush

