<?php

return [
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=bestlang_test',
        'user' => 'bestlang',
        'pass' => 'BestLang'
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