<?php

namespace App\Http\Controllers\Okrs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Okrs\Year;
use App\Http\Requests\Okrs\YearRequest;

class YearController extends Controller
{
    public function user(): User
    {
        return Auth::user();
    }

    public function create()
    {
        return inertia('app/okrs/years/Create');
    }

    public function store(YearRequest $request)
    {
        $validated_data = $request->validated();

        $year = $this->user()->years()->create($validated_data);

        Inertia::flash('toast', [
            'type' => "success",
            'message' => "Year created successfully"
        ]);

        return to_route('okrs.index');
    }

    public function edit(Year $year)
    {
        return inertia('app/okrs/years/Edit', [
            'year' => $year
        ]);
    }

    public function update(YearRequest $request, Year $year)
    {
        $validated_data = $request->validated();

        $year->update($validated_data);

        Inertia::flash('toast', [
            'type' => "success",
            'message' => "Year updated successfully"
        ]);

        return to_route('okrs.index');
    }

    public function destroy(Year $year)
    {
        $year->delete();

        Inertia::flash('toast', [
            'type' => "success",
            'message' => "Year deleted successfully"
        ]);

        return to_route('okrs.index');
    }
}
