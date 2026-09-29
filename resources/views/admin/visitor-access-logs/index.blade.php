<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-bold text-2xl text-stone-900 leading-tight">Visitor Access Logs</h2>
            <p class="text-sm text-stone-500 mt-1">Operational request records. IP addresses identify network endpoints, not specific people.</p>
        </div>
    </x-slot>

    <div class="py-8 bg-stone-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
            <form method="GET" action="{{ route('visitor-access-logs.index') }}" class="bg-white rounded-2xl border border-stone-200 p-5 shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <label class="text-xs font-semibold text-stone-600">From
                    <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="mt-1 block w-full rounded-lg border-stone-300 text-sm">
                </label>
                <label class="text-xs font-semibold text-stone-600">To
                    <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="mt-1 block w-full rounded-lg border-stone-300 text-sm">
                </label>
                <label class="text-xs font-semibold text-stone-600">IP address
                    <input type="text" name="ip" value="{{ $filters['ip'] ?? '' }}" placeholder="203.0.113.10" class="mt-1 block w-full rounded-lg border-stone-300 text-sm">
                </label>
                <label class="text-xs font-semibold text-stone-600">User ID
                    <input type="number" min="1" name="user_id" value="{{ $filters['user_id'] ?? '' }}" class="mt-1 block w-full rounded-lg border-stone-300 text-sm">
                </label>
                <label class="text-xs font-semibold text-stone-600">Device
                    <select name="device_category" class="mt-1 block w-full rounded-lg border-stone-300 text-sm">
                        <option value="">All</option>
                        @foreach (['desktop', 'mobile', 'tablet', 'bot'] as $category)
                            <option value="{{ $category }}" @selected(($filters['device_category'] ?? '') === $category)>{{ ucfirst($category) }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-xs font-semibold text-stone-600">Status
                    <input type="number" min="100" max="599" name="status_code" value="{{ $filters['status_code'] ?? '' }}" placeholder="200" class="mt-1 block w-full rounded-lg border-stone-300 text-sm">
                </label>

                <div class="sm:col-span-2 lg:col-span-6 flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-[#3b271e] text-white rounded-lg text-sm font-semibold hover:bg-[#4d3328]">Search</button>
                    <a href="{{ route('visitor-access-logs.index') }}" class="px-4 py-2 border border-stone-300 rounded-lg text-sm font-semibold text-stone-700 hover:bg-stone-50">Clear</a>
                </div>
            </form>

            <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-stone-50 border-b border-stone-200 text-xs uppercase tracking-wide text-stone-500">
                            <tr>
                                <th class="px-4 py-3">Accessed</th>
                                <th class="px-4 py-3">Request</th>
                                <th class="px-4 py-3">IP / User</th>
                                <th class="px-4 py-3">Client</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($logs as $log)
                                <tr class="align-top hover:bg-amber-50/20">
                                    <td class="px-4 py-3 whitespace-nowrap text-stone-600">{{ $log->accessed_at->format('Y-m-d H:i:s') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold text-stone-900">{{ $log->http_method }} {{ $log->url_path }}</div>
                                        <div class="text-xs text-stone-500">{{ $log->route_name ?? 'unnamed route' }}@if($log->referrer_domain) · ref: {{ $log->referrer_domain }}@endif</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-mono text-xs text-stone-800">{{ $log->ip_address }}</div>
                                        <div class="text-xs text-stone-500">{{ $log->user ? $log->user->name.' (#'.$log->user_id.')' : ($log->user_id ? 'Deleted user #'.$log->user_id : 'Guest') }}</div>
                                    </td>
                                    <td class="px-4 py-3" title="{{ $log->user_agent }}">
                                        <div class="text-stone-800">{{ $log->device_category ? ucfirst($log->device_category) : 'Unknown device' }}</div>
                                        <div class="text-xs text-stone-500">{{ trim(($log->browser_name ?? '').' '.($log->browser_version ?? '')) ?: 'Unknown browser' }} · {{ trim(($log->os_name ?? '').' '.($log->os_version ?? '')) ?: 'Unknown OS' }}</div>
                                    </td>
                                    <td class="px-4 py-3"><span class="font-semibold {{ $log->response_status >= 400 ? 'text-rose-700' : 'text-emerald-700' }}">{{ $log->response_status }}</span></td>
                                    <td class="px-4 py-3 text-stone-600">{{ $log->duration_ms !== null ? $log->duration_ms.' ms' : '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-12 text-center text-stone-500">No visitor access logs match these filters.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-4 py-4 border-t border-stone-200">{{ $logs->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
