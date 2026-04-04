<?php

use App\Commands\Browse;
use Illuminate\Support\Facades\Storage;

uses(Tests\TestCase::class);

test('uses book title as filename when SLUGIFY_FILENAMES is false', function () {
    config(['app.slugifyFilenames' => false]);

    expect(Browse::getFilename('The Martian'))->toBe('The Martian.md');
});

test('slugifies filename when SLUGIFY_FILENAMES is true', function () {
    config(['app.slugifyFilenames' => true]);

    expect(Browse::getFilename('The Martian'))->toBe('the-martian.md');
});

test('preserves special characters in title when not slugifying', function () {
    config(['app.slugifyFilenames' => false]);

    expect(Browse::getFilename("It's Only Drowning"))->toBe("It's Only Drowning.md");
});

test('uses storage/app path when DEFAULT_OUTPUT_DIRECTORY is not set', function () {
    config(['app.outputDirectory' => null]);

    $path = Browse::getOutputPath('test.md');

    expect($path)->toContain('storage/app')->toEndWith('test.md');
});

test('uses custom directory when DEFAULT_OUTPUT_DIRECTORY is set', function () {
    config(['app.outputDirectory' => '/custom/path/']);

    expect(Browse::getOutputPath('test.md'))->toBe('/custom/path/test.md');
});

test('handles custom directory without trailing slash', function () {
    config(['app.outputDirectory' => '/custom/path']);

    expect(Browse::getOutputPath('test.md'))->toBe('/custom/path/test.md');
});

test('combines custom directory with slugified filename', function () {
    config([
        'app.outputDirectory' => '/output/',
        'app.slugifyFilenames' => true,
    ]);

    $filename = Browse::getFilename('The Martian');
    $path = Browse::getOutputPath($filename);

    expect($path)->toBe('/output/the-martian.md');
});

test('combines custom directory with title filename', function () {
    config([
        'app.outputDirectory' => '/output/',
        'app.slugifyFilenames' => false,
    ]);

    $filename = Browse::getFilename('The Martian');
    $path = Browse::getOutputPath($filename);

    expect($path)->toBe('/output/The Martian.md');
});
