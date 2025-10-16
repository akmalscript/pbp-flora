@extends('layouts.app')

@section('content')
<div class="flex flex-col p-4 max-w-5xl mx-auto">
    <h1 class="font-bold text-4xl p-4">CHECKOUT</h1>
    <div class="flex flex-col md:flex-row md:items-start gap-4 p-4">
        {{-- Kolom Kiri --}}
        <div class="flex flex-1 flex-col md:flex-[2] gap-4">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <x-checkout.shipping-method />
            </div>

            <div id="shipping-address-container" class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 transition-all duration-500 ease-in-out hidden">
                <x-checkout.shipping-address :provinces="$provinces" />
            </div>

            <div id="courier-options-container"></div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="flex flex-1 flex-col md:flex-[1] bg-white gap-4 rounded-xl shadow-2xl border border-gray-200 p-4">
            <h1 class='text-2xl font-bold text-gray-800'>Total Pesanan</h1>
            <div>
                @foreach ($orderItems as $item)
                    <x-checkout.order-item :item="$item" />
                @endforeach
            </div>
            <hr class="w-4/5 mx-auto border-t border-gray-500 mt-3" />
            <button type="submit" form="shipping-address-form" class="bg-yellow-600 text-white font-semibold py-2 rounded w-[240px] mx-auto block hover:bg-yellow-700">
                Bayar
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fungsi ini ditempatkan di luar agar bisa diakses oleh onchange dari komponen Blade
    function toggleShippingAddress(method) {
        const addressContainer = document.getElementById('shipping-address-container');
        if (addressContainer) {
            addressContainer.classList.toggle('hidden', method !== 'Kurir');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // === KONFIGURASI & SELEKSI ELEMEN ===
        const wilayahApiBaseUrl = "{{ url('/api/wilayah') }}";
        const calculateShippingUrl = "{{ route('user.checkout.calculate') }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const shippingForm = document.getElementById('shipping-address-form');
        const courierContainer = document.getElementById('courier-options-container');
        
        const selectors = {
            province: document.getElementById('province'),
            city: document.getElementById('city'),
            district: document.getElementById('district'),
            village: document.getElementById('village')
        };

        // Memaksa form untuk selalu kosong saat halaman dimuat
        if(shippingForm) {
            shippingForm.reset();
        }

        // === FUNGSI-FUNGSI BANTU (HELPERS) ===

        const toggleSpinner = (selectId, show) => {
            const spinner = document.getElementById(`${selectId}-spinner`);
            if (spinner) spinner.style.display = show ? 'block' : 'none';
        };

        const updateSelectOptions = (selectElement, data, placeholder) => {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`;
            data.forEach(item => {
                selectElement.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            selectElement.disabled = !data.length;
        };
        
        const resetSelects = (startingFrom) => {
            const levels = ['city', 'district', 'village'];
            const placeholders = {'city': 'Pilih Kota/Kabupaten', 'district': 'Pilih Kecamatan', 'village': 'Pilih Kelurahan'};
            const startIndex = levels.indexOf(startingFrom);
            
            if (startIndex !== -1) {
                for (let i = startIndex; i < levels.length; i++) {
                    const level = levels[i];
                    selectors[level].innerHTML = `<option value="">${placeholders[level]}</option>`;
                    selectors[level].disabled = true;
                }
            }
        };

        const fetchData = async (endpoint, selectElement, placeholder) => {
            try {
                const response = await fetch(endpoint);
                if (!response.ok) throw new Error(`Network response was not ok: ${response.statusText}`);
                const data = await response.json();
                updateSelectOptions(selectElement, data, placeholder);
            } catch (error) {
                console.error(`Fetch error for ${endpoint}:`, error);
            }
        };
        
        function displayCourierOptions(cost) {
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(cost.cost[0].value);
            
            const courierHTML = `
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mt-4 transition-all duration-300 ease-in-out">
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Pilih Jasa Pengiriman</h3>
                    <div class="flex items-center p-4 border-2 rounded-lg border-sky-500 ring-2 ring-sky-200">
                        <div class="flex-shrink-0 mr-4">
                            <img src="{{ asset('images/jne.png') }}" alt="JNE Logo" class="h-6" /> 
                        </div>
                        <div class="flex-grow">
                            <span class="font-bold text-slate-700">${cost.service} (${cost.description})</span>
                            <p class="text-sm text-slate-500">Estimasi tiba dalam ${cost.cost[0].etd} hari.</p>
                        </div>
                        <div class="flex-shrink-0 ml-4 text-right">
                            <span class="font-semibold text-slate-800">${formattedPrice}</span>
                        </div>
                    </div>
                </div>
            `;
            courierContainer.innerHTML = courierHTML;
        }

        // === EVENT LISTENERS ===

        // Event listener untuk dropdown wilayah
        selectors.province.addEventListener('change', async function () {
            resetSelects('city');
            if (this.value) {
                toggleSpinner('city', true);
                await fetchData(`${wilayahApiBaseUrl}/cities/${this.value}`, selectors.city, 'Pilih Kota/Kabupaten');
                toggleSpinner('city', false);
            }
        });

        selectors.city.addEventListener('change', async function () {
            resetSelects('district');
            if (this.value) {
                toggleSpinner('district', true);
                await fetchData(`${wilayahApiBaseUrl}/districts/${this.value}`, selectors.district, 'Pilih Kecamatan');
                toggleSpinner('district', false);
            }
        });
        
        selectors.district.addEventListener('change', async function () {
            resetSelects('village');
            if (this.value) {
                toggleSpinner('village', true);
                await fetchData(`${wilayahApiBaseUrl}/villages/${this.value}`, selectors.village, 'Pilih Kelurahan');
                toggleSpinner('village', false);
            }
        });

        // Event listener untuk form submit (AJAX)
        if(shippingForm) {
            shippingForm.addEventListener('submit', async function(event) {
                event.preventDefault(); 
                
                courierContainer.innerHTML = `<div class="p-4 text-center mt-4">
                    <div class="w-8 h-8 border-4 border-slate-200 border-t-sky-500 rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="font-semibold text-slate-600">Menghitung ongkos kirim...</p>
                </div>`;

                const formData = new FormData(this);
                formData.append('shipping_method', 'Kurir');

                try {
                    const response = await fetch(calculateShippingUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        const errors = Object.values(result.errors).map(e => `<li>${e[0]}</li>`).join('');
                        courierContainer.innerHTML = `<div class="p-4 mt-4 text-red-700 bg-red-100 rounded-lg"><strong>Error:</strong><ul>${errors}</ul></div>`;
                        return;
                    }

                    displayCourierOptions(result.cost);

                } catch (error) {
                    console.error('Submit error:', error);
                    courierContainer.innerHTML = `<div class="p-4 mt-4 text-center text-red-600">Terjadi kesalahan. Silakan coba lagi.</div>`;
                }
            });
        }
    });
</script>
@endpush

