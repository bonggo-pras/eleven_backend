<?php

return [
    'midtrans_bank_transfer' => [
        'code' => 'midtrans.bank_transfer',
        'title' => 'ATM/Bank Transfer',
        'description' => 'Bayar dengan ATM atau Transfer Bank (BCA atau BRI)',
        'class' => 'Webkul\Midtrans\Payment\BankTransfer',
        'active' => true,
        'sort' => 1
    ],
    'midtrans_e_wallet' => [
        'code' => 'midtrans.e_wallet',
        'title' => 'Dompet Digital',
        'description' => 'Bayar dengan Dompet Digital (Qris)',
        'class' => 'Webkul\Midtrans\Payment\EWallet',
        'active' => true,
        'sort' => 2
    ],
];
