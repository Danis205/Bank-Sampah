<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\WasteCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of withdrawals
     */

    public function show($id)
    {
        $withdrawal = Withdrawal::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('withdrawals.user.show', compact('withdrawal'));
    }


    public function index(){
        $withdrawals = Withdrawal::where('user_id', Auth::id())
        ->latest()    
        ->paginate(10);    

    return view('withdrawals.user.index', compact('withdrawals'));
    }

    public function create(){
        return view('withdrawals.user.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $user = Auth::user();

        if ($request->amount > $user->saldo) {
            return back()->with('error', 'Saldo tidak mencukupi.');
        }

        // =========================
        // SIMPAN (WITHOUT CODE)
        // =========================
        \App\Models\Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        // Saldo will be deducted when admin approves

        return redirect()
            ->route('withdrawals.user.index')
            ->with('success', 'Permintaan penarikan berhasil dikirim.');
    }


    // ADMIN
    public function adminIndex()
    {
        $withdrawals = Withdrawal::with('user')->latest()->get();
        return view('withdrawals.admin.index', compact('withdrawals'));
    }

    public function approve(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal sudah diproses.');
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

            return back()->with('success', 'Withdraw disetujui');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Withdrawal $withdrawal)
    {
        $withdrawal->update(['status'=>'rejected']);
        return back()->with('success','Withdraw ditolak');
    }

    /**
     * Remove the specified withdrawal (Admin only)
     */
    public function destroy(Withdrawal $withdrawal)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $withdrawal->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Data penarikan berhasil dihapus.');
    }
}