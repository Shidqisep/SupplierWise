<div>
    {{-- ====================================================
         SECURITY DASHBOARD - Halaman Monitoring Keamanan
         Hanya bisa diakses oleh admin
         ==================================================== --}}

    <div class="space-y-6">

        {{-- ========== HEADER ========== --}}
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="font-display-lg text-display-lg text-on-background">Security Monitoring</h1>
                <p class="font-body-lg text-body-lg text-secondary">Real-time oversight of system access, threat detection, and audit trails across the logistics network.</p>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="refreshData"
                    class="bg-primary text-on-primary font-label-md text-label-md px-md py-sm rounded-full soft-elevation hover:opacity-90 transition-all active:scale-95 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[20px]" wire:loading.class="animate-spin" wire:target="refreshData">refresh</span>
                    Refresh Live Data
                </button>
            </div>
        </header>

        {{-- ========== STATS GRID ========== --}}
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
            {{-- Total Events --}}
            <div class="bg-white p-md rounded-DEFAULT soft-elevation border border-outline-variant/10 flex flex-col gap-2 stat-card">
                <div class="w-10 h-10 rounded-full bg-primary-fixed/30 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">analytics</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-secondary uppercase">Total Events</p>
                    <p class="font-headline-md text-headline-md">{{ number_format($stats['total_events']) }}</p>
                </div>
            </div>

            {{-- Success Logins --}}
            <div class="bg-white p-md rounded-DEFAULT soft-elevation border border-outline-variant/10 flex flex-col gap-2 stat-card">
                <div class="w-10 h-10 rounded-full bg-primary-fixed/30 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">how_to_reg</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-secondary uppercase">Login Berhasil</p>
                    <p class="font-headline-md text-headline-md">{{ number_format($stats['login_success']) }}</p>
                </div>
            </div>

            {{-- Failed Logins --}}
            <div class="bg-white p-md rounded-DEFAULT soft-elevation border border-outline-variant/10 flex flex-col gap-2 stat-card">
                <div class="w-10 h-10 rounded-full bg-error-container/30 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined">no_accounts</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-secondary uppercase">Login Gagal</p>
                    <p class="font-headline-md text-headline-md">{{ number_format($stats['login_failed']) }}</p>
                </div>
            </div>

            {{-- SQL Injection --}}
            <div class="bg-white p-md rounded-DEFAULT soft-elevation border border-outline-variant/10 flex flex-col gap-2 stat-card">
                <div class="w-10 h-10 rounded-full bg-error-container/30 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined">database_off</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-secondary uppercase">SQL Injection</p>
                    <p class="font-headline-md text-headline-md">{{ number_format($stats['sql_injection']) }}</p>
                </div>
            </div>

            {{-- Rate Limited --}}
            <div class="bg-white p-md rounded-DEFAULT soft-elevation border border-outline-variant/10 flex flex-col gap-2 stat-card">
                <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">speed</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-secondary uppercase">Rate Limited</p>
                    <p class="font-headline-md text-headline-md">{{ number_format($stats['rate_limited']) }}</p>
                </div>
            </div>

            {{-- Unique IPs --}}
            <div class="bg-white p-md rounded-DEFAULT soft-elevation border border-outline-variant/10 flex flex-col gap-2 stat-card">
                <div class="w-10 h-10 rounded-full bg-primary-fixed/30 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">public</span>
                </div>
                <div>
                    <p class="font-label-md text-label-md text-secondary uppercase">IP Unik</p>
                    <p class="font-headline-md text-headline-md">{{ number_format($stats['unique_ips']) }}</p>
                </div>
            </div>
        </section>

        {{-- ========== SEARCH & FILTER BAR ========== --}}
        <section class="bg-white p-md rounded-DEFAULT soft-elevation border border-outline-variant/10 flex flex-wrap gap-4 items-center">
            <div class="relative flex-grow min-w-[300px]">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-secondary">search</span>
                <input wire:model.live.debounce.300ms="search"
                    class="w-full pl-12 pr-md py-sm rounded-full bg-surface-container-low border-none focus:ring-2 focus:ring-primary/20 text-body-md font-body-md"
                    placeholder="Cari berdasarkan user, IP, atau tipe event..." type="text" />
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <select wire:model.live="filterType"
                    class="bg-surface-container-low border-none rounded-full px-md py-sm text-label-md font-label-md text-secondary focus:ring-2 focus:ring-primary/20 cursor-pointer">
                    <option value="">Semua Event</option>
                    @foreach ($eventTypes as $type)
                        <option value="{{ $type }}">{{ str_replace('_', ' ', ucfirst($type)) }}</option>
                    @endforeach
                </select>
                <input wire:model.live="dateFrom" type="date"
                    class="bg-surface-container-low border-none rounded-full px-md py-sm text-label-md font-label-md text-secondary focus:ring-2 focus:ring-primary/20 cursor-pointer"
                    placeholder="Dari" />
                <input wire:model.live="dateTo" type="date"
                    class="bg-surface-container-low border-none rounded-full px-md py-sm text-label-md font-label-md text-secondary focus:ring-2 focus:ring-primary/20 cursor-pointer"
                    placeholder="Sampai" />
            </div>
        </section>

        {{-- ========== RECENT ACTIVITY / AUDIT LOG ========== --}}
        <section class="bg-white rounded-DEFAULT soft-elevation border border-outline-variant/10 overflow-hidden">
            <div class="px-lg py-md border-b border-outline-subtle flex justify-between items-center bg-surface-bright">
                <h2 class="font-headline-sm text-headline-sm text-on-background">Recent Activity Log</h2>
                <span class="px-sm py-1 bg-primary-fixed/20 text-primary font-label-md text-label-md rounded-full flex items-center gap-1">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Live Updates Active
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container-low/50">
                            <th class="px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Timestamp</th>
                            <th class="px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Event Type</th>
                            <th class="px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">User</th>
                            <th class="px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">IP Address</th>
                            <th class="px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Method</th>
                            <th class="px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Status</th>
                            <th class="px-lg py-md font-label-md text-label-md text-secondary uppercase tracking-wider">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-subtle">
                        @forelse ($logs as $log)
                            @php
                                // Icon & color mapping per event type
                                $eventMeta = [
                                    'login_success'        => ['icon' => 'login',           'color' => 'text-primary',   'badge' => 'bg-primary-fixed/20 text-primary',   'label' => 'Success'],
                                    'login_failed'         => ['icon' => 'lock_reset',      'color' => 'text-error',     'badge' => 'bg-error-container/30 text-error',   'label' => 'Failed'],
                                    'login_rate_limited'   => ['icon' => 'speed',           'color' => 'text-secondary', 'badge' => 'bg-surface-container-high text-on-surface-variant', 'label' => 'Rate Limited'],
                                    'sql_injection_attempt'=> ['icon' => 'database_off',    'color' => 'text-error',     'badge' => 'bg-error-container text-error',      'label' => 'Blocked'],
                                    'register_success'     => ['icon' => 'person_add',      'color' => 'text-primary',   'badge' => 'bg-primary-fixed/20 text-primary',   'label' => 'Success'],
                                    'logout'               => ['icon' => 'logout',          'color' => 'text-secondary', 'badge' => 'bg-surface-container-high text-on-surface-variant', 'label' => 'Neutral'],
                                    'google_login'         => ['icon' => 'login',           'color' => 'text-primary',   'badge' => 'bg-primary-fixed/20 text-primary',   'label' => 'Success'],
                                    'google_login_failed'  => ['icon' => 'lock_reset',      'color' => 'text-error',     'badge' => 'bg-error-container/30 text-error',   'label' => 'Failed'],
                                    'suspicious_request'   => ['icon' => 'gpp_maybe',       'color' => 'text-error',     'badge' => 'bg-error-container text-error',      'label' => 'Blocked'],
                                    'password_changed'     => ['icon' => 'key',             'color' => 'text-primary',   'badge' => 'bg-primary-fixed/20 text-primary',   'label' => 'Success'],
                                ];
                                $meta = $eventMeta[$log->event_type] ?? ['icon' => 'info', 'color' => 'text-secondary', 'badge' => 'bg-surface-container-high text-on-surface-variant', 'label' => 'Info'];
                            @endphp
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="px-lg py-md font-body-md text-body-md text-on-surface whitespace-nowrap">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="px-lg py-md whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined {{ $meta['color'] }} text-[18px]">{{ $meta['icon'] }}</span>
                                        <span class="font-body-md text-body-md">{{ str_replace('_', ' ', ucfirst($log->event_type)) }}</span>
                                    </div>
                                </td>
                                <td class="px-lg py-md whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        @if($log->user)
                                            <div class="w-8 h-8 rounded-full bg-primary-fixed/30 text-primary flex items-center justify-center font-bold text-[12px]">
                                                {{ strtoupper(substr($log->user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-label-md text-label-md text-on-surface">{{ $log->user->name }}</p>
                                                <p class="text-[11px] text-secondary">{{ $log->user->email }}</p>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-on-background/10 text-on-background flex items-center justify-center">
                                                <span class="material-symbols-outlined text-[16px]">person_off</span>
                                            </div>
                                            <div>
                                                <p class="font-label-md text-label-md text-on-surface">Anonymous</p>
                                                <p class="text-[11px] text-secondary">Unknown Origin</p>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-lg py-md font-body-md text-body-md font-mono text-secondary whitespace-nowrap">
                                    {{ $log->ip_address }}
                                </td>
                                <td class="px-lg py-md font-body-md text-body-md text-secondary whitespace-nowrap">
                                    {{ $log->request_method }}
                                </td>
                                <td class="px-lg py-md whitespace-nowrap">
                                    <span class="px-sm py-1 {{ $meta['badge'] }} font-label-md text-label-md rounded-full">
                                        {{ $meta['label'] }}
                                    </span>
                                </td>
                                <td class="px-lg py-md font-body-md text-body-md text-secondary max-w-[200px] truncate" title="{{ $log->details }}">
                                    {{ Str::limit($log->details, 60) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-lg py-12 text-center text-secondary">
                                    <div class="flex flex-col items-center gap-2">
                                        <span class="material-symbols-outlined text-[48px] text-outline-variant">shield</span>
                                        <p class="font-body-lg text-body-lg">Belum ada log keamanan. Semua aman!</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($logs->hasPages())
                <div class="px-lg py-md bg-surface-bright border-t border-outline-subtle flex justify-between items-center">
                    <p class="font-body-md text-body-md text-secondary">
                        Showing {{ $logs->firstItem() }} to {{ $logs->lastItem() }} of {{ number_format($logs->total()) }} entries
                    </p>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif
        </section>

        {{-- ========== BOTTOM CARDS (Asymmetric Layout) ========== --}}
        <section class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            {{-- Threat Summary --}}
            <div class="lg:col-span-8 bg-white p-lg rounded-DEFAULT soft-elevation border border-outline-variant/10 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-headline-sm text-headline-sm text-on-background">Threat Summary</h3>
                    <p class="font-body-md text-body-md text-secondary mb-4">Ringkasan distribusi event keamanan berdasarkan tipe ancaman.</p>
                </div>

                {{-- Simple visual threat bars --}}
                <div class="space-y-4 relative z-10">
                    @php
                        $total = max($stats['total_events'], 1);
                        $threatTypes = [
                            ['label' => 'Login Berhasil',   'count' => $stats['login_success'],  'color' => 'bg-primary',          'icon' => 'check_circle'],
                            ['label' => 'Login Gagal',      'count' => $stats['login_failed'],   'color' => 'bg-error',            'icon' => 'warning'],
                            ['label' => 'SQL Injection',    'count' => $stats['sql_injection'],  'color' => 'bg-error',            'icon' => 'database_off'],
                            ['label' => 'Rate Limited',     'count' => $stats['rate_limited'],   'color' => 'bg-secondary',        'icon' => 'speed'],
                        ];
                    @endphp
                    @foreach($threatTypes as $threat)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-secondary">{{ $threat['icon'] }}</span>
                                    <span class="font-label-md text-label-md text-on-surface">{{ $threat['label'] }}</span>
                                </div>
                                <span class="font-label-md text-label-md text-secondary">{{ number_format($threat['count']) }}</span>
                            </div>
                            <div class="w-full bg-surface-container-low rounded-full h-2 overflow-hidden">
                                <div class="{{ $threat['color'] }} h-2 rounded-full transition-all duration-700"
                                     style="width: {{ min(($threat['count'] / $total) * 100, 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Bottom tags --}}
                <div class="mt-4 flex flex-wrap gap-2 relative z-10">
                    @php
                        $topIps = \App\Models\SecurityAuditLog::where('event_type', 'login_failed')
                            ->select('ip_address')
                            ->selectRaw('COUNT(*) as attempt_count')
                            ->groupBy('ip_address')
                            ->orderByDesc('attempt_count')
                            ->limit(3)
                            ->get();
                    @endphp
                    @foreach($topIps as $ip)
                        <div class="flex items-center gap-2 bg-error-container/20 backdrop-blur-md px-sm py-1 rounded-full border border-outline-subtle">
                            <span class="w-2 h-2 rounded-full bg-error"></span>
                            <span class="font-label-md text-label-md text-secondary">{{ $ip->ip_address }} ({{ $ip->attempt_count }}x failed)</span>
                        </div>
                    @endforeach
                    @if($topIps->isEmpty())
                        <div class="flex items-center gap-2 bg-primary-fixed/20 backdrop-blur-md px-sm py-1 rounded-full border border-outline-subtle">
                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                            <span class="font-label-md text-label-md text-secondary">Tidak ada IP mencurigakan</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Security Health Score --}}
            <div class="lg:col-span-4 bg-primary text-on-primary p-lg rounded-DEFAULT soft-elevation relative overflow-hidden flex flex-col justify-between">
                <div class="relative z-10">
                    <h3 class="font-headline-sm text-headline-sm">Security Health</h3>
                    <p class="text-[12px] opacity-80 uppercase tracking-widest mt-1">Overall System Integrity</p>
                </div>
                <div class="relative z-10 flex flex-col items-center py-md">
                    @php
                        // Calculate security score:
                        // Start at 100, deduct for failures
                        $score = 100;
                        if ($stats['total_events'] > 0) {
                            // Deduct up to 30 points for failed login ratio
                            $failRatio = $stats['login_failed'] / max($stats['total_events'], 1);
                            $score -= min(30, round($failRatio * 300));
                            // Deduct 20 points per SQL injection
                            $score -= min(40, $stats['sql_injection'] * 20);
                            // Deduct for rate limited (minor)
                            $score -= min(10, round(($stats['rate_limited'] / max($stats['total_events'], 1)) * 100));
                        }
                        $score = max(0, min(100, $score));

                        // SVG arc calculation
                        $circumference = 2 * 3.14159 * 58; // r=58
                        $offset = $circumference - ($score / 100) * $circumference;

                        // Health label
                        $healthLabel = match(true) {
                            $score >= 90 => 'Stable & Secure',
                            $score >= 70 => 'Moderate Risk',
                            $score >= 50 => 'Elevated Risk',
                            default => 'Critical Alert',
                        };
                    @endphp
                    <div class="relative w-32 h-32 flex items-center justify-center">
                        <svg class="w-full h-full transform -rotate-90">
                            <circle class="opacity-20" cx="64" cy="64" r="58" fill="transparent" stroke="currentColor" stroke-width="8"></circle>
                            <circle cx="64" cy="64" r="58" fill="transparent" stroke="#9ff4ca"
                                stroke-width="8" stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $offset }}"></circle>
                        </svg>
                        <span class="absolute font-display-lg text-display-lg">{{ $score }}</span>
                    </div>
                    <p class="font-label-md text-label-md mt-2 text-primary-fixed">{{ $healthLabel }}</p>
                </div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center text-[12px] opacity-90 border-t border-white/10 pt-2">
                        <span>Latest Scan</span>
                        <span>{{ now()->diffForHumans() }}</span>
                    </div>
                </div>
                {{-- Background decorative element --}}
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>
        </section>

    </div>
</div>
