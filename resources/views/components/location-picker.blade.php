{{-- Location Picker Component with Leaflet Map --}}
@props(['name' => 'location'])

<div x-data="locationPicker()" class="space-y-4">
    {{-- GPS Prompt --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-amber-700 font-semibold text-sm">Pastikan GPS / Lokasi Anda Aktif</p>
            <p class="text-amber-600 text-xs mt-1">Anda wajib mengaktifkan GPS untuk melanjutkan pengiriman data</p>
        </div>
    </div>

    {{-- Postal Code Input --}}
    <div>
        <label for="postal_code" class="block text-sm font-bold text-gray-700 mb-2">Kode Pos <span class="text-red-500">*</span></label>
        <div class="flex gap-2">
            <input 
                type="text" 
                id="postal_code" 
                name="postal_code"
                x-model="postalCode"
                @input.debounce.500ms="searchByPostalCode"
                placeholder="Masukkan kode pos (5 digit)"
                maxlength="5"
                class="flex-1 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-brand-500 focus:border-brand-500 block p-3.5 transition-all outline-none"
                required
            >
            <button 
                type="button"
                @click="getCurrentLocation"
                class="px-4 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition flex items-center gap-2"
                :disabled="isLoadingLocation"
            >
                <svg x-show="!isLoadingLocation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <svg x-show="isLoadingLocation" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="hidden sm:inline" x-text="isLoadingLocation ? 'Mencari...' : 'GPS'"></span>
            </button>
        </div>
    </div>

    {{-- Address Display --}}
    <div x-show="addressDisplay" x-cloak class="bg-gray-50 rounded-xl p-4 border border-gray-200">
        <p class="text-gray-700 text-sm" x-text="addressDisplay"></p>
    </div>

    {{-- Map Container --}}
    <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm">
        <div id="location-map" class="h-64 w-full bg-gray-100"></div>
    </div>
    <p class="text-xs text-gray-500 text-center">Klik pada peta untuk menandai lokasi pengiriman</p>

    {{-- Hidden inputs --}}
    <input type="hidden" name="latitude" x-model="latitude">
    <input type="hidden" name="longitude" x-model="longitude">
    <input type="hidden" name="destination_city_id" x-model="cityId">
    <input type="hidden" name="destination_city_name" x-model="cityName">

    {{-- Shipping Options --}}
    <div x-show="shippingOptions.length > 0" x-cloak class="space-y-3">
        <label class="block text-sm font-bold text-gray-700">🚚 Pilih Kurir</label>
        <div class="space-y-2 max-h-48 overflow-y-auto">
            <template x-for="(option, index) in shippingOptions" :key="index">
                <label 
                    class="flex items-center justify-between p-4 bg-white border rounded-xl cursor-pointer hover:border-brand-500 transition"
                    :class="selectedShipping === index ? 'border-brand-500 bg-brand-50' : 'border-gray-200'"
                >
                    <div class="flex items-center gap-3">
                        <input 
                            type="radio" 
                            name="shipping_option" 
                            :value="index"
                            x-model="selectedShipping"
                            @change="selectShipping(index)"
                            class="w-4 h-4 text-brand-600 border-gray-300 focus:ring-brand-500"
                        >
                        <div>
                            <p class="font-semibold text-gray-900" x-text="option.courier + ' ' + option.service"></p>
                            <p class="text-xs text-gray-500" x-text="option.description + ' (' + option.etd + ' hari)'"></p>
                        </div>
                    </div>
                    <span class="font-bold text-brand-600" x-text="'Rp ' + formatNumber(option.cost)"></span>
                </label>
            </template>
        </div>
    </div>

    {{-- Loading State --}}
    <div x-show="isLoadingShipping" x-cloak class="text-center py-4">
        <svg class="w-8 h-8 animate-spin mx-auto text-brand-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-sm text-gray-500 mt-2">Menghitung ongkir...</p>
    </div>

    {{-- Error Message --}}
    <div x-show="errorMessage" x-cloak class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm">
        <span x-text="errorMessage"></span>
    </div>

    {{-- Hidden shipping inputs for form submission --}}
    <input type="hidden" name="courier" x-model="selectedCourier">
    <input type="hidden" name="courier_service" x-model="selectedService">
    <input type="hidden" name="shipping_cost" x-model="shippingCost">
</div>

{{-- Leaflet CSS --}}
@once
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
@endonce

{{-- Leaflet JS & Alpine Component --}}
@once
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
function locationPicker() {
    return {
        map: null,
        marker: null,
        postalCode: '',
        latitude: '',
        longitude: '',
        cityId: '',
        cityName: '',
        addressDisplay: '',
        shippingOptions: [],
        selectedShipping: null,
        selectedCourier: '',
        selectedService: '',
        shippingCost: 0,
        isLoadingLocation: false,
        isLoadingShipping: false,
        errorMessage: '',
        totalWeight: {{ $totalWeight ?? 1000 }}, // grams

        init() {
            this.$nextTick(() => {
                this.initMap();
            });
        },

        initMap() {
            // Default to Sidenreng Rappang
            const defaultLat = -3.9278;
            const defaultLng = 120.0167;

            this.map = L.map('location-map').setView([defaultLat, defaultLng], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(this.map);

            // Click to place marker
            this.map.on('click', (e) => {
                this.placeMarker(e.latlng.lat, e.latlng.lng);
                this.reverseGeocode(e.latlng.lat, e.latlng.lng);
            });
        },

        placeMarker(lat, lng) {
            if (this.marker) {
                this.marker.setLatLng([lat, lng]);
            } else {
                this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                this.marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.reverseGeocode(pos.lat, pos.lng);
                });
            }
            this.latitude = lat;
            this.longitude = lng;
            this.map.setView([lat, lng], 15);
        },

        getCurrentLocation() {
            if (!navigator.geolocation) {
                this.errorMessage = 'Browser Anda tidak mendukung GPS';
                return;
            }

            this.isLoadingLocation = true;
            this.errorMessage = '';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    this.placeMarker(lat, lng);
                    this.reverseGeocode(lat, lng);
                    this.isLoadingLocation = false;
                },
                (error) => {
                    this.isLoadingLocation = false;
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            this.errorMessage = 'Izin lokasi ditolak. Aktifkan GPS di pengaturan browser.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            this.errorMessage = 'Lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            this.errorMessage = 'Waktu habis saat mencari lokasi.';
                            break;
                    }
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        },

        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch('/api/shipping/geocode', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ lat, lng })
                });

                const result = await response.json();
                if (result.success) {
                    this.postalCode = result.data.postal_code || '';
                    this.addressDisplay = result.data.display_name || '';
                    
                    if (this.postalCode) {
                        this.searchByPostalCode();
                    }
                }
            } catch (error) {
                console.error('Geocode error:', error);
            }
        },

        async searchByPostalCode() {
            if (this.postalCode.length !== 5) return;

            this.errorMessage = '';
            this.isLoadingShipping = true;
            this.shippingOptions = [];

            try {
                // Find city by postal code
                const cityResponse = await fetch('/api/shipping/find-city', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ postal_code: this.postalCode })
                });

                const cityResult = await cityResponse.json();
                
                if (!cityResult.success) {
                    this.errorMessage = cityResult.message || 'Kota tidak ditemukan';
                    this.isLoadingShipping = false;
                    return;
                }

                this.cityId = cityResult.data.city_id;
                this.cityName = cityResult.data.city_name + ', ' + cityResult.data.province;
                this.addressDisplay = this.cityName;

                // Calculate shipping cost
                const costResponse = await fetch('/api/shipping/cost', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        destination_city_id: this.cityId,
                        weight: this.totalWeight
                    })
                });

                const costResult = await costResponse.json();
                
                if (costResult.success && costResult.data.length > 0) {
                    this.shippingOptions = costResult.data;
                } else {
                    this.errorMessage = 'Tidak ada layanan pengiriman ke lokasi ini';
                }
            } catch (error) {
                this.errorMessage = 'Gagal menghitung ongkir: ' + error.message;
            } finally {
                this.isLoadingShipping = false;
            }
        },

        selectShipping(index) {
            const option = this.shippingOptions[index];
            this.selectedCourier = option.courier;
            this.selectedService = option.service;
            this.shippingCost = option.cost;

            // Dispatch event for parent to update total
            this.$dispatch('shipping-selected', {
                courier: option.courier,
                service: option.service,
                cost: option.cost
            });
        },

        formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endpush
@endonce
