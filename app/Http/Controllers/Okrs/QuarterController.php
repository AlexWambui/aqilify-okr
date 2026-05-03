<?php

namespace App\Http\Controllers\Okrs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Okrs\Year;
use App\Models\Okrs\Quarter;
use App\Http\Requests\Okrs\QuarterRequest;

class QuarterController extends Controller
{
    public function store(QuarterRequest $request, Year $year)
    {
        $validated = $request->validated();
        
        $quarter = $year->quarters()->create($validated);
        
        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$quarter->label} created successfully"
        ]);
        
        return back();
    }
    
    public function storeBulk(Request $request, Year $year)
    {
        $request->validate([
            'quarters' => 'required|array|min:1',
            'quarters.*.label' => 'required|string|in:Q1,Q2,Q3,Q4',
            'quarters.*.start_date' => 'required|date',
            'quarters.*.end_date' => 'required|date|after:start_date',
        ]);
        
        $created = 0;
        foreach ($request->quarters as $quarterData) {
            // Check if quarter already exists
            if (!$year->quarters()->where('label', $quarterData['label'])->exists()) {
                $year->quarters()->create($quarterData);
                $created++;
            }
        }
        
        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$created} quarter(s) created successfully"
        ]);
        
        return back();
    }
    
    public function update(QuarterRequest $request, Year $year, Quarter $quarter)
    {
        $quarter->update($request->validated());
        
        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$quarter->label} updated successfully"
        ]);
        
        return back();
    }
    
    public function destroy(Year $year, Quarter $quarter)
    {
        $label = $quarter->label;
        $quarter->delete();
        
        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "{$label} deleted successfully"
        ]);
        
        return back();
    }
}