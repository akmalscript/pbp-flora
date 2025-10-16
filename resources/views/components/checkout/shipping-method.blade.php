<h3 class="text-xl font-bold text-slate-800 mb-4">Metode Pengiriman</h3>
<div class="space-y-3">
    <div>
        <input type="radio" id="PickUp" name="shipping_method" value="PickUp"
               class="hidden peer" onchange="toggleShippingAddress(this.value)">
        <label for="PickUp" class="flex items-center p-4 border-2 border-slate-200 rounded-lg cursor-pointer transition-all duration-300 peer-checked:border-sky-500 peer-checked:ring-2 peer-checked:ring-sky-200 hover:bg-slate-50">
            <div class="flex-shrink-0 mr-4">@include('components.checkout.icons.store')</div>
            <div class="flex-grow">
                <span class="font-bold text-slate-700">Ambil di Toko</span>
                <p class="text-sm text-slate-500">Ambil langsung di toko kami</p>
            </div>
        </label>
    </div>
    <div>
        <input type="radio" id="Kurir" name="shipping_method" value="Kurir"
               class="hidden peer" onchange="toggleShippingAddress(this.value)">
        <label for="Kurir" class="flex items-center p-4 border-2 border-slate-200 rounded-lg cursor-pointer transition-all duration-300 peer-checked:border-sky-500 peer-checked:ring-2 peer-checked:ring-sky-200 hover:bg-slate-50">
            <div class="flex-shrink-0 mr-4">@include('components.checkout.icons.truck')</div>
            <div class="flex-grow">
                <span class="font-bold text-slate-700">Kurir</span>
                <p class="text-sm text-slate-500">Menggunakan jasa kurir terbaik</p>
            </div>
        </label>
    </div>
</div>
<script>
    window.addEventListener('load', () => {
        const pickupRadio = document.getElementById('PickUp');
        if (pickupRadio) pickupRadio.checked = true;
    });
</script>