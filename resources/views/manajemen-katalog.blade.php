@extends('layouts.app')

@section('content')
@php
    $products = $products ?? collect();
    $categories = $categories ?? collect();
@endphp
    <section class="text-center mt-12 mb-10">
        <h1 class="text-3xl font-bold tracking-wide text-gray-900">MANAJEMEN PRODUK DAN KATEGORI</h1>
        @if (session('status'))
            <p class="mt-4 inline-block bg-green-100 text-green-700 text-sm font-medium px-4 py-2 rounded-md">
                {{ session('status') }}
            </p>
        @endif
        @if ($errors->any())
            <div class="mt-4 max-w-3xl mx-auto bg-red-100 border border-red-200 text-red-700 text-sm rounded-md p-4">
                <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </section>

    <main class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 mb-16">
        <section class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg text-center font-semibold mb-4">PRODUK</h2>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search" class="w-full border border-gray-300 rounded-md pl-8 pr-3 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none" />
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-2 top-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <button id="open-create-product" type="button" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium w-full sm:w-auto">
                    TAMBAH PRODUK
                </button>
            </div>

            <table class="w-full text-sm border-t">
                <thead class="text-left text-gray-600 border-b">
                    <tr>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4 text-center">Tersedia</th>
                        <th class="py-3 px-4 text-center" colspan="2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($products as $product)
                        @php
                            $primaryPhoto = $product->photos->first();
                            $imageUrl = $primaryPhoto->url ?? $product->image_url ?? 'https://placehold.co/96x96?text=No+Image';
                            $isAvailable = (bool) ($product->available ?? false);
                            $categoryName = optional($product->category)->name ?? 'Tanpa Kategori';
                            $productPayload = [
                                'id' => $product->id,
                                'name' => $product->name,
                                'description' => $product->description,
                                'price' => $product->price,
                                'available' => $isAvailable ? 1 : 0,
                                'image_url' => $product->image_url,
                                'pre_order_duration' => $product->pre_order_duration,
                                'category_id' => $product->category_id,
                                'update_url' => route('admin.products.update', $product->id),
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-md object-cover border" />
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500">Kategori: {{ $categoryName }}</p>
                                        <p class="text-xs text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">{!! $isAvailable ? '&#x2714;' : '&#x2716;' !!}</td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" class="edit-product-btn text-orange-500 text-xs font-semibold hover:underline" data-product='@json($productPayload)'>Edit</button>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="delete-product-form" data-product-name="{{ $product->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs font-semibold hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada produk yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <section class="bg-white rounded-lg border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg text-center font-semibold mb-4">KATEGORI</h2>

            <div class="flex items-center justify-between gap-4 pb-4">
                <div class="relative flex-1">
                    <input type="text" placeholder="Search" class="w-full border border-gray-300 rounded-md pl-8 pr-3 py-2 text-sm focus:ring-1 focus:ring-orange-400 focus:outline-none" />
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-2 top-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button id="open-create-category" type="button" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    TAMBAH KATEGORI
                </button>
            </div>

            <table class="w-full text-sm border-t">
                <thead class="text-left text-gray-600 border-b">
                    <tr>
                        <th class="py-3 px-4 text-center">Nama</th>
                        <th class="py-3 px-4 text-center">Banyak Produk</th>
                        <th class="py-3 px-4 text-center" colspan="2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($categories as $category)
                        @php
                            $categoryPayload = [
                                'id' => $category->id,
                                'name' => $category->name,
                                'description' => $category->description,
                                'update_url' => route('admin.categories.update', $category->id),
                            ];
                        @endphp
                        <tr>
                            <td class="py-3 px-4">
                                <p class="font-medium text-gray-800">{{ $category->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $category->description }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">{{ $category->products_count ?? 0 }}</td>
                            <td class="py-3 px-4 text-center">
                                <button type="button" class="edit-category-btn text-orange-500 text-xs font-semibold hover:underline" data-category='@json($categoryPayload)'>Edit</button>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}" class="delete-category-form" data-category-name="{{ $category->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 text-xs font-semibold hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500">Belum ada kategori yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </main>

    <div id="create-product-modal" class="fixed inset-0 hidden z-50 items-center justify-center">
        <div data-modal-overlay class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="border-b px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Produk</h3>
                <button type="button" data-modal-close class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="create-product-form" method="POST" action="{{ route('admin.products.store') }}" class="px-6 py-6 space-y-4">
                @csrf
                <input type="hidden" name="available" value="1">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="create-product-name">Nama Produk</label>
                        <input id="create-product-name" name="name" type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="create-product-price">Harga</label>
                        <input id="create-product-price" name="price" type="number" min="0" step="0.01" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="create-product-category">Kategori</label>
                        <select id="create-product-category" name="category_id" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="create-product-preorder">Durasi Pre-order (hari)</label>
                        <input id="create-product-preorder" name="pre_order_duration" type="number" min="0" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" placeholder="Misal: 3" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="create-product-image">URL Gambar</label>
                    <input id="create-product-image" name="image_url" type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" placeholder="https://..." required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="create-product-description">Deskripsi</label>
                    <textarea id="create-product-description" name="description" rows="4" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" data-modal-close class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-orange-500 text-white font-semibold hover:bg-orange-600">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-product-modal" class="fixed inset-0 hidden z-50 items-center justify-center">
        <div data-modal-overlay class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="border-b px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Edit Produk</h3>
                <button type="button" data-modal-close class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="edit-product-form" method="POST" action="#" class="px-6 py-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="product_id" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="edit-product-name">Nama Produk</label>
                        <input id="edit-product-name" name="name" type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="edit-product-price">Harga</label>
                        <input id="edit-product-price" name="price" type="number" min="0" step="0.01" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="edit-product-category">Kategori</label>
                        <select id="edit-product-category" name="category_id" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none">
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700" for="edit-product-available">Ketersediaan</label>
                        <select id="edit-product-available" name="available" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none">
                            <option value="1">Tersedia</option>
                            <option value="0">Tidak Tersedia</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="edit-product-image">URL Gambar</label>
                    <input id="edit-product-image" name="image_url" type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" placeholder="https://...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="edit-product-preorder">Durasi Pre-order (hari)</label>
                    <input id="edit-product-preorder" name="pre_order_duration" type="number" min="0" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" placeholder="Misal: 3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="edit-product-description">Deskripsi</label>
                    <textarea id="edit-product-description" name="description" rows="4" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" data-modal-close class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-orange-500 text-white font-semibold hover:bg-orange-600">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="create-category-modal" class="fixed inset-0 hidden z-50 items-center justify-center">
        <div data-modal-overlay class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="border-b px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Tambah Kategori</h3>
                <button type="button" data-modal-close class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="create-category-form" method="POST" action="{{ route('admin.categories.store') }}" class="px-6 py-6 space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="create-category-name">Nama Kategori</label>
                    <input id="create-category-name" name="name" type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="create-category-description">Deskripsi</label>
                    <textarea id="create-category-description" name="description" rows="4" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" data-modal-close class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-orange-500 text-white font-semibold hover:bg-orange-600">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <div id="edit-category-modal" class="fixed inset-0 hidden z-50 items-center justify-center">
        <div data-modal-overlay class="absolute inset-0 bg-black bg-opacity-40"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="border-b px-6 py-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Edit Kategori</h3>
                <button type="button" data-modal-close class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form id="edit-category-form" method="POST" action="#" class="px-6 py-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="edit-category-name">Nama Kategori</label>
                    <input id="edit-category-name" name="name" type="text" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700" for="edit-category-description">Deskripsi</label>
                    <textarea id="edit-category-description" name="description" rows="4" class="mt-1 w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-1 focus:ring-orange-400 focus:outline-none" required></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" data-modal-close class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-orange-500 text-white font-semibold hover:bg-orange-600">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const activeClosers = new Set();

    const configureModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (!modal) return null;
        const form = modal.querySelector('form');
        const overlay = modal.querySelector('[data-modal-overlay]');
        const closeButtons = modal.querySelectorAll('[data-modal-close]');

        const close = () => {
            if (modal.classList.contains('hidden')) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            activeClosers.delete(close);
            if (activeClosers.size === 0) document.body.classList.remove('overflow-hidden');
            if (form) form.reset();
        };

        const open = () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            activeClosers.add(close);
            document.body.classList.add('overflow-hidden');
        };

        closeButtons.forEach((btn) => btn.addEventListener('click', close));
        if (overlay) overlay.addEventListener('click', close);

        return { modal, form, open, close };
    };

    const createModal = configureModal('create-product-modal');
    const editModal = configureModal('edit-product-modal');
    const createCategoryModal = configureModal('create-category-modal');
    const categoryModal = configureModal('edit-category-modal');

    const createButton = document.getElementById('open-create-product');
    if (createButton && createModal) {
        createButton.addEventListener('click', () => createModal.open());
    }

    const createCategoryButton = document.getElementById('open-create-category');
    if (createCategoryButton && createCategoryModal) {
        createCategoryButton.addEventListener('click', () => createCategoryModal.open());
    }

    document.querySelectorAll('.edit-product-btn').forEach((button) => {
        button.addEventListener('click', () => {
            if (!editModal) return;
            const raw = button.getAttribute('data-product');
            if (!raw) return;

            let data;
            try {
                data = JSON.parse(raw);
            } catch (error) {
                console.error('Failed to parse product payload', error);
                return;
            }

            const form = editModal.form;
            if (!form) return;

            form.action = data.update_url || '#';
            if (form.elements['product_id']) form.elements['product_id'].value = data.id || '';
            if (form.elements['name']) form.elements['name'].value = data.name || '';
            if (form.elements['description']) form.elements['description'].value = data.description || '';
            if (form.elements['price']) form.elements['price'].value = data.price ?? '';
            if (form.elements['available']) form.elements['available'].value = String(data.available ?? 1);
            if (form.elements['image_url']) form.elements['image_url'].value = data.image_url || '';
            if (form.elements['pre_order_duration']) form.elements['pre_order_duration'].value = data.pre_order_duration ?? '';
            if (form.elements['category_id']) form.elements['category_id'].value = data.category_id || '';

            editModal.open();
        });
    });

    document.querySelectorAll('.edit-category-btn').forEach((button) => {
        button.addEventListener('click', () => {
            if (!categoryModal) return;
            const raw = button.getAttribute('data-category');
            if (!raw) return;

            let data;
            try {
                data = JSON.parse(raw);
            } catch (error) {
                console.error('Failed to parse category payload', error);
                return;
            }

            const form = categoryModal.form;
            if (!form) return;

            form.action = data.update_url || '#';
            if (form.elements['name']) form.elements['name'].value = data.name || '';
            if (form.elements['description']) form.elements['description'].value = data.description || '';

            categoryModal.open();
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            Array.from(activeClosers).forEach((close) => close());
        }
    });

    const confirmDeletion = (title, text, fallbackMessage, onConfirm) => {
        if (typeof Swal === 'undefined') {
            if (confirm(fallbackMessage)) onConfirm();
            return;
        }

        Swal.fire({
            title,
            text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f97316',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            focusCancel: true,
        }).then((result) => {
            if (result.isConfirmed) onConfirm();
        });
    };

    document.querySelectorAll('.delete-product-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const productName = form.dataset.productName || 'produk ini';
            confirmDeletion(
                'Hapus Produk?',
                `Produk "${productName}" akan dihapus secara permanen.`,
                `Hapus ${productName}?`,
                () => form.submit(),
            );
        });
    });

    document.querySelectorAll('.delete-category-form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const categoryName = form.dataset.categoryName || 'kategori ini';
            confirmDeletion(
                'Hapus Kategori?',
                `Kategori "${categoryName}" akan dihapus. Produk yang terkait akan kehilangan kategorinya.`,
                `Hapus ${categoryName}?`,
                () => form.submit(),
            );
        });
    });
});
</script>
@endpush
