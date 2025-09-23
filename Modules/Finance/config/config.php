<?php

return [
    'name' => 'Finance',
    'modules' => [
        'Finance' => [
            'providers' => [
                // ... diğer provider'lar
                \Modules\Finance\Providers\AuthServiceProvider::class, // BU SATIRI EKLEYİN
            ],
            ]]
];
