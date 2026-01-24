<div x-data="chatbot()" x-cloak class="fixed bottom-5 right-5 z-50 flex flex-col items-end font-sans">
    
    <!-- Chat Window -->
    <div x-show="isOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="bg-white w-80 sm:w-96 rounded-2xl shadow-2xl mb-4 overflow-hidden border border-gray-100 flex flex-col pointer-events-auto">
        
        <!-- Header -->
        <div class="bg-brand-600 p-4 flex justify-between items-center shadow-md">
            <div class="flex items-center space-x-3">
                <div class="bg-white p-1.5 rounded-full relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h3 class="font-bold text-white text-base">Ivo Karya Bot</h3>
                    <p class="text-brand-100 text-xs flex items-center">
                        <span class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                        Online
                    </p>
                </div>
            </div>
            <button @click="isOpen = false" class="text-brand-100 hover:text-white transition focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div class="h-80 overflow-y-auto p-4 bg-gray-50 flex flex-col space-y-3" x-ref="chatContainer">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.sender === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div :class="msg.sender === 'user' ? 'bg-brand-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none'" 
                         class="max-w-[80%] rounded-2xl px-4 py-2.5 shadow-sm text-sm leading-relaxed relative group">
                         <p x-html="msg.text"></p>
                         <p class="text-[10px] mt-1 opacity-70" :class="msg.sender === 'user' ? 'text-brand-100 text-right' : 'text-gray-400'" x-text="msg.time"></p>
                    </div>
                </div>
            </template>
            <div x-show="isTyping" class="flex justify-start animate-fade-in-up">
                <div class="bg-white border border-gray-200 p-3 rounded-2xl rounded-bl-none shadow-sm flex space-x-1 items-center">
                   <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                   <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                   <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="px-3 pb-2 bg-gray-50 flex space-x-2 overflow-x-auto no-scrollbar scroll-smooth" x-show="messages.length < 5">
             <button @click="sendMessage('Lihat Produk')" class="flex-shrink-0 bg-white border border-brand-200 text-brand-600 text-xs px-3 py-1.5 rounded-full hover:bg-brand-50 transition shadow-sm">🛍️ Lihat Produk</button>
             <button @click="sendMessage('Cek Ongkir')" class="flex-shrink-0 bg-white border border-brand-200 text-brand-600 text-xs px-3 py-1.5 rounded-full hover:bg-brand-50 transition shadow-sm">🚚 Cek Ongkir</button>
             <button @click="sendMessage('Kontak WhatsApp')" class="flex-shrink-0 bg-white border border-brand-200 text-brand-600 text-xs px-3 py-1.5 rounded-full hover:bg-brand-50 transition shadow-sm">📞 Kontak Admin</button>
        </div>

        <!-- Input Area -->
        <div class="p-3 bg-white border-t border-gray-100">
            <form @submit.prevent="handleSend" class="flex items-center space-x-2">
                <input type="text" x-model="userInput" placeholder="Tulis pesan..." 
                       class="flex-1 bg-gray-100 text-gray-800 text-sm border-0 rounded-full px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:bg-white transition placeholder-gray-400">
                <button type="submit" :disabled="!userInput.trim()" 
                        class="bg-brand-600 text-white p-2.5 rounded-full shadow-md hover:bg-brand-600 transition disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95 flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Toggle Button -->
    <button @click="isOpen = !isOpen" 
            :class="isOpen ? 'bg-red-500 rotate-90' : 'bg-brand-600 rotate-0'"
            class="p-4 rounded-full shadow-xl text-white hover:shadow-2xl hover:scale-105 transition-all duration-300 transform flex items-center justify-center relative group">
        
        <!-- Icon Switch -->
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>

        <!-- Notification Badge -->
        <span x-show="!isOpen && hasUnread" class="absolute top-0 right-0 -mt-1 -mr-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white animate-ping"></span>
        <span x-show="!isOpen && hasUnread" class="absolute top-0 right-0 -mt-1 -mr-1 w-4 h-4 bg-red-500 rounded-full border-2 border-white"></span>
        
        <!-- Tooltip -->
        <span x-show="!isOpen" class="absolute right-full mr-3 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap pointer-events-none">
            Butuh bantuan?
        </span>
    </button>
</div>

<script>
    function chatbot() {
        return {
            isOpen: false,
            userInput: '',
            messages: [
                { text: 'Halo! Selamat datang di <b>Ivo Karya</b>. 👋<br>Ada yang bisa saya bantu?', sender: 'bot', time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }
            ],
            isTyping: false,
            hasUnread: true,

            init() {
                this.$watch('isOpen', value => {
                    if (value) {
                        this.hasUnread = false;
                        this.scrollToBottom();
                    }
                });
            },

            handleSend() {
                if (this.userInput.trim()) {
                    this.sendMessage(this.userInput);
                    this.userInput = '';
                }
            },

            sendMessage(text) {
                // Add User Message
                this.messages.push({ 
                    text: text, 
                    sender: 'user', 
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) 
                });
                
                this.scrollToBottom();
                this.isTyping = true;

                // Simple Bot Logic
                setTimeout(() => {
                    let reply = this.getBotReply(text.toLowerCase());
                    this.messages.push({ 
                        text: reply, 
                        sender: 'bot', 
                        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) 
                    });
                    this.isTyping = false;
                    this.scrollToBottom();
                }, 1000 + Math.random() * 500); // Realistic delay
            },

            getBotReply(input) {
                // Keyword Matching
                if (input.includes('harga') || input.includes('berapa')) {
                    return 'Harga Abon Ikan mulai dari <b>Rp 25.000</b> dan Abon Sapi mulai dari <b>Rp 35.000</b>. Cek katalog kami untuk detailnya! 📝';
                }
                if (input.includes('lokasi') || input.includes('alamat') || input.includes('toko') || input.includes('dimana')) {
                    return 'Kami berlokasi di <b>Sidenreng Rappang (Sidrap)</b>, Sulawesi Selatan. 📍<br>Bisa kirim ke seluruh Indonesia lho!';
                }
                if (input.includes('ongkir') || input.includes('kirim')) {
                    return 'Ongkir tergantung kecamatan tujuan. Kami pakai JNE, J&T, dan Pos Indonesia. Silakan coba checkout untuk cek ongkir otomatis, atau tanya Admin via WA. 🚚';
                }
                if (input.includes('produk') || input.includes('jual') || input.includes('menu')) {
                    return 'Produk andalan kami:<br>1. Abon Ikan Tuna 🐟<br>2. Abon Sapi Original 🐮<br>3. Abon Sapi Pedas 🔥<br><br>Mau pesan yang mana?';
                }
                if (input.includes('wa') || input.includes('whatsapp') || input.includes('admin') || input.includes('kontak')) {
                    return 'Boleh banget! Klik tombol ini untuk chat langsung dengan Admin: <br><a href="https://wa.me/6281234567890" target="_blank" class="inline-block mt-2 bg-green-500 text-white text-xs px-3 py-1 rounded-full hover:bg-green-600 transition">Chat via WhatsApp 💬</a>';
                }
                if (input.includes('terima kasih') || input.includes('makasih') || input.includes('thanks')) {
                    return 'Sama-sama! Senang bisa membantu. 😊';
                }
                
                // Fallback
                return 'Maaf, saya kurang paham. 🤔<br>Tapi Kakak bisa tanya langsung ke Admin kami via WhatsApp ya.<br><a href="https://wa.me/6281234567890" target="_blank" class="text-brand-600 underline font-semibold">Hubungi Admin di sini</a>.';
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = this.$refs.chatContainer;
                    container.scrollTop = container.scrollHeight;
                });
            }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
