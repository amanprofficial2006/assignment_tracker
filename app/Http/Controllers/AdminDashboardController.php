<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\User;
use Illuminate\Contracts\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display a high-level admin overview.
     */
    public function index(): View
    {
        $stats = Assignment::query()
            ->selectRaw('COUNT(*) as total_count')
            ->selectRaw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count")
            ->selectRaw("SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed_count")
            ->selectRaw("SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_count")
            ->first();

        $recentAssignments = Assignment::query()
            ->with([
                'creator:id,name',
                'reviewer:id,name',
            ])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'recentAssignments' => $recentAssignments,
            'stats' => $stats,
            'usersCount' => User::query()->count(),
        ]);
    }
}
