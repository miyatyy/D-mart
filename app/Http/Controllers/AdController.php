<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use Illuminate\Support\Facades\Auth;


class AdController extends Controller
{
    public function storeAd(Request $request)
{
    $request->validate([
        'judul_iklan' => 'required|string|max:100',
        'gambar_iklan' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $filename = time() . '_' . $request->file('gambar_iklan')->getClientOriginalName();
    $request->file('gambar_iklan')->move(public_path('uploads/ads'), $filename);

    Ad::create([
        'user_id' => Auth::id(),
        'judul_iklan' => $request->judul_iklan,
        'gambar_iklan' => $filename,
    ]);

    return back()->with('success', 'Banner iklan berhasil dipasang!');
}
public function stopAd($id) {
    $ad = \App\Models\Ad::findOrFail($id);
    $ad->update(['status' => 'berhenti']);
    return back()->with('success', 'Iklan telah dihentikan.');
}
}
