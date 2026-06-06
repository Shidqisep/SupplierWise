@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Page Title --}}
    <div class="flex items-center justify-between mb-lg">
        <div>
            <h1 class="font-display-lg text-display-lg text-primary">Profile</h1>
            <p class="font-body-md text-body-md text-secondary mt-xs">Manage your account information and security settings.</p>
        </div>

        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-sm px-lg py-md rounded-DEFAULT border border-error text-error hover:bg-error-container transition-colors font-label-md">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Logout
            </button>
        </form>
    </div>

    {{-- Update Profile Info (Breeze Volt form) --}}
    <div class="bg-surface-container-lowest border border-outline-subtle rounded-DEFAULT shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-lg">
        <livewire:profile.update-profile-information-form />
    </div>

    {{-- Update Password (Breeze Volt form) --}}
    <div class="bg-surface-container-lowest border border-outline-subtle rounded-DEFAULT shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-lg">
        <livewire:profile.update-password-form />
    </div>

    {{-- Delete Account (Breeze Volt form) --}}
    <div class="bg-surface-container-lowest border border-outline-subtle rounded-DEFAULT shadow-[0_4px_20px_rgba(15,23,42,0.04)] p-lg">
        <livewire:profile.delete-user-form />
    </div>

</div>
@endsection
