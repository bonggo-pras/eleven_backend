<?php

return [
    [
        'key'    => 'sales.paymentmethods.midtrans',
        'name'   => 'Midtrans',
        'sort'   => 0,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'admin::app.admin.system.status',
                'type'          => 'boolean',
                'validation'    => 'required',
                'channel_based' => false,
                'locale_based'  => true
            ],
            [
                'name' => 'server_key',
                'title' => 'Server Key',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ],
            [
                'name' => 'client_key',
                'title' => 'Client Key',
                'type' => 'text',
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ],
            [
                'name' => 'environment',
                'title' => 'Environment',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Sandbox',
                        'value' => 'sandbox'
                    ],
                    [
                        'title' => 'Production',
                        'value' => 'production'
                    ]
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ],
            [
                'name' => 'sanitize',
                'title' => 'Sanitize',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Enabled',
                        'value' => true
                    ],
                    [
                        'title' => 'Disabled',
                        'value' => false
                    ]
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ],
            [
                'name' => '3ds',
                'title' => '3D Secure',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'Enabled',
                        'value' => true
                    ],
                    [
                        'title' => 'Disabled',
                        'value' => false
                    ]
                ],
                'validation' => 'required',
                'channel_based' => false,
                'locale_based' => false
            ],
        ]
    ]
];
