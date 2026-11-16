<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Tenancy;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'tenancy_id' => 'required|exists:tenancies,id'
        ], [
            'tenancy_id.exists' => 'Token ID tidak ditemukan.'
        ]);

        return redirect()->route('payment.show', $request->tenancy_id);
    }

    public function index()
    {
        $groups = Group::with(['rooms' => function($q) {
            $q->published()->orderBy('number')->with('facilities');
        }])->get();

        return view('front.home', compact('groups'));
    }

    public function term()
    {
        return view('front.term');
    }
    
    public function detail(Group $group)
    {
        $group->load(['rooms' => function($q) {
            $q->published()->orderBy('number')->with('facilities');
        }]);

        return view('front.group_detail', compact('group'));
    }

    public function about()
    {
        return view('front.about');
    }
}