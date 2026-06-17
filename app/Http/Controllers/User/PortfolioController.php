<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\InvestmentAsset;
use App\Models\Portfolio;
use App\Models\PortfolioHolding;
use App\Models\PortfolioTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Investment Portfolio');
        $query = Portfolio::withCount('holdings')->where('user_id', Auth::id());
        $portfolios = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('user.sections.portfolios.index', compact('page_title', 'portfolios'));
    }

    public function create()
    {
        $page_title = __('Create Portfolio');
        return view('user.sections.portfolios.create', compact('page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        Portfolio::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
        ]);
        return redirect()->route('user.portfolios.index')->with('success', __('Portfolio created.'));
    }

    public function show($id)
    {
        $portfolio = Portfolio::with(['holdings.asset', 'transactions.asset'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        $page_title = $portfolio->name;
        $assets = InvestmentAsset::where('status', true)->orderBy('name')->get();
        return view('user.sections.portfolios.show', compact('page_title', 'portfolio', 'assets'));
    }

    public function edit($id)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);
        $page_title = __('Edit Portfolio');
        return view('user.sections.portfolios.edit', compact('page_title', 'portfolio'));
    }

    public function update(Request $request, $id)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);
        $portfolio->update(['name' => $request->name]);
        return redirect()->route('user.portfolios.index')->with('success', __('Portfolio updated.'));
    }

    public function destroy($id)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);
        $portfolio->delete();
        return redirect()->route('user.portfolios.index')->with('success', __('Portfolio deleted.'));
    }

    public function addHolding(Request $request, $id)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);
        $request->validate([
            'asset_id' => ['required', 'exists:investment_assets,id'],
            'quantity' => ['required', 'numeric', 'min:0.00000001'],
            'price'    => ['required', 'numeric', 'min:0'],
        ]);

        $holding = PortfolioHolding::firstOrCreate(
            ['portfolio_id' => $portfolio->id, 'investment_asset_id' => $request->asset_id],
            ['quantity' => 0, 'avg_cost' => 0]
        );

        $totalCost = ($holding->quantity * $holding->avg_cost) + ($request->quantity * $request->price);
        $newQty = $holding->quantity + $request->quantity;
        $holding->quantity = $newQty;
        $holding->avg_cost = $newQty > 0 ? ($totalCost / $newQty) : 0;
        $holding->save();

        PortfolioTransaction::create([
            'portfolio_id'        => $portfolio->id,
            'investment_asset_id' => $request->asset_id,
            'type'                => 'buy',
            'quantity'            => $request->quantity,
            'price'               => $request->price,
            'fee'                 => $request->input('fee', 0),
            'executed_at'         => now(),
        ]);

        return back()->with('success', __('Holding added.'));
    }

    public function removeHolding(Request $request, $id)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);
        $request->validate([
            'holding_id' => ['required', 'exists:portfolio_holdings,id'],
        ]);
        $holding = PortfolioHolding::where('portfolio_id', $portfolio->id)->findOrFail($request->holding_id);
        $holding->delete();
        return back()->with('success', __('Holding removed.'));
    }

    public function performance($id)
    {
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);
        $holdings = $portfolio->holdings()->with('asset')->get();
        $allocation = [];
        $totalValue = 0;
        foreach ($holdings as $h) {
            $value = (float) $h->quantity * (float) ($h->asset->current_price ?? 0);
            $totalValue += $value;
            $type = $h->asset->asset_type ?? 'other';
            $allocation[$type] = ($allocation[$type] ?? 0) + $value;
        }

        $series = [];
        foreach ($allocation as $type => $value) {
            $series[] = ['name' => $type, 'y' => $value];
        }

        $transactions = $portfolio->transactions()->orderBy('executed_at')->get(['executed_at', 'type', 'quantity', 'price']);
        $timeline = $transactions->map(function ($t) {
            return [
                'x' => optional($t->executed_at)->format('Y-m-d H:i'),
                'y' => (float) $t->quantity * (float) $t->price * ($t->type === 'sell' ? -1 : 1),
            ];
        });

        return response()->json([
            'total_value' => $totalValue,
            'allocation'  => $series,
            'timeline'    => $timeline,
        ]);
    }
}

