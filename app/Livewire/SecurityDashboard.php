<?php

namespace App\Livewire;

use App\Models\SecurityAuditLog;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * =====================================================
 * LIVEWIRE: SECURITY DASHBOARD
 * =====================================================
 *
 * Dashboard keamanan yang menampilkan:
 * - Statistik keamanan (total login, gagal, SQL injection attempts)
 * - Log audit keamanan terbaru
 * - Filter berdasarkan tipe event
 *
 * Hanya bisa diakses oleh admin (middleware 'admin').
 */
class SecurityDashboard extends Component
{
    use WithPagination;

    public string $filterType = '';
    public string $filterIp = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $search = '';

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function updatingFilterIp(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Refresh data (triggers Livewire re-render).
     */
    public function refreshData(): void
    {
        // Simply triggers a re-render which refreshes all stats & logs
    }

    public function render()
    {
        // Statistik
        $stats = [
            'total_events' => SecurityAuditLog::count(),
            'login_success' => SecurityAuditLog::where('event_type', 'login_success')->count(),
            'login_failed' => SecurityAuditLog::where('event_type', 'login_failed')->count(),
            'sql_injection' => SecurityAuditLog::where('event_type', 'sql_injection_attempt')->count(),
            'rate_limited' => SecurityAuditLog::where('event_type', 'login_rate_limited')->count(),
            'unique_ips' => SecurityAuditLog::distinct('ip_address')->count('ip_address'),
        ];

        // Query logs dengan filter
        $query = SecurityAuditLog::with('user')
            ->orderBy('created_at', 'desc');

        if ($this->filterType) {
            $query->where('event_type', $this->filterType);
        }

        if ($this->filterIp) {
            $query->where('ip_address', 'like', '%' . $this->filterIp . '%');
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        // Search across user name, email, IP, and event type
        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20);

        // Event types untuk filter dropdown
        $eventTypes = SecurityAuditLog::distinct()->pluck('event_type')->sort()->values();

        return view('livewire.security-dashboard', [
            'stats' => $stats,
            'logs' => $logs,
            'eventTypes' => $eventTypes,
        ]);
    }
}
