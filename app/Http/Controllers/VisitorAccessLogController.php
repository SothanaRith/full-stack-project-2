<?php

namespace App\Http\Controllers;

use App\Models\VisitorAccessLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VisitorAccessLogController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'ip' => ['nullable', 'ip'],
            'user_id' => ['nullable', 'integer', 'min:1'],
            'device_category' => ['nullable', Rule::in(['desktop', 'mobile', 'tablet', 'bot'])],
            'status_code' => ['nullable', 'integer', 'between:100,599'],
        ]);

        $logs = VisitorAccessLog::query()
            ->with('user:id,name,email')
            ->filter($filters)
            ->latest('accessed_at')
            ->paginate(50)
            ->withQueryString();

        return view('admin.visitor-access-logs.index', compact('logs', 'filters'));
    }
}
