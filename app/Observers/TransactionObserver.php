<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Helpers\TransactionHelper;

class TransactionObserver
{
    //
    public function creating($transaction)
    {
        $transaction->boking_trx_id = TransactionHelper::generateUniqueTrxId();
    }

    /**
     * Handle the Transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        // Logic after a transaction is created
    }

    public function update(Transaction $transaction): void
    {

    }

    public function deleted(Transaction $transaction): void
    {

    }
    public function restored(Transaction $transaction): void
    {

    }

}
