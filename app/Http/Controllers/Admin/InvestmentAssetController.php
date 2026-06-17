<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestmentAsset;
use Illuminate\Http\Request;

class InvestmentAssetController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Investment Assets');
        $q = InvestmentAsset::query();
        if ($request->filled('q')) {
            $term = $request->get('q');
            $q->where('name','like',"%{$term}%")->orWhere('symbol','like',"%{$term}%");
        }
        $assets = $q->orderBy('name')->paginate(20);
        return view('admin.sections.investment-assets.index', compact('page_title','assets'));
    }

    public function create()
    {
        $page_title = __('Create Investment Asset');
        return view('admin.sections.investment-assets.create', compact('page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:120'],
            'symbol' => ['required','string','max:40','unique:investment_assets,symbol'],
            'asset_type' => ['required','in:stock,fund,bond,crypto,cash'],
            'offering_type' => ['required','in:fixed_deposit,mutual_fund,gov_bond,corp_bond,stock,retirement'],
            'risk_level' => ['nullable','string','max:50'],
            'risk_score' => ['required','integer','min:1','max:5'],
            'current_price' => ['required','numeric','min:0'],
            'base_yield' => ['nullable','numeric','min:0'],
            'tiers' => ['nullable','string'],
            'maturities' => ['nullable','string'],
            'status' => ['required','boolean'],
        ]);
        InvestmentAsset::create([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'asset_type' => $request->asset_type,
            'offering_type' => $request->offering_type,
            'risk_level' => $request->risk_level,
            'risk_score' => $request->risk_score,
            'current_price' => $request->current_price,
            'base_yield' => $request->base_yield ?? 0,
            'tiers' => $request->tiers ? json_decode($request->tiers, true) : null,
            'maturities' => $request->maturities ? json_decode($request->maturities, true) : null,
            'status' => $request->boolean('status'),
        ]);
        return redirect()->route('admin.investment.assets.index')->with('success', __('Asset created'));
    }

    public function edit($id)
    {
        $asset = InvestmentAsset::findOrFail($id);
        $page_title = __('Edit Investment Asset');
        return view('admin.sections.investment-assets.edit', compact('page_title','asset'));
    }

    public function update(Request $request, $id)
    {
        $asset = InvestmentAsset::findOrFail($id);
        $request->validate([
            'name' => ['required','string','max:120'],
            'symbol' => ['required','string','max:40','unique:investment_assets,symbol,'.$asset->id],
            'asset_type' => ['required','in:stock,fund,bond,crypto,cash'],
            'offering_type' => ['required','in:fixed_deposit,mutual_fund,gov_bond,corp_bond,stock,retirement'],
            'risk_level' => ['nullable','string','max:50'],
            'risk_score' => ['required','integer','min:1','max:5'],
            'current_price' => ['required','numeric','min:0'],
            'base_yield' => ['nullable','numeric','min:0'],
            'tiers' => ['nullable','string'],
            'maturities' => ['nullable','string'],
            'status' => ['required','boolean'],
        ]);
        $asset->update([
            'name' => $request->name,
            'symbol' => $request->symbol,
            'asset_type' => $request->asset_type,
            'offering_type' => $request->offering_type,
            'risk_level' => $request->risk_level,
            'risk_score' => $request->risk_score,
            'current_price' => $request->current_price,
            'base_yield' => $request->base_yield ?? 0,
            'tiers' => $request->tiers ? json_decode($request->tiers, true) : null,
            'maturities' => $request->maturities ? json_decode($request->maturities, true) : null,
            'status' => $request->boolean('status'),
        ]);
        return redirect()->route('admin.investment.assets.index')->with('success', __('Asset updated'));
    }

    public function destroy($id)
    {
        $asset = InvestmentAsset::findOrFail($id);
        $asset->delete();
        return back()->with('success', __('Asset deleted'));
    }
}

