@extends('layouts.app')

@section('title', 'Server Error (500)')

@section('content')
<main class="min-h-screen flex items-center justify-center py-20">
    <div class="max-w-md w-full mx-auto px-4 text-center space-y-6">
        
        <div class="relative inline-block">
            <div class="text-[90px] font-serif font-bold leading-none text-mosh-gold/20 select-none animate-pulse">
                500
            </div>
            <div class="absolute inset-0 flex items-center justify-center text-mosh-gold">
                <i class="mdi mdi-alert-octagon-outline text-4xl"></i>
            </div>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl font-serif font-bold tracking-tight id-heading-main text-white uppercase">
                Kitchen Disruption
            </h1>
            <p class="text-xs text-gray-400 leading-relaxed max-w-sm mx-auto id-text-body">
                Something unexpected broke on our server infrastructure while prepping this page. Our team has been logged and notified to fix the recipe layout.
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="w-full sm:w-auto bg-mosh-gold hover:bg-opacity-90 text-white font-bold text-[11px] uppercase tracking-wider px-5 py-2.5 rounded transition">
                <i class="mdi mdi-refresh mr-1"></i> Retry Page
            </button>
            
            <a href="{{ url('/') }}" class="w-full sm:w-auto border border-gray-500/20 hover:border-mosh-gold/5 bg-black/10 dark:bg-black/30 text-gray-300 id-text-body font-bold text-[11px] uppercase tracking-wider px-5 py-2.5 rounded transition">
                Return Home
            </a>
        </div>

    </div>
</main>
@endsection