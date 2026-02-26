<?php

return [
    'name' => 'Dekoboler',
    'version' => app('git.version'),
    'env' => 'development',
    'timezone' => env('TIMEZONE', 'UTC'),
    'ePubDir' => '/Volumes/KOBOeReader/.kobo/kepub',
    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],
];
