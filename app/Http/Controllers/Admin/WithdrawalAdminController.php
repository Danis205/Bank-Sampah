<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalAdminController extends Controller
{
    public function index()
    {
        $withdrawals = Withdrawal::with('user')
            ->latest()
            ->paginate(10);

        return view('withdrawals.admin.index', compact('withdrawals'));
    }

    public function show(Withdrawal $withdrawal)
    {
        return view('withdrawals.admin.show', compact('withdrawal'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return redirect()
                ->route('withdrawals.admin.index')
                ->with('error', 'Penarikan sudah diproses');
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update(['status' => 'approved']);

            // Deduct saldo when approved
            $withdrawal->user->decrement('saldo', $withdrawal->amount);
        });

        return redirect()
            ->route('withdrawals.admin.index')
            ->with('success', 'Penarikan disetujui');
    }

    public function reject(Withdrawal $withdrawal)
    {
        $withdrawal->update(['status' => 'rejected']);

        return redirect()
            ->route('withdrawals.admin.index')
            ->with('error', 'Penarikan ditolak');
    }
}
