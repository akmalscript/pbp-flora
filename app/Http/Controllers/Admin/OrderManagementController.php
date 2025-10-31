<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderManagementController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with('user')
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereRaw('LOWER(status) <> ?', ['pending']);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $statusOptions = $this->statusOptions();

        return view('order', [
            'orders' => $orders,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $statusOptions = $this->statusOptions();
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($statusOptions))],
        ]);

        $order->update(['status' => $validated['status']]);

        return Redirect::route('admin.orders.manage')
            ->with('status', 'Status pesanan diperbarui.');
    }

    /**
     * @return array<string, string>
     */
    protected function statusOptions(): array
    {
        $defaults = collect(['Processing', 'Shipping', 'Delivered', 'Cancelled', 'Completed']);
        $existing = Order::query()->select('status')->distinct()->pluck('status')->filter();

        return $defaults
            ->merge($existing)
            ->filter(fn ($status) => $status && strtolower((string) $status) !== 'pending')
            ->unique()
            ->mapWithKeys(function ($status) {
                $statusString = (string) $status;
                return [$statusString => Str::headline($statusString)];
            })
            ->sort()
            ->toArray();
    }
}
