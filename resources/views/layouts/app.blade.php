<html class="light" lang="id">
<head>
    @include('layouts.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-surface text-on-surface">

    <x-sidebar />
    <x-topbar />

    <main class="ml-64 pt-24 px-8 pb-8">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    @include('layouts.modals')

    @include('layouts.toast')

    @include('layouts.scripts')

</body>
</html>