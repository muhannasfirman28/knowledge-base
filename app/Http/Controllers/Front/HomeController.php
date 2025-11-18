<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Tenancy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $groups = Group::with(['rooms' => function($q) {
            $q->published()->orderBy('number');
        }])->get();

        return view('front.home', compact('groups'));
    }

    public function check(Request $request)
    {
        $request->validate([
            'tenancy_id' => 'required|exists:tenancies,id'
        ], [
            'tenancy_id.exists' => 'Token ID tidak ditemukan.'
        ]);

        return redirect()->route('payment.show', $request->tenancy_id);
    }
}