<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} - SPK Stunting</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="bg-[#e9f3ee] text-slate-800 antialiased min-h-screen flex items-center justify-center p-3 sm:p-6 lg:p-8 relative selection:bg-emerald-600 selection:text-white overflow-x-hidden">
    <!-- Ambient Organic Background Blobs matching Mockup -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full bg-emerald-200/35 blur-3xl"></div>
        <div class="absolute -bottom-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-teal-200/30 blur-3xl"></div>
        <div class="absolute top-[40%] -left-[15%] w-[40vw] h-[40vw] rounded-full bg-white/50 blur-2xl"></div>
    </div>

    {{ $slot }}
    @livewireScripts
</body>
</html>
