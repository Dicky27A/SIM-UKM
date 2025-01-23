<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Pricing;
use illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    public function prepareCheckout(Pricing $pricing)
    {
        $user = Auth::user();
        $alReadySubscribed = $pricing->isSubscribedByUser($user->id);

        $tax =  0.1;
        $total_tax_amount = $pricing->price * $tax;
        $sub_total_amount = $pricing->price;
        $grand_total_amount = $sub_total_amount + $total_tax_amount;

        $started_at = now();
        $ended_at = $started_at->copy()->addMonths($pricing->duration);

        session()->put('pricing_id', $pricing->id);

        return compact(
            'total_tax_amount',
            'grand_total_amount',
            'sub_total_amount',
            'pricing',
            'user',
            'alReadySubscribed',
            'started_at',
            'ended_at'
        );
    }

    public function getRecentPricing()
    {
        $pricingId = session()->get('pricing_id');
        return Pricing::find($pricingId);
    }

    public function getUserTransactions()
    {
        $user = Auth::user();

        // if (!$user) {
        //     return collect();
        // }

        // return $this->transactionRepository->getUserTransactions($user->id);

        return Transaction::width('pricing')
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
    }
}
