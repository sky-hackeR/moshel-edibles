@extends('layouts.app')

@section('title', 'Page Under Construction')

@section('content')
<div class="container mx-auto px-6 py-24 flex items-center justify-center min-h-[60vh]">
    <div class="glass-card max-w-lg w-full text-center p-10 rounded-2xl border theme-transition">
        <!-- Graphic Indicator -->
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-mosh-gold/10 border border-mosh-gold/30 mb-6 animate-pulse">
            <i class="mdi mdi-hammer-wrench text-2xl text-mosh-gold"></i>
        </div>

        <!-- Warning Typography -->
        <h1 class="text-3xl font-serif font-medium text-white id-heading-main mb-3 tracking-tight">
            Kitchen Station Active
        </h1>
        <p class="text-sm text-gray-400 id-text-body mb-8 max-w-sm mx-auto leading-relaxed">
            This module (including user spaces and order configurations) is undergoing formulation. Our developers are fine-tuning the code recipes.
        </p>

        <!-- Navigation Recovery Switch -->
        <div class="space-y-3">
            <a href="{{ url('/') }}" class="inline-block w-full bg-mosh-gold hover:bg-opacity-95 text-white font-bold text-xs uppercase tracking-widest py-3.5 rounded-lg shadow transition">
                Return to Front Counter
            </a>
            <a href="{{ url('/products') }}" class="inline-block w-full border border-gray-400/30 dark:border-gray-800 text-gray-700 dark:text-gray-400 id-text-body font-bold text-xs uppercase tracking-widest py-3.5 rounded-lg transition hover:bg-gray-500/5">
                View Available Sweets
            </a>
        </div>
    </div>
</div>
@endsection