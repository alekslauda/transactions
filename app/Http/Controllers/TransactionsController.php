<?php

namespace App\Http\Controllers;

use App\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class TransactionsController extends Controller
{
    public function index()
    {
        $transactions = Transactions::all()->load(['sender', 'receiver']);

        return response()->json(
            [
                'status' => 'success',
                'data' => $transactions->toArray()
            ], 200);
    }

    public function paymentTypes()
    {
        return response()->json(
            [
                'status' => 'success',
                'data' => Transactions::PAYMENT_TYPES
            ], 200);
    }

    public function statuses()
    {
        return response()->json(
            [
                'status' => 'success',
                'data' => Transactions::STATUSES
            ], 200);
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'toAccount' => 'required',
            'fromAccount' => 'required',
            'amount' => 'required|numeric',
            'selectedPayment' => [
                'required',
                Rule::in(array_keys(Transactions::PAYMENT_TYPES))
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'data' => $validator->errors(),
                    'message' => 'The given data was invalid'
                ], 422);
        }

        $transaction = Transactions::create([
            'status' => Transactions::setStatus('Sent'),
            'payment_type' => $data['selectedPayment'],
            'amount' => round($data['amount'], 2),
            'from_id' => $data['meta']['fromAccountId'],
            'to_id' => $data['meta']['toAccountId'],
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $transaction->toArray()
        ], 200);
    }

    public function transaction(Transactions $transaction)
    {
        return response()->json([
            'status' => 'success',
            'data' => $transaction->load(['sender', 'receiver'])->toArray()
        ], 200);
    }

    public function update(Transactions $transaction, Request $request)
    {
        $transaction->update($request->all());

        return response()->json([
            'status' => 'success',
            'data' => $transaction->load(['sender', 'receiver'])->toArray()
        ], 200);
    }
}
