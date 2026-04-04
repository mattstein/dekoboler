<?php

use App\Providers\AppServiceProvider;

return [
    'name' => 'Dekoboler',
    'version' => app('git.version'),
    'env' => 'development',
    'timezone' => env('TIMEZONE', 'UTC'),
    'slugifyFilenames' => env('SLUGIFY_FILENAMES', false),
    'outputDirectory' => env('DEFAULT_OUTPUT_DIRECTORY', null),
    'ePubDir' => '/Volumes/KOBOeReader/.kobo/kepub',
    'providers' => [
        AppServiceProvider::class,
    ],
];
