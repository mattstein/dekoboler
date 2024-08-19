<?php

return [
    'name' => 'Dekoboler',
    'version' => app('git.version'),
    'env' => 'development',
    'ePubDir' => '/Volumes/KOBOeReader/.kobo/kepub',
    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],
];
