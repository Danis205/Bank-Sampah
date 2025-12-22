<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // ======================
    // USER
    // ======================
    public function userIndex()
    {
        $transactions = Transaction::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('transactions.user.index', compact('transactions'));
    }

    public function userCreate()
    {
        return view('transactions.user.create', [
            'categories' => WasteCategory::all()
        ]);
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:waste_categories,id',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $category = WasteCategory::findOrFail($request->category_id);
        $total = $category->price_per_kg * $request->weight;

        Transaction::create([
            'user_id' => Auth::id(),
            'waste_category_id' => $category->id,
            'weight' => $request->weight,
            'price_per_kg' => $category->price_per_kg,
            'total_price' => $total,
            'status' => 'pending',
            'transaction_code' => 'TRX-' . now()->format('YmdHis') . '-' . rand(100,999),
        ]);

        return redirect()->route('user.transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan, menunggu verifikasi admin.');
    }

    // ======================
    // ADMIN
    // ======================
    public function index()
    {
        $transactions = Transaction::with('user','category')
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        return view('transactions.show', compact('transaction'));
    }

    public function approve(Transaction $transaction)
    {
        // 🔒 admin only
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back();
        }

        DB::transaction(function () use ($transaction) {
            $transaction->load('user');

            $points = floor($transaction->total_price / 1000);

            $transaction->update([
                'status' => 'approved',
                'points_awarded' => $points,
            ]);

            $transaction->user->update([
                'saldo' => $transaction->user->saldo + $transaction->total_price,
                'total_points' => $transaction->user->total_points + $points,
            ]);
        });

        return back()->with('success','Transaksi berhasil di-approve');
    }

    public function reject(Transaction $transaction)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if ($transaction->status !== 'pending') {
            return back();
        }

        $transaction->update(['status' => 'rejected']);

        return back()->with('success','Transaksi ditolak');
    }
}
