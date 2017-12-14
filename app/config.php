<?php

return [
    'db' => [
        'dsn' => 'sqlite:biocard.sqlite3',
        'user' => '',
        'pass' => ''
    ],

    'cache' => [
        'provider' => '\BestLang\ext\cache\WinCache'
    ],

    'token' => [
        'provider' => '\BestLang\ext\token\JWT',
        'options' => [
            'signer' => '\Lcobucci\JWT\Signer\Hmac\Sha256',
            'key' => '!!!!!REPLACE_THIS!!!!!'
        ]
    ]
];