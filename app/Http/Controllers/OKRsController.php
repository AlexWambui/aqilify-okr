<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OKRsController extends Controller
{
    public function user(): User
    {
        return Auth::user();
    }

    public function index(Request $request)
    {
        $years = $this->user()->years()->orderBy('year', 'asc')->get();

        $current_year = $request->query('year') ? (int) $request->query('year') : null;

        if (!$current_year) {
            // Get the latest year that's greater than current year
            $year = $years->where('year', '<=', now()->year)->sortByDesc('year')->first();
            $current_year = $year ? $year->year : ($years->first()?->year ?? null);
        }

        return inertia('app/okrs/okrs/Index', [
            'years' => $years,
            'current_year' => $current_year
        ]);
    }
}
