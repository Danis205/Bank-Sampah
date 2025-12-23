<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * Redeem poin menjadi saldo
     * 100 poin = Rp 10.000
     */
    public function redeem(Request $request)
    {
        $user = Auth::user();

        // Cek minimal poin (100 poin)
        if ($user->total_points < 100) {
            return back()->with('error', 'Poin tidak mencukupi! Minimal 100 poin untuk redeem.');
        }

        // Konfigurasi redeem
        $pointsToRedeem = 100;      // Poin yang akan dikurangi
        $saldoToAdd = 10000;        // Rp 10.000

        // Kurangi poin dan tambah saldo
        $user->total_points -= $pointsToRedeem;
        $user->saldo += $saldoToAdd;
        $user->save();

        return back()->with('success', 'Berhasil redeem! 100 poin ditukar menjadi Rp 10.000');
    }
}
