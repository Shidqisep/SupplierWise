@php
    $current = Route::currentRouteName();
@endphp
{{-- ============================================================ --}}
{{-- SIDEBAR NAVIGATION --}}
{{-- ============================================================ --}}

<aside class="h-screen w-64 fixed left-0 top-0 flex flex-col bg-surface-container-low border-r border-outline-subtle z-40">
    
    <div class="p-md flex flex-col gap-sm h-full">

        {{-- Logo --}}
        <div class="flex items-center gap-md mb-xl">
            <div class="w-10 h-10 rounded-DEFAULT bg-primary flex items-center justify-center text-on-primary">
                <span class="material-symbols-outlined">inventory_2</span>
            </div>
            <div>
                <h1 class="font-headline-sm text-headline-sm font-black text-primary">SuppleWise</h1>
                <p class="font-label-md text-label-md text-secondary">Supply Chain Intelligence</p>
            </div>
        </div>

        {{-- Nav items --}}
        <nav class="flex flex-col gap-sm flex-grow" id="sidebar-nav">
            <a href="{{ route('dashboard') }}"
   class="flex items-center gap-md px-md py-sm rounded-DEFAULT transition-all duration-200
   {{ $current == 'dashboard'
        ? 'bg-primary text-on-primary'
        : 'text-secondary hover:bg-surface-container-high' }}">
    <span class="material-symbols-outlined">dashboard</span>
    <span class="font-label-md text-label-md">Dashboard</span>
</a>
            <a href="{{ route('suppliers') }}"
   class="flex items-center gap-md px-md py-sm rounded-DEFAULT transition-all duration-200
   {{ $current == 'suppliers'
        ? 'bg-primary text-on-primary'
        : 'text-secondary hover:bg-surface-container-high' }}">
    <span class="material-symbols-outlined">inventory_2</span>
    <span class="font-label-md text-label-md">Suppliers</span>
</a>
            <a href="{{ route('criteria') }}"
   class="flex items-center gap-md px-md py-sm rounded-DEFAULT transition-all duration-200
   {{ $current == 'criteria'
        ? 'bg-primary text-on-primary'
        : 'text-secondary hover:bg-surface-container-high' }}">
    <span class="material-symbols-outlined">rule</span>
    <span class="font-label-md text-label-md">Criteria</span>
</a>
            <a href="{{ route('results') }}"
   class="flex items-center gap-md px-md py-sm rounded-DEFAULT transition-all duration-200
   {{ $current == 'results'
        ? 'bg-primary text-on-primary'
        : 'text-secondary hover:bg-surface-container-high' }}">
    <span class="material-symbols-outlined">analytics</span>
    <span class="font-label-md text-label-md">Results</span>
</a>
        </nav>

        {{-- Bottom nav --}}
        <div class="flex flex-col gap-sm mt-auto border-t border-outline-subtle pt-md">
            <div class="text-secondary flex items-center gap-md px-md py-sm hover:bg-surface-container-high cursor-pointer transition-all duration-200 rounded-DEFAULT">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-label-md text-label-md">Settings</span>
            </div>
            <div class="text-secondary flex items-center gap-md px-md py-sm hover:bg-surface-container-high cursor-pointer transition-all duration-200 rounded-DEFAULT">
                <span class="material-symbols-outlined">help</span>
                <span class="font-label-md text-label-md">Support</span>
            </div>
        </div>
    </div>
</aside>

