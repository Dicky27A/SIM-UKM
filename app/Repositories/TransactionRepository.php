<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Collection;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function findByBookingId(string $bookingId)
    {
        return Transaction::where('booking_trx_id', $bookingId)->first();
    }

    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }
    public function getUserTansactions(int $userId)
    {
        return Transaction::width('pricing')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
