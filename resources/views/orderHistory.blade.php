@extends('layouts.app')

@section('content')
    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-6 py-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Order History</h2>
            <div class="relative">
                <select id="order-filter"
                    class="border border-gray-300 rounded-md py-2 pl-3 pr-10 bg-white focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <option>All Orders</option>
                    <option>Delivered</option>
                    <option>Processing</option>
                    <option>Cancelled</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-100 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="py-3 px-6 cursor-pointer sort-header" data-col="id">
                            Order ID
                        </th>
                        <th class="py-3 px-6 cursor-pointer sort-header" data-col="date">
                            Date
                        </th>
                        <th class="py-3 px-6 cursor-pointer sort-header" data-col="status">
                            Status
                        </th>
                        <th class="py-3 px-6 cursor-pointer sort-header" data-col="total">
                            Total
                        </th>
                        <th class="py-3 px-6">Action</th>
                    </tr>
                </thead>
                <tbody id="order-table">
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-4 px-6 font-medium text-gray-800">#FL004</td>
                        <td class="py-4 px-6 text-gray-700">2025-10-06</td>
                        <td class="py-4 px-6 text-blue-600 font-semibold">Shipping</td>
                        <td class="py-4 px-6 text-gray-800">Rp180.000</td>
                        <td class="py-4 px-6">
                            <button onclick="alert('Order #FL004 details opened!')" class="text-orange-500 hover:underline">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-4 px-6 font-medium text-gray-800">#FL003</td>
                        <td class="py-4 px-6 text-gray-700">2025-10-02</td>
                        <td class="py-4 px-6 text-green-600 font-semibold">Delivered</td>
                        <td class="py-4 px-6 text-gray-800">Rp250.000</td>
                        <td class="py-4 px-6">
                            <button onclick="alert('Order #FL003 details opened!')" class="text-orange-500 hover:underline">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-4 px-6 font-medium text-gray-800">#FL002</td>
                        <td class="py-4 px-6 text-gray-700">2025-09-29</td>
                        <td class="py-4 px-6 text-yellow-600 font-semibold">
                            Processing
                        </td>
                        <td class="py-4 px-6 text-gray-800">Rp95.000</td>
                        <td class="py-4 px-6">
                            <button onclick="alert('Order #FL002 details opened!')" class="text-orange-500 hover:underline">
                                View Details
                            </button>
                        </td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="py-4 px-6 font-medium text-gray-800">#FL001</td>
                        <td class="py-4 px-6 text-gray-700">2025-09-20</td>
                        <td class="py-4 px-6 text-red-600 font-semibold">Cancelled</td>
                        <td class="py-4 px-6 text-gray-800">Rp150.000</td>
                        <td class="py-4 px-6">
                            <button onclick="alert('Order #FL001 details opened!')" class="text-orange-500 hover:underline">
                                View Details
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
@endsection


@push('scripts')
    <!-- Script: Sorting & Filtering -->
    <script>
        const table = document.getElementById("order-table");
        const headers = document.querySelectorAll(".sort-header");
        const filter = document.getElementById("order-filter");

        headers.forEach((header) => {
            header.addEventListener("click", () => {
                const col = header.dataset.col;
                const rows = Array.from(table.querySelectorAll("tr"));
                const asc = !header.classList.contains("asc");

                rows.sort((a, b) => {
                    const aText = a
                        .querySelector(`[data-col="${col}"]`) ?
                        a.querySelector(`[data-col="${col}"]`).innerText :
                        a.children[header.cellIndex].innerText;
                    const bText = b
                        .querySelector(`[data-col="${col}"]`) ?
                        b.querySelector(`[data-col="${col}"]`).innerText :
                        b.children[header.cellIndex].innerText;

                    return asc ?
                        aText.localeCompare(bText) :
                        bText.localeCompare(aText);
                });

                table.innerHTML = "";
                rows.forEach((row) => table.appendChild(row));
                headers.forEach((h) => h.classList.remove("asc", "desc"));
                header.classList.add(asc ? "asc" : "desc");
            });
        });

        filter.addEventListener("change", () => {
            const value = filter.value;
            Array.from(table.rows).forEach((row) => {
                const status = row.cells[2].innerText.trim();
                row.style.display =
                    value === "All Orders" || status === value ? "" : "none";
            });
        });
    </script>
@endpush
