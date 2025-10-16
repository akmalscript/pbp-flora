@props(['item'])

<div class="mx-auto">
  <div class="flex items-start gap-4 p-2 bg-white rounded-xl shadow-md border border-slate-100">
    <img 
      src="{{ $item['imageUrl'] }}" 
      alt="{{ $item['name'] }}"
      class="w-16 h-16 object-cover rounded-lg flex-shrink-0"
    />
    <div>
      <p class="font-bold text-slate-800 text-base leading-tight">
        {{ $item['name'] }}
      </p>
      <p class="text-sm text-slate-500 mt-1">
        Jumlah: <span class="font-semibold text-slate-600">{{ $item['quantity'] }}</span>
      </p>
      <p class="font-semibold text-sky-600 text-base">
        {{ 'Rp ' . number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
      </p>
    </div>
  </div>
</div>