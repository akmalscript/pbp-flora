@props(['cost'])

<div>
    <h3 class="text-xl font-bold text-slate-800 mb-4">Pilih Jasa Pengiriman</h3>
    <div class="flex items-center p-4 border-2 rounded-lg border-sky-500 ring-2 ring-sky-200">
        <div class="flex-grow">
            <span class="font-bold text-slate-700">{{ $cost['service'] }} ({{ $cost['description'] }})</span>
            <p class="text-sm text-slate-500">Estimasi tiba dalam {{ $cost['cost'][0]['etd'] }} hari.</p>
        </div>
        <div class="flex-shrink-0 ml-4 text-right">
            <span class="font-semibold text-slate-800">{{ 'Rp ' . number_format($cost['cost'][0]['value'], 0, ',', '.') }}</span>
        </div>
    </div>
</div>