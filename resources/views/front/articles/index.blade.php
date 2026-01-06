@extends('layouts.app-public')

@section('content')
<div class="relative bg-black pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('img/premium/texture.jpg') }}" alt="Background" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/40 to-gray-50"></div>
    </div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <span class="text-brand-500 font-bold tracking-widest text-sm uppercase mb-2 block">Blog & Berita</span>
        <h1 class="text-4xl md:text-5xl font-display font-bold text-white mb-4">Edukasi & Seputar Abon</h1>
        <p class="text-gray-300 max-w-2xl mx-auto">Temukan informasi menarik, resep, dan tips seputar abon ikan dan sapi.</p>
    </div>
</div>

<div class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles as $article)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300 border border-gray-100 overflow-hidden flex flex-col h-full">
                    <a href="{{ route('articles.show', $article->slug) }}" class="block relative h-48 overflow-hidden bg-gray-100">
                        @if($article->thumbnail)
                            <img src="{{ Storage::url($article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                        @endif
                    </a>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center text-xs text-gray-500 mb-2 space-x-2">
                             @if($article->category)
                                <span class="bg-gray-100 px-2 py-1 rounded text-brand-600 font-medium">{{ $article->category->name }}</span>
                             @endif
                             <span>&bull;</span>
                             <span>{{ $article->created_at->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">
                            <a href="{{ route('articles.show', $article->slug) }}" class="hover:text-brand-500 transition-colors">
                                {{ $article->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3 flex-1">
                            {{ Str::limit(strip_tags($article->content), 120) }}
                        </p>
                        <a href="{{ route('articles.show', $article->slug) }}" class="inline-flex items-center text-brand-600 hover:text-brand-700 font-medium whitespace-nowrap">
                            Baca Selengkapnya
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
