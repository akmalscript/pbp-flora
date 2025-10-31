@extends('layouts.public')

@section('title', 'Keranjang Belanja')

@section('content')
<main class="flex-grow max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-12">
  <section class="md:col-span-2 space-y-6">
    <h1 class="text-3xl font-bold">Your Cart</h1>

    @if ($cart->products->isEmpty())
      <div class="p-6 border rounded-lg text-center text-gray-600">
        Your cart is empty.
      </div>
    @else
      @foreach ($cart->products as $product)
        @php
          $price = $product->price;
          $subtotalItem = $product->pivot->quantity * $price;
        @endphp
        <div class="flex items-start justify-between border-b pb-6">
          <div class="flex items-start gap-4">
            <img src="{{ asset($product->image_url) }}"\
                 class="w-20 h-20 rounded-md object-cover" />
            <div>
              <p class="font-semibold text-lg">{{ $product->name }}</p>
              <p class="text-gray-500 text-sm">Rp {{ number_format($price, 0, ',', '.') }}</p>
            </div>
          </div>

          <div class="flex flex-col items-end gap-3">
            <div class="flex items-center border rounded-lg">
              <form action="{{ route('user.cart.decrease', $product->id) }}" method="POST">
                @csrf
                <button class="px-3 py-2 hover:bg-gray-100">-</button>
              </form>

              <form action="{{ route('user.cart.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="number" name="quantity" value="{{ $product->pivot->quantity }}"
                  min="1" max="{{ $product->stock }}"
                  class="no-spinner w-12 text-center border-x outline-none quantity-input" />
                <button type="submit" class="hidden"></button>
              </form>

              <form action="{{ route('user.cart.increase', $product->id) }}" method="POST">
                @csrf
                <button class="px-3 py-2 hover:bg-gray-100">+</button>
              </form>
            </div>

            <div class="flex items-center gap-4 text-sm">
              <form action="{{ route('user.cart.remove', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="text-red-600 hover:underline">Remove</button>
              </form>
              <div class="text-sm text-gray-700 font-medium">
                Rp {{ number_format($subtotalItem, 0, ',', '.') }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    @endif

    <div class="mt-6">
      <a href="{{ route('user.home') }}"
         class="inline-flex items-center gap-2 text-sm text-orange-500 hover:underline">
        ← Continue shopping
      </a>
    </div>
  </section>

  <aside class="md:col-span-1">
    <div class="bg-white rounded-lg shadow p-6 sticky top-24">
      <h2 class="text-lg text-center font-semibold mb-4">Total yang harus dibayarkan</h2>

      <div class="text-3xl text-center font-bold text-gray-900 tracking-wide mb-6">
        Rp {{ number_format($cart->products->sum(fn($product) => $product->pivot->quantity * $product->price), 0, ',', '.') }}
      </div>

      <form action="{{ route('user.checkout.index') }}" method="GET" onsubmit="if(this.querySelector('button[type=submit]').disabled){ event.preventDefault(); }">
        @php $disabled = $cart->products->isEmpty(); @endphp
        <button type="submit"
                @if($disabled) disabled aria-disabled="true" @endif
                class="w-full py-3 rounded-lg transition {{ $disabled ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-orange-500 text-white hover:bg-orange-600' }}">
          Checkout
        </button>
      </form>
    </div>
  </aside>
</main>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.quantity-input').forEach(input => {
  input.addEventListener('keydown', function(e) {
    const allowedKeys = ['Backspace', 'ArrowLeft', 'ArrowRight', 'Delete', 'Tab'];
    const isCtrlCombo = (e.ctrlKey || e.metaKey) && ['a', 'c', 'v', 'x'].includes(e.key.toLowerCase());

    if (e.key === 'Enter') {
      e.preventDefault();
      const value = parseInt(this.value);
      const min = parseInt(this.min);
      const max = parseInt(this.max);

      if (isNaN(value) || value < min) {
        Swal.fire({
          icon: 'warning',
          title: 'Jumlah terlalu kecil',
          text: `Jumlah minimal adalah ${min}`,
          allowOutsideClick: false,
          confirmButtonText: 'OK'
        }).then(() => {
          this.value = min;
          this.focus();
        });
        return;
      }

      if (value > max) {
        Swal.fire({
          icon: 'warning',
          title: 'Jumlah melebihi stok',
          text: `Jumlah maksimal stok adalah ${max}`,
          allowOutsideClick: false,
          confirmButtonText: 'OK'
        }).then(() => {
          this.value = max;
          this.focus();
        });
        return;
      }

      this.form.submit();
      return;
    }

    if (!allowedKeys.includes(e.key) && !isCtrlCombo && !/^\d$/.test(e.key)) {
      e.preventDefault();
    }
  });

  input.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
  });
});
</script>

@endpush