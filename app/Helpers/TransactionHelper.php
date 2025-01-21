<?php

namespace App\Helpers;

use App\Models\Transaction;

class TransactionHelper

{
    public static function generateUniqueTrxId(): string
    {
        $prefix = 'PS';
        do {
            $trxId = $prefix . mt_rand(100000, 999999);
        } while (Transaction::where('boking_trx_id', $trxId)->exists());

        return $trxId;
    }
}
