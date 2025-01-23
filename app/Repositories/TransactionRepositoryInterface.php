<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
    public function findByBookingId(string $bookingId);
    public function create(array $data): Transaction;
    public function getUserTansactions(int $userId);
}
