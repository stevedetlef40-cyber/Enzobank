<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InvestmentAsset;
use Illuminate\Http\Request;

class InvestmentOfferController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Investment Opportunities');
        $query = InvestmentAsset::where('status', true);
        if ($request->filled('type')) {
            $query->where('offering_type', $request->type);
        }
        $assets = $query->orderBy('name')->paginate(12);
        $assetPayload = $assets->getCollection()->map(function ($a) {
            return [
                'id'          => $a->id,
                'name'        => $a->name,
                'symbol'      => $a->symbol,
                'type'        => $a->offering_type,
                'risk'        => (int) $a->risk_score,
                'base_yield'  => (float) $a->base_yield,
                'tiers'       => $a->tiers,
                'maturities'  => $a->maturities,
            ];
        });
        return view('user.sections.investments.offers', compact('page_title', 'assets', 'assetPayload'));
    }
}
