<?php

/*
 * This file is part of the Laravel Rave package.
 *
 * (c) Oluwole Adebiyi - Flamez <flamekeed@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    'payment_gateways' => [
        'payfast' => [
            'merchant_id' => env('PAYFAST_MERCHANT_ID'),
            'merchant_key' => env('PAYFAST_MERCHANT_KEY'),
            'passphrase'  => env('PAYFAST_PASSPHRASE'),
            'testmode'    => env('PAYFAST_TESTMODE', true), // Change to false for live mode
        ],
    ],
];
