<div>
    <div class="space-y-8">
        <!-- Review Statistics -->
        <div class="flex items-center space-x-4 mb-8">
            <div class="flex items-center">
                <span class="text-4xl font-bold text-gray-900 mr-2">
                    {{ number_format($reviews->avg('rating') ?? 0, 1) }}
                </span>
                <div class="flex text-yellow-400">
                     @for($i=1; $i<=5; $i++)
                        <svg class="w-6 h-6 {{ ($reviews->avg('rating') ?? 0) >= $i ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                     @endfor
                </div>
            </div>
            <span class="text-gray-500">
                Berdasarkan {{ $reviews->count() }} ulasan
            </span>
        </div>

        <!-- Review Form -->
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Tulis Ulasan</h3>
            
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="submit">
                @guest
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Nama Anda
                    </label>
                    <input wire:model="name" type="text" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="Masukkan nama Anda">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                @endguest

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                    <div class="flex space-x-2">
                            @for($i=1; $i<=5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none transition-transform hover:scale-110">
                                <svg class="w-8 h-8 {{ $rating >= $i ? 'text-yellow-400 fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                            @endfor
                    </div>
                    @error('rating') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="comment">
                        Ulasan Anda
                    </label>
                    <textarea wire:model="comment" id="comment" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-brand-500" placeholder="Ceritakan pengalaman Anda..."></textarea>
                    @error('comment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-brand-500 hover:bg-brand-600 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 transform hover:-translate-y-1">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>

        <!-- Review List -->
        <div class="space-y-6">
            @forelse($reviews as $review)
                <div class="border-b border-gray-200 pb-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-3 uppercase">
                                {{ substr($review->customer_name ?? $review->user->name ?? 'A', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $review->customer_name ?? $review->user->name ?? 'Anonymous' }}</h4>
                                <div class="flex text-yellow-400 text-xs">
                                        @for($i=1; $i<=5; $i++)
                                        <svg class="w-3 h-3 {{ $review->rating >= $i ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $review->comment }}
                    </p>
                </div>
            @empty
                <div class="text-center py-10 text-gray-500">
                    Belum ada ulasan untuk produk ini. Jadilah yang pertama memberikan ulasan!
                </div>
            @endforelse
        </div>
    </div>
</div>
    </div>
</div>
