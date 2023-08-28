<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    protected $currencies;
    public function index(Request $request)
    {
        $currency = new Currency();
        $validator = Validator::make($request->all(), [
            'source' => ['required', Rule::in(array_keys($currency->getCurrencies()))],
            'target' => ['required', Rule::in(array_keys($currency->getCurrencies()))],
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response('Error', Response::HTTP_BAD_REQUEST);
        }
        $data = $validator->validated();
        $source = $data['source'];
        $target = $data['target'];
        $source_amount = $data['amount'];
        $currency->setSource($source, $source_amount)->setTarget($target);
        $amount = $currency->transferCurrency();
        $msg = 'success';
        return response()->json(['msg' => $msg, 'amount' => $amount]);
    }

    private function getCurrency($source, $target)
    {
    }
}
