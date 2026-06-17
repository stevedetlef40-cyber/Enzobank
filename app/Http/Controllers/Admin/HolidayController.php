<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $page_title = __('Holidays');
        $holidays = Holiday::orderBy('holiday_date')->paginate(20);
        return view('admin.sections.holidays.index', compact('page_title','holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => ['required','date'],
            'name' => ['nullable','string','max:120'],
            'region' => ['nullable','string','max:50'],
        ]);
        Holiday::updateOrCreate(
            ['holiday_date' => $request->holiday_date, 'region' => $request->region],
            ['name' => $request->name]
        );
        return back()->with('success', __('Holiday saved'));
    }

    public function destroy($id)
    {
        $h = Holiday::findOrFail($id);
        $h->delete();
        return back()->with('success', __('Holiday deleted'));
    }
}

