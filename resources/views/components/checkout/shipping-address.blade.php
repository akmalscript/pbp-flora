@props(['provinces' => []])

<form id="shipping-address-form" action="{{ route('user.checkout.calculate') }}" method="POST" autocomplete="off">
    @csrf
    <input type="hidden" name="shipping_method" id="shipping_method_input" value="PickUp" />
    <h3 class="text-xl font-bold text-slate-800 mb-4">Alamat Pengiriman</h3>
    <div class="space-y-4">

        <div class="relative">
            <label for="province" class="block text-sm font-semibold text-slate-600 mb-1">Provinsi</label>
            @include('components.checkout.icons.map-pin')
            <select id="province" name="province" required class="w-full pl-10 pr-10 p-3 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 appearance-none disabled:bg-slate-100">
                <option value="">Pilih Provinsi</option>
                @foreach ($provinces as $province)
                    <option value="{{ $province['id'] }}">
                        {{ $province['name'] }}
                    </option>
                @endforeach
            </select>
            <div id="province-spinner" style="display: none;">@include('components.checkout.icons.loading-spinner')</div>
        </div>

        <div class="relative">
            <label for="city" class="block text-sm font-semibold text-slate-600 mb-1">Kota/Kabupaten</label>
            @include('components.checkout.icons.map-pin')
            <select id="city" name="city" required disabled class="w-full pl-10 pr-10 p-3 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 appearance-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                <option value="">Pilih Kota/Kabupaten</option>
            </select>
            <div id="city-spinner" style="display: none;">@include('components.checkout.icons.loading-spinner')</div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="relative">
                <label for="district" class="block text-sm font-semibold text-slate-600 mb-1">Kecamatan</label>
                <select id="district" name="district" required disabled class="w-full p-3 pr-10 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 appearance-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                    <option value="">Pilih Kecamatan</option>
                </select>
                <div id="district-spinner" style="display: none;">@include('components.checkout.icons.loading-spinner')</div>
            </div>
            <div class="relative">
                <label for="village" class="block text-sm font-semibold text-slate-600 mb-1">Kelurahan/Desa</label>
                <select id="village" name="village" required disabled class="w-full p-3 pr-10 bg-white border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 appearance-none disabled:bg-slate-100 disabled:cursor-not-allowed">
                    <option value="">Pilih Kelurahan</option>
                </select>
                <div id="village-spinner" style="display: none;">@include('components.checkout.icons.loading-spinner')</div>
            </div>
        </div>

        <div class="relative">
            <label for="fullAddress" class="block text-sm font-semibold text-slate-600 mb-1">Alamat Lengkap</label>
            @include('components.checkout.icons.home')
            <textarea id="fullAddress" name="fullAddress" rows="3" required class="w-full pl-10 p-3 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="Nama jalan, nomor rumah, RT/RW..."></textarea>
        </div>

        <div class="relative">
            <label for="postalCode" class="block text-sm font-semibold text-slate-600 mb-1">Kode Pos</label>
            @include('components.checkout.icons.mailbox')
            <input type="text" id="postalCode" name="postalCode" value="" required pattern="[0-9]{5}" class="w-full pl-10 p-3 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500" placeholder="e.g. 50511" title="Kode pos harus 5 digit angka" />
        </div>
    </div>
    
    <button type="submit" class="mt-6 w-full bg-yellow-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-sky-700 focus:outline-none focus:ring-4 focus:ring-sky-300 transition-all duration-300">
        Simpan & Cek Ongkos Kirim
    </button>
</form>
<script>
    // Sync hidden shipping_method input with the shipping method radio buttons
    window.addEventListener('load', () => {
        const hidden = document.getElementById('shipping_method_input');
        const pickupRadio = document.getElementById('PickUp');
        const kurirRadio = document.getElementById('Kurir');

        if (pickupRadio) pickupRadio.addEventListener('change', () => { hidden.value = pickupRadio.value; });
        if (kurirRadio) kurirRadio.addEventListener('change', () => { hidden.value = kurirRadio.value; });
        // Set default
        if (!hidden.value && pickupRadio) hidden.value = pickupRadio.value || 'PickUp';
    });
</script>