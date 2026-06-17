<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanProduct;
use Illuminate\Http\Request;

class LoanProductController extends Controller
{
    public function index(Request $request)
    {
        $page_title = __('Loan Products');
        $query = LoanProduct::query()
            ->when($request->get('q'), function ($q) use ($request) {
                $term = $request->get('q');
                $q->where('name', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%");
            })
            ->orderBy('name');
        $products = $query->paginate(15);
        return view('admin.sections.loan-products.index', compact('page_title', 'products'));
    }

    public function create()
    {
        $page_title = __('Add Loan Product');
        return view('admin.sections.loan-products.create', compact('page_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'term_months'   => ['required', 'integer', 'min:1'],
            'min_amount'    => ['required', 'numeric', 'min:0'],
            'max_amount'    => ['required', 'numeric', 'gte:min_amount'],
            'description'   => ['nullable', 'string'],
            'status'        => ['required', 'boolean'],
        ]);
        LoanProduct::create($request->only('name', 'interest_rate', 'term_months', 'min_amount', 'max_amount', 'description', 'status'));
        return redirect()->route('admin.loan.products.index')->with('success', __('Product created.'));
    }

    public function edit($id)
    {
        $product = LoanProduct::findOrFail($id);
        $page_title = __('Edit Loan Product');
        return view('admin.sections.loan-products.edit', compact('page_title', 'product'));
    }

    public function update(Request $request, $id)
    {
        $product = LoanProduct::findOrFail($id);
        $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'interest_rate' => ['required', 'numeric', 'min:0'],
            'term_months'   => ['required', 'integer', 'min:1'],
            'min_amount'    => ['required', 'numeric', 'min:0'],
            'max_amount'    => ['required', 'numeric', 'gte:min_amount'],
            'description'   => ['nullable', 'string'],
            'status'        => ['required', 'boolean'],
        ]);
        $product->update($request->only('name', 'interest_rate', 'term_months', 'min_amount', 'max_amount', 'description', 'status'));
        return redirect()->route('admin.loan.products.index')->with('success', __('Product updated.'));
    }

    public function destroy($id)
    {
        $product = LoanProduct::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.loan.products.index')->with('success', __('Product deleted.'));
    }
}

