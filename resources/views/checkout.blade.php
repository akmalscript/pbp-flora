@extends('layouts.app')

@section('content')
<div class="flex flex-col p-4 max-w-5xl mx-auto">
    <h1 class="font-bold text-4xl p-4">CHECKOUT</h1>
    <div class="flex flex-col md:flex-row md:items-start gap-4 p-4">
        <div class="flex flex-1 flex-col md:flex-[2] gap-4">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <x-checkout.shipping-method />
            </div>

            <div id="shipping-address-container" class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 transition-all duration-500 ease-in-out hidden">
                <x-checkout.shipping-address :provinces="$provinces" />
            </div>

            <div id="courier-options-container"></div>
        </div>

        <div class="flex flex-1 flex-col md:flex-[1] bg-white gap-4 rounded-xl shadow-2xl border border-gray-200 p-4">
            <h1 class='text-2xl font-bold text-gray-800'>Total Pesanan</h1>
            <div>
                @foreach ($orderItems as $item)
                    <x-checkout.order-item :item="$item" />
                @endforeach
            </div>

            <div class="mt-4 p-4 border rounded-lg bg-gray-50">
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-slate-600">Subtotal</span>
                    <span id="subtotal-display" class="font-semibold text-slate-800">{{ 'Rp ' . number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-slate-600">Biaya Pengiriman</span>
                    <span id="shipping-display" class="font-semibold text-slate-800">{{ 'Rp ' . number_format(0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-slate-600">Discount</span>
                    <span id="discount-display" class="font-semibold text-slate-800">{{ $discount > 0 ? '- Rp ' . number_format($discount, 0, ',', '.') : 'Rp 0' }}</span>
                </div>
                <div class="mt-3">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Gift card or discount code</label>
                    <form id="checkout-coupon-form" action="{{ route('user.cart.applyCoupon') }}" method="POST" class="flex gap-3">
                        @csrf
                        <input name="coupon" type="text" placeholder="Enter code"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" autocomplete="off"/>
                        <button type="submit" class="bg-gray-900 text-white px-3 rounded-lg hover:bg-gray-800 transition">Apply</button>
                    </form>
                    @error('coupon')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                    @if (session('coupon_message'))
                        <p class="text-sm text-green-600 mt-2">{{ session('coupon_message') }}</p>
                    @endif
                </div>
                <hr class="my-2" />
                <div class="flex justify-between">
                    <span class="text-lg font-bold">Total</span>
                    <span id="total-display" class="text-lg font-bold text-sky-600">{{ 'Rp ' . number_format($subtotal - $discount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4">
                <label for="order-note" class="block text-sm font-medium text-slate-700 mb-2">Catatan untuk penjual (opsional)</label>
                <textarea id="order-note" name="note" rows="3" placeholder="Contoh: Tolong sertakan kartu ucapan."
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"></textarea>
            </div>

            <hr class="w-4/5 mx-auto border-t border-gray-500 mt-3" />
            @php $payDisabled = ($subtotal <= 0 || empty($orderItems)); @endphp
            <button id="pay-button" type="button" 
                    @if($payDisabled) disabled aria-disabled="true" @endif
                    class="w-full py-2 rounded font-semibold transition {{ $payDisabled ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-yellow-600 text-white' }}">
                Bayar Sekarang
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleShippingAddress(method) {
        const addressContainer = document.getElementById('shipping-address-container');
        if (addressContainer) {
            addressContainer.classList.toggle('hidden', method !== 'Kurir');
        }

        const courierContainer = document.getElementById('courier-options-container');
        if (courierContainer) courierContainer.innerHTML = '';

        const shippingForm = document.getElementById('shipping-address-form');
        if (shippingForm) {
            const sel = shippingForm.querySelector('input[name="selected_courier_service"]');
            if (sel) sel.remove();
        }

        if (method.toLowerCase() === 'pickup') {
            if (typeof window.updateTotals === 'function') window.updateTotals(0);
            if (courierContainer) {
                courierContainer.innerHTML = `<div class="p-4 mt-4 text-center text-slate-700">Anda memilih <strong>Ambil di Toko</strong>. Pengambilan gratis (Rp 0).</div>`;
            }
        } else {
            if (typeof window.updateTotals === 'function') window.updateTotals(0);
        }
    }

    window.addEventListener('load', () => {
        const pickupRadio = document.getElementById('PickUp');
        const kurirRadio = document.getElementById('Kurir');

        if (pickupRadio) {
            pickupRadio.checked = true;
            toggleShippingAddress('PickUp');
        }

        [pickupRadio, kurirRadio].forEach(radio => {
            if (radio) {
                radio.addEventListener('change', () => {
                    toggleShippingAddress(radio.value);
                });
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
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

        const clearCourierSelection = () => {
            if (courierContainer) courierContainer.innerHTML = '';
            if (shippingForm) {
                const sel = shippingForm.querySelector('input[name="selected_courier_service"]');
                if (sel) sel.remove();
            }
            if (typeof updateTotals === 'function') updateTotals(0);
        };

        ['province','city','district','village'].forEach(k => {
            const el = selectors[k];
            if (el) el.addEventListener('change', () => {
                clearCourierSelection();
            });
        });

        const fullAddressEl = document.getElementById('fullAddress');
        const postalCodeEl = document.getElementById('postalCode');
        if (fullAddressEl) fullAddressEl.addEventListener('input', clearCourierSelection);
        if (postalCodeEl) postalCodeEl.addEventListener('input', clearCourierSelection);

        if(shippingForm) {
            shippingForm.reset();
        }

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
        
    function displayCourierOptions(costs) {
            const services = Array.isArray(costs) ? costs : [costs];

            if (!services.length) {
                courierContainer.innerHTML = `<div class="p-4 mt-4 text-center text-slate-600">Tidak ada opsi kurir tersedia.</div>`;
                return;
            }

            let itemsHtml = '';
            services.forEach((cost, idx) => {
                const price = cost.cost && cost.cost[0] ? cost.cost[0].value : 0;
                const etd = cost.cost && cost.cost[0] ? cost.cost[0].etd : '-';
                const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);

                itemsHtml += `
                    <label class="flex items-center p-4 border-2 rounded-lg mb-3 cursor-pointer hover:bg-slate-50">
                        <input type="radio" name="selected_courier" value="${idx}" class="mr-4" ${idx === 0 ? 'checked' : ''} />
                        <div class="flex-grow">
                            <div class="font-bold text-slate-700">${cost.service} (${cost.description})</div>
                            <p class="text-sm text-slate-500">Estimasi tiba dalam ${etd} hari.</p>
                        </div>
                        <div class="ml-4 text-right font-semibold text-slate-800">${formattedPrice}</div>
                    </label>
                `;
            });

            const courierHTML = `
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mt-4 transition-all duration-300 ease-in-out">
                    <h3 class="text-xl font-bold text-slate-800 mb-4">Pilih Jasa Pengiriman</h3>
                    <form id="courier-selection-form">
                        ${itemsHtml}
                        <div class="mt-3 text-right">
                            <button type="button" id="confirm-courier-btn" class="bg-sky-600 text-white font-semibold py-2 px-4 rounded">Pilih</button>
                        </div>
                    </form>
                </div>
            `;

            courierContainer.innerHTML = courierHTML;

            document.getElementById('confirm-courier-btn').addEventListener('click', function () {
                const selectedIdx = document.querySelector('input[name="selected_courier"]:checked').value;
                const selected = services[selectedIdx];

                if (shippingForm) {
                    let el = shippingForm.querySelector('input[name="selected_courier_service"]');
                    if (!el) {
                        el = document.createElement('input');
                        el.type = 'hidden';
                        el.name = 'selected_courier_service';
                        shippingForm.appendChild(el);
                    }
                    el.value = JSON.stringify(selected);

                    courierContainer.innerHTML = `<div class="p-4 mt-4 text-center text-slate-700">Jasa pengiriman <strong>${selected.service}</strong> dipilih. Biaya: <strong>${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(selected.cost[0].value)}</strong></div>`;

                    updateTotals(selected.cost[0].value || 0);
                }
            });
        }

    const subtotalValue = {{ $subtotal ?? 0 }};
        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
        }

        function parseCurrency(str) {
            if (!str) return 0;
            const n = Number(String(str).replace(/[^0-9]/g, ''));
            return isNaN(n) ? 0 : n;
        }

        function updateTotals(shippingCost) {
            const subtotalEl = document.getElementById('subtotal-display');
            const shippingEl = document.getElementById('shipping-display');
            const totalEl = document.getElementById('total-display');
            const discountEl = document.getElementById('discount-display');

            if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotalValue);

            let ship = typeof shippingCost !== 'undefined' ? Number(shippingCost) : (shippingEl ? parseCurrency(shippingEl.textContent) : 0);
            ship = isNaN(ship) ? 0 : ship;

            const dv = Number(window.discountValue || 0);

            if (shippingEl) shippingEl.textContent = formatRupiah(ship || 0);
            if (discountEl) discountEl.textContent = (dv > 0) ? ('- ' + formatRupiah(dv)) : formatRupiah(0);
            if (totalEl) totalEl.textContent = formatRupiah((subtotalValue || 0) + (ship || 0) - (dv || 0));
        }

        window.updateTotals = updateTotals;

        const couponForm = document.getElementById('checkout-coupon-form');
        if (couponForm) {
            couponForm.addEventListener('submit', async function (e) {
                e.preventDefault();
                const fd = new FormData(this);
                try {
                    const res = await fetch(this.action, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    const json = await res.json();
                    if (!res.ok) {
                        const err = json.errors && json.errors.coupon ? json.errors.coupon[0] : 'Invalid coupon';
                        showCouponMessage(err, false);
                        return;
                    }

                    const newDiscount = json.discount || 0;
                    window.discountValue = newDiscount;
                    const discountEl = document.getElementById('discount-display');
                    if (discountEl) discountEl.textContent = '- ' + formatRupiah(newDiscount);
                    updateTotals();

                    showCouponMessage(json.message || 'Coupon applied', true);

                } catch (error) {
                    console.error('Coupon submit error', error);
                    showCouponMessage('Terjadi kesalahan saat memproses kupon', false);
                }
            });
        }

        function showCouponMessage(msg, ok = true) {
            let el = document.getElementById('coupon-msg');
            if (!el) {
                el = document.createElement('div');
                el.id = 'coupon-msg';
                el.className = 'mt-2 text-sm';
                const couponHolder = document.getElementById('checkout-coupon-form');
                if (couponHolder && couponHolder.parentNode) couponHolder.parentNode.appendChild(el);
            }
            el.textContent = msg;
            el.style.color = ok ? '#059669' : '#dc2626';
        }

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

        if(shippingForm) {
            shippingForm.addEventListener('submit', async function(event) {
                event.preventDefault(); 
                
                courierContainer.innerHTML = `<div class="p-4 text-center mt-4">
                    <div class="w-8 h-8 border-4 border-slate-200 border-t-sky-500 rounded-full animate-spin mx-auto mb-3"></div>
                    <p class="font-semibold text-slate-600">Menghitung ongkos kirim...</p>
                </div>`;

                const formData = new FormData(this);
                const methodInput = this.querySelector('input[name="shipping_method"]') || document.querySelector('input[name="shipping_method"]');
                const method = methodInput ? methodInput.value : (document.querySelector('input[name="shipping_method_input"]') ? document.querySelector('input[name="shipping_method_input"]').value : null);
                if (method) formData.append('shipping_method', method);

                if (method && method.toLowerCase && method.toLowerCase() === 'pickup') {
                    courierContainer.innerHTML = `<div class="p-4 mt-4 text-center text-slate-700">Anda memilih <strong>Ambil di Toko</strong>. Pengambilan gratis (Rp 0).</div>`;
                    if (typeof updateTotals === 'function') updateTotals(0);
                    return;
                }

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

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
document.getElementById('pay-button').addEventListener('click', async function() {
    const total = {{ $subtotal - $discount }};
    // collect optional fields: note and shipping info if available (read from shipping-address component)
    const noteEl = document.getElementById('order-note');
    const shippingForm = document.getElementById('shipping-address-form');
    let selectedCourierService = null;
    // prefer checked radio for shipping method, fallback to hidden input used by the component
    let shippingMethod = (document.querySelector('input[name="shipping_method"]:checked') ? document.querySelector('input[name="shipping_method"]:checked').value : null) || (document.getElementById('shipping_method_input') ? document.getElementById('shipping_method_input').value : null);

    // build a single comma-separated address string (fullAddress, village, district, city, province, postalCode)
    let concatenatedAddress = '';
    const selectLabel = (name) => {
        if (!shippingForm) return '';
        const el = shippingForm.querySelector('[name="' + name + '"]');
        if (!el || el.tagName !== 'SELECT') return '';
        if (!el.value) return '';
        const opt = el.options[el.selectedIndex];
        return opt ? (opt.text || '').trim() : '';
    };
    if (shippingForm) {
        const getVal = (name) => {
            const el = shippingForm.querySelector('[name="' + name + '"]');
            return el ? (el.value || '').trim() : '';
        };

        const parts = [];
        const fullAddr = getVal('fullAddress') || (document.getElementById('fullAddress') ? document.getElementById('fullAddress').value : '');
        if (fullAddr) parts.push(fullAddr.trim());

        const villageName = selectLabel('village'); if (villageName) parts.push(villageName);
        const districtName = selectLabel('district'); if (districtName) parts.push(districtName);
        const cityName = selectLabel('city'); if (cityName) parts.push(cityName);
        const provinceName = selectLabel('province'); if (provinceName) parts.push(provinceName);

        const postal = getVal('postalCode') || (document.getElementById('postalCode') ? document.getElementById('postalCode').value : '');
        if (postal) parts.push(postal);

        concatenatedAddress = parts.filter(p => p && String(p).trim() !== '').join(', ');

        const svc = shippingForm.querySelector('input[name="selected_courier_service"]');
        if (svc) {
            try { selectedCourierService = JSON.parse(svc.value); } catch (e) { selectedCourierService = svc.value; }
        }
    }

    const payload = {
        total: total,
        name: '{{ auth()->user()->name }}',
        email: '{{ auth()->user()->email }}',
        phone: '{{ auth()->user()->phone ?? '' }}',
        note: (noteEl && noteEl.value) ? noteEl.value : '',
        address: concatenatedAddress,
        selected_courier_service: selectedCourierService ? JSON.stringify(selectedCourierService) : null,
        shipping_method: shippingMethod
    };

    if (shippingForm) {
        const getVal = (name) => {
            const el = shippingForm.querySelector('[name="' + name + '"]');
            return el ? (el.value || '').trim() : '';
        };

        payload.fullAddress = getVal('fullAddress');
        payload.postalCode = getVal('postalCode');
        payload.province = getVal('province');
        payload.city = getVal('city');
        payload.district = getVal('district');
        payload.village = getVal('village');
        payload.province_label = selectLabel('province');
        payload.city_label = selectLabel('city');
        payload.district_label = selectLabel('district');
        payload.village_label = selectLabel('village');
    }

    try {
        const response = await fetch('{{ route('user.checkout.payment') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!response.ok || !data.snapToken) {
            const errorMessage = data.error || 'Gagal membuat transaksi. Periksa kembali keranjang Anda.';
            if (window.Swal) {
                Swal.fire({
                icon: 'error',
                title: 'Stok Tidak Cukup',
                    text: errorMessage,
                    confirmButtonText: 'Kembali ke Keranjang'
                }).then(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                alert(errorMessage);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            }
            return;
        }

        snap.pay(data.snapToken, {
            onSuccess: function(result){
                console.log('success', result);
                window.location.href = "{{ route('user.checkout.success') }}?order_id=" + encodeURIComponent(data.orderId);
            },
            onPending: function(result){
                console.log('pending', result);
                (window.Swal ? Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Pending',
                    text: 'Silakan selesaikan pembayaran Anda melalui Midtrans.'
                }) : alert('Pembayaran pending, silakan selesaikan di Midtrans'));
            },
            onError: function(result){
                console.log('error', result);
                const msg = result.status_message || 'Pembayaran gagal diproses.';
                window.Swal ? Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: msg
                }) : alert('Pembayaran gagal: ' + msg);
            },
            onClose: function(){
                window.Swal ? Swal.fire({
                    icon: 'warning',
                    title: 'Pembayaran Dibatalkan',
                    text: 'Anda menutup popup pembayaran tanpa menyelesaikannya.'
                }) : alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran');
            }
        });
    } catch (error) {
        console.error('Checkout error:', error);
        window.Swal ? Swal.fire({
            icon: 'error',
            title: 'Kesalahan Sistem',
            text: 'Terjadi kesalahan saat memproses checkout. Silakan coba lagi.'
        }) : alert('Terjadi kesalahan saat memproses checkout. Silakan coba lagi.');
    }
});
</script>

@endpush
