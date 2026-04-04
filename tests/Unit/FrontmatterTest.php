<?php

use App\Content;
use Tests\TestCase;

uses(TestCase::class);

function makeContent(array $attributes): Content
{
    return tap(new Content, fn ($c) => $c->forceFill($attributes));
}

test('frontmatter always includes title', function () {
    $content = makeContent(['BookTitle' => 'My Book']);

    $lines = $content->buildFrontmatter();

    expect($lines)->toContain('title: My Book')
        ->and($lines[0])->toBe('---')
        ->and(end($lines))->toBe('---');
});

test('uses Attribution as author when available', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'Attribution' => 'Jane Doe',
    ]);

    expect($content->buildFrontmatter())->toContain('author: Jane Doe');
});

test('omits author when Attribution is empty', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'Attribution' => null,
    ]);

    $lines = $content->buildFrontmatter();

    expect(implode("\n", $lines))->not->toContain('author:');
});

test('includes ISBN from database when valid ISBN-13', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'ISBN' => '9780743273565',
    ]);

    expect($content->buildFrontmatter())->toContain('isbn: 9780743273565');
});

test('includes ISBN from database when valid ISBN-10', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'ISBN' => '0743273567',
    ]);

    expect($content->buildFrontmatter())->toContain('isbn: 0743273567');
});

test('omits ISBN when value is not a valid ISBN', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'ISBN' => 'not-an-isbn',
    ]);

    $lines = $content->buildFrontmatter();

    expect(implode("\n", $lines))->not->toContain('isbn:');
});

test('omits ISBN when database value is empty', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'ISBN' => null,
    ]);

    $lines = $content->buildFrontmatter();

    expect(implode("\n", $lines))->not->toContain('isbn:');
});

test('includes dateStarted when LastTimeStartedReading is set', function () {
    config(['app.timezone' => 'UTC']);

    $content = makeContent([
        'BookTitle' => 'My Book',
        'LastTimeStartedReading' => '2024-01-15 10:00:00',
    ]);

    expect($content->buildFrontmatter())->toContain('dateStarted: 2024-01-15');
});

test('includes dateFinished when LastTimeFinishedReading is set', function () {
    config(['app.timezone' => 'UTC']);

    $content = makeContent([
        'BookTitle' => 'My Book',
        'LastTimeFinishedReading' => '2024-02-10 20:00:00',
    ]);

    expect($content->buildFrontmatter())->toContain('dateFinished: 2024-02-10');
});

test('omits dateStarted when LastTimeStartedReading is empty', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'LastTimeStartedReading' => null,
    ]);

    $lines = $content->buildFrontmatter();

    expect(implode("\n", $lines))->not->toContain('dateStarted:');
});

test('omits dateFinished when LastTimeFinishedReading is empty', function () {
    $content = makeContent([
        'BookTitle' => 'My Book',
        'LastTimeFinishedReading' => null,
    ]);

    $lines = $content->buildFrontmatter();

    expect(implode("\n", $lines))->not->toContain('dateFinished:');
});

test('converts read dates from UTC to configured timezone', function () {
    config(['app.timezone' => 'America/New_York']);

    $content = makeContent([
        'BookTitle' => 'My Book',
        // 2024-01-15 03:00:00 UTC = 2024-01-14 22:00:00 EST
        'LastTimeStartedReading' => '2024-01-15 03:00:00',
        // 2024-03-01 03:00:00 UTC = 2024-02-29 22:00:00 EST
        'LastTimeFinishedReading' => '2024-03-01 03:00:00',
    ]);

    $lines = $content->buildFrontmatter();

    expect($lines)->toContain('dateStarted: 2024-01-14')
        ->and($lines)->toContain('dateFinished: 2024-02-29');
});

test('all available metadata fields appear in correct order', function () {
    config(['app.timezone' => 'UTC']);

    $content = makeContent([
        'BookTitle' => 'My Book',
        'Attribution' => 'Jane Doe',
        'ISBN' => '9780743273565',
        'LastTimeStartedReading' => '2024-01-15 00:00:00',
        'LastTimeFinishedReading' => '2024-02-10 00:00:00',
    ]);

    $lines = $content->buildFrontmatter();

    expect($lines[0])->toBe('---')
        ->and($lines[1])->toBe('title: My Book')
        ->and($lines[2])->toBe('author: Jane Doe')
        ->and($lines[3])->toBe('isbn: 9780743273565')
        ->and($lines[4])->toBe('dateStarted: 2024-01-15')
        ->and($lines[5])->toBe('dateFinished: 2024-02-10')
        ->and($lines[6])->toBe('---');
});
