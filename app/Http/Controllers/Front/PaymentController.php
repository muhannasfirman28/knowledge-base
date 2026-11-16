<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Tenancy;
use App\Models\Bill;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    protected $expiryUnit = 'day';
    protected $expiryDuration = 1;  

    public function show(Tenancy $tenancy)
    {
        // 1. Load data relasi dasar
        $tenancy->load(['room.group', 'mainTenant']);

        // 2. Tagihan Belum Lunas (Untuk Form Bayar Baru)
        $unpaidBills = $tenancy->bills()
            ->unpaid()
            ->orderBy('order_sequence', 'asc')
            ->get();

        // 3. Transaksi Pending (Active Transactions)
        // User bisa resume pembayaran (Snap Token) dari sini
        $pendingTransactions = Transaction::where('tenancy_id', $tenancy->id)
            ->where('status', 'pending')
            ->with('bills') // Load tagihan apa saja di dalamnya
            ->latest()
            ->get();

        // 4. Riwayat Pembayaran (Lunas)
        $historyBills = $tenancy->bills()
            ->paid()
            ->orderBy('order_sequence', 'desc')
            ->get();

        $expiryConfig = [
            'unit' => $this->expiryUnit,
            'duration' => $this->expiryDuration
        ];

        return view('front.payment', compact('tenancy', 'unpaidBills', 'pendingTransactions', 'historyBills', 'expiryConfig'));
    }
    
    public function process(Request $request)
    {
        $request->validate([
            'tenancy_id' => 'required|exists:tenancies,id',
            'bill_ids' => 'required|array|min:1',
            'bill_ids.*' => 'exists:bills,id'
        ]);

        $hasPending = Transaction::where('tenancy_id', $request->tenancy_id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return response()->json([
                'error' => 'Anda memiliki transaksi yang sedang berjalan. Mohon selesaikan atau batalkan transaksi tersebut di tab "Sedang Berjalan".'
            ], 400);
        }

        $bills = Bill::whereIn('id', $request->bill_ids)->get();
        $totalAmount = (int) $bills->sum('amount');
        
        $orderId = 'TRX-' . time() . '-' . Str::upper(Str::random(6));

        $transaction = Transaction::create([
            'tenancy_id' => $request->tenancy_id,
            'order_id' => $orderId,
            'gross_amount' => (int) $totalAmount,
            'status' => 'pending',
            'payment_type' => null,
        ]);

        $transaction->bills()->attach($request->bill_ids);

        $expirySettings = [
            'start_time' => date('Y-m-d H:i:s O'), 
            'unit'       => $this->expiryUnit,
            'duration'   => $this->expiryDuration
        ];


        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $transaction->tenancy->mainTenant->name ?? 'Penyewa',
                'email' => $transaction->tenancy->mainTenant->email ?? 'guest@example.com',
            ],
            "item_details" => $bills->map(function ($bill) {
                return [
                    "id" => $bill->id,
                    "price" => (int) $bill->amount,
                    "quantity" => 1,
                    "name" => $bill->month_name,
                ];
            })->toArray(),

            'expiry' => $expirySettings,
            'callbacks' => [
                'finish' => route('home')
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            $transaction->update(['snap_token' => $snapToken]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cancel(Transaction $transaction)
    {
        if ($transaction->status === 'pending') {
            $transaction->update(['status' => 'cancel']);
            
            return back()->with('success', 'Transaksi berhasil dibatalkan. Silakan buat pembayaran baru.');
        }

        return back()->with('error', 'Transaksi tidak dapat dibatalkan.');
    }

    public function callback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key');
        $hashed = hash("sha512", $request->order_id.$request->status_code.$request->gross_amount.$serverKey);

        if ($hashed == $request->signature_key) {
            $transaction = Transaction::where('order_id', $request->order_id)->first();
            
            if ($transaction) {
                $transaction->update([
                    'status' => $request->transaction_status,
                    'payment_type' => $request->payment_type,
                ]);
            }
        }
        return response('OK');
    }
}