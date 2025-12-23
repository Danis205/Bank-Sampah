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

        try {
            DB::transaction(function () use ($withdrawal) {
                // Lock the user row to prevent race conditions
                $user = $withdrawal->user()->lockForUpdate()->first();
                
                // Check if user has sufficient balance
                if ($user->saldo < $withdrawal->amount) {
                    throw new \Exception('Saldo pengguna tidak mencukupi untuk penarikan ini');
                }
                
                // Deduct saldo
                $user->decrement('saldo', $withdrawal->amount);
                
                // Update withdrawal status (only status field)
                $withdrawal->update(['status' => 'approved']);
            });

            return redirect()
                ->route('withdrawals.admin.index')
                ->with('success', 'Penarikan disetujui');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('withdrawals.admin.index')
                ->with('error', $e->getMessage());
        }
    }

    public function reject(Withdrawal $withdrawal)
    {
        $withdrawal->update(['status' => 'rejected']);

        return redirect()
            ->route('withdrawals.admin.index')
            ->with('error', 'Penarikan ditolak');
    }
}
