<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserBankDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankDetailsController extends Controller
{
    public function index()
    {
        $page_title = 'Bank Details';
        $user = Auth::user()->load('bankDetails');
        $countries = $this->worldCountries();

        return view('user.sections.bank-details.index', compact('page_title', 'user', 'countries'));
    }

    /**
     * Load the list of world countries for the country select.
     */
    private function worldCountries(): array
    {
        $path = resource_path('world/countries.json');
        if (! file_exists($path)) {
            return [];
        }
        $data = json_decode(file_get_contents($path), true) ?: [];

        return array_values(array_unique(array_filter(array_column($data, 'name'))));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number_iban' => 'required|string|max:34',
            'country' => 'required|string|max:100',
            'swift_bic' => 'nullable|string|max:11',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validate();
        $validated['user_id'] = Auth::id();

        UserBankDetail::create($validated);

        return back()->with(['success' => ['Bank detail added successfully.']]);
    }

    public function update(Request $request, $id)
    {
        $detail = UserBankDetail::where('user_id', Auth::id())->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'recipient_name' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_number_iban' => 'required|string|max:34',
            'country' => 'required|string|max:100',
            'swift_bic' => 'nullable|string|max:11',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $detail->update($validator->validate());

        return back()->with(['success' => ['Bank detail updated successfully.']]);
    }

    public function destroy($id)
    {
        $detail = UserBankDetail::where('user_id', Auth::id())->findOrFail($id);
        $detail->delete();

        return back()->with(['success' => ['Bank detail removed.']]);
    }

    public function toggleStatus($id)
    {
        $detail = UserBankDetail::where('user_id', Auth::id())->findOrFail($id);
        $detail->status = $detail->status ? 0 : 1;
        $detail->save();

        return back()->with(['success' => ['Bank detail status updated.']]);
    }
}
