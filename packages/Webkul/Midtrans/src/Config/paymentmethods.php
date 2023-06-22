<?php

return [
    'midtrans'  => [
        'code'        => 'midtrans',
        'title'       => 'Midtrans',
        'description' => 'Midtrans',
        'class'       => 'Webkul\Midtrans\Payment\Midtrans',
        'active'      => false,
        'sort'        => 1,
    ],
    'midtrans_bank_transfer' => [
        'code' => 'midtrans.bank_transfer',
        'title' => 'ATM/Bank Transfer',
        'description' => 'Bayar dengan ATM atau Transfer Bank',
        'class' => 'Webkul\Midtrans\Payment\BankTransfer',
        'active' => true,
        'sort' => 2
    ],
    'midtrans_credit_card' => [
        'code' => 'midtrans.credit_card',
        'title' => 'Kartu Kredit',
        'description' => 'Bayar dengan Kartu Kredit',
        'class' => 'Digital88\BagistoMidtrans\Payment\CreditCard',
        'active' => false,
        'sort' => 3
    ],
    'midtrans_over_the_counter' => [
        'code' => 'midtrans.over_the_counter',
        'title' => 'Toko Retail',
        'description' => 'Bayar di Toko Retail (Indomaret atau Alfamart)',
        'class' => 'Digital88\BagistoMidtrans\Payment\OverTheCounter',
        'active' => false,
        'sort' => 4
    ],
    'midtrans_e_wallet' => [
        'code' => 'midtrans.e_wallet',
        'title' => 'Dompet Digital',
        'description' => 'Bayar dengan  Dompet Digital (Qris, Gopay atau ShopeePay)',
        'class' => 'Digital88\BagistoMidtrans\Payment\EWallet',
        'active' => false,
        'sort' => 5
    ],
    'midtrans_akulaku' => [
        'code' => 'midtrans.akulaku',
        'title' => 'Akulaku',
        'description' => 'Bayar dengan Akulaku',
        'class' => 'Digital88\BagistoMidtrans\Payment\Akulaku',
        'active' => false,
        'sort' => 6
    ],
];
