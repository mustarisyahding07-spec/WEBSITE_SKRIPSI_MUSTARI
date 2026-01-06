@extends('layouts.app-public')

@section('title', $article->title . ' - UMKM Ivo Karya')

@push('seo')
    <meta name="description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($article->content), 160) }}">
    @if($article->thumbnail)
        <meta property="og:image" content="{{ Storage::url($article->thumbnail) }}">
    @endif
@endpush

@section('content')
<!-- Hero Section for Article -->
<div class="relative bg-black h-[50vh] min-h-[400px] w-full overflow-hidden">
    @if($article->thumbnail)
        <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
    @else
         <div class="absolute inset-0 bg-gray-800 flex items-center justify-center opacity-40"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-black/60"></div>

    <div class="absolute bottom-0 left-0 w-full p-6 md:p-12 pb-16 z-10">
        <div class="max-w-4xl mx-auto">
             <div class="flex items-center space-x-3 mb-4 text-sm font-medium text-brand-400">
                @if($article->category)
                    <span class="bg-brand-900/50 backdrop-blur-sm border border-brand-500/30 px-3 py-1 rounded-full text-brand-300">{{ $article->category->name }}</span>
                @endif
                <span class="text-gray-300">&bull;</span>
                <span class="text-gray-300">{{ $article->created_at->format('d M Y') }}</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-display font-bold text-white leading-tight mb-4 shadow-sm">
                {{ $article->title }}
            </h1>
        </div>
    </div>
</div>

<div class="bg-white min-h-screen relative z-10 -mt-8 rounded-t-3xl border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <article>

            <!-- Content -->
            <div class="p-6 md:p-10">
                <div class="prose prose-lg max-w-none prose-brand text-gray-800">
                    {!! $article->content !!}
                </div>
            </div>

            <!-- Share / Footer -->
            <div class="bg-gray-50 px-6 py-4 md:px-10 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ route('articles.index') }}" class="text-gray-600 hover:text-brand-600 font-medium flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Artikel
                </a>
                
                <div class="flex space-x-3 text-gray-400">
                    <!-- Placeholder share icons -->
                    <span class="text-xs">Bagikan:</span>
                    <a href="#" class="hover:text-brand-500">FB</a>
                    <a href="#" class="hover:text-brand-500">WA</a>
                    <a href="#" class="hover:text-brand-500">TW</a>
                </div>
            </div>
        </article>
    </div>
</div>
@endsection
