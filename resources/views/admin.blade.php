<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    @livewireStyles
    @fluxAppearance
    <title>Admin | SupplyWise</title>
  </head>
  <body class="bg-slate-50 text-slate-900">
    <div class="min-h-screen">
      <div class="border-b border-slate-200 bg-white py-4 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
              <p class="text-sm uppercase tracking-[0.2em] text-slate-500">SupplyWise Admin</p>
              <h1 class="mt-2 text-3xl font-semibold text-slate-900">Manage suppliers and criteria</h1>
            </div>
            <a href="/" class="rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-700">
              Return to site
            </a>
          </div>
        </div>
      </div>

      <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @livewire('admin-dashboard')
      </main>
    </div>

    @livewireScripts
    @fluxScripts
  </body>
</html>
