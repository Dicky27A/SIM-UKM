<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Services\TransactionService;
use App\Repositories\PricingRepositoryInterface;

class FrontController extends Controller
{
    protected $transactionService;
    protected $paymentService;
    protected $pricingRepository;

    public function __construct(
        PaymentService $paymentService,
        TransactionService $transactionService,
        PricingRepositoryInterface $pricingRepository
    ) {
        $this->paymentService = $paymentService;
        $this->transactionService = $transactionService;
        $this->pricingRepository = $pricingRepository;
    }

    public function index()
    {
        $pricings = $this->pricingRepository->getAll();
        return view('frontindex', compact('pricings'));
    }
}
