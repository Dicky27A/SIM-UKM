<?php

namespace App\Services;

use App\Helpers\TransactionHelper;
use App\Models\Payment;
use App\Models\pricing;
use App\Repositories\PricingRepository as RepositoriesPricingRepository;
use App\Repositories\TransactionRepository;
use App\Repository\PricingRepository;
use Faker\Provider\ar_EG\Payment as Ar_EGPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    protected $midtransService;
    protected $pricingRepository;
    protected $transactionRepository;

    public function __construct(
        MidtransService $midtransService,
        RepositoriesPricingRepository $pricingRepository,
        TransactionRepository $transactionRepository
    )
    {
        $this->midtransService = $midtransService;
        $this->pricingRepository = $pricingRepository;
        $this->transactionRepository = $transactionRepository;
        return 'Payment created successfully';
    }

    public function createPayment(int $pricingId)
    {
        $user = Auth::user();
        // $pricing = pricing::findOrfail($pricingId);
        $pricing = $this->pricingRepository->findById($pricingId);

        $tax = 0.11;
        $totalTax = $pricing->price * $tax;
        $grandTotal = $pricing->price + $totalTax;

        $params =  [
            'transaction_details' => [
                'order_id' => TransactionHelper::generateUniqueTrxId(),
                'gross_amount' => (int) $grandTotal,
            ],
            'customer_details' => [
                'frist_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => $pricing->id,
                    'price' => $pricing->price,
                    'quantity' => 1,
                    'name' => $pricing->name,
                ],
                [
                    'id' => 'tax',
                    'price' => $totalTax,
                    'quantity' => 1,
                    'name' => 'PPN 11%',
                ]
            ],
            'custom_field1' => $user->id,
            'custom_field2' => $pricing->id,
        ];
        return $this->midtransService->createSnapToken($params);
    }

    public function handleNotification()
    {
        $notification = $this->midtransService->handleNotification();

        if (in_array($notification['transaction_status'], ['capture', 'settlement'])) {
            $pricing = Pricing::findOrfail($notification['custom_field2']);

            $this->createTransaction($notification, $pricing);
        }

        return $notification['transaction_status'];
    }

    public function createTransaction(array $notification, Pricing $pricing)
    {
        $startedAt = now();
        $endedAt = $startedAt->copy()->addMonth($pricing->duration);

        $transactionData = [
            'user_id' => $notification['custom_field1'],
            'pricing_id' => $notification['custom_field2'],
            'sub_total_amount' => $pricing->price,
            'tax_amount' => $pricing->price * 0.11,
            'grand_total_amount' => $notification['gross_amount'],
            'payment_type' => 'Midtrans',
            'is_paid' => true,
            'boking_trx_id' => $notification['order_id'],
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ];

        $this->transactionRepository->create($transactionData);

        Log::info('Transaction created successfully: ' . $notification['order_id'] );
    }

}
