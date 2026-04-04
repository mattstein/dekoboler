<?php

namespace App\Commands;

use App\Services\Kobo\Reader;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use LaravelZero\Framework\Commands\Command;

class Browse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'browse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Browse Kobo books and highlights';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $reader = new Reader;

        $books = $reader->getBooks();
        $selectedBookTitle = $this->choice(
            'Which book?',
            $books->map(function ($book) {
                return $book->BookTitle;
            })->all(),
        );

        $selectedBook = $books
            ->where('BookTitle', $selectedBookTitle)
            ->first();

        $selectedAction = $this->choice(
            'What do you want to do with clippings?',
            [
                'view',
                'save',
            ],
        );

        if ($selectedAction === 'view') {
            echo $selectedBook->getClippingsAsMarkdown();
        } else {
            $filename = static::getFilename($selectedBookTitle);
            $content = $selectedBook->getClippingsAsMarkdown();
            $path = static::getOutputPath($filename);

            if (file_put_contents($path, $content) !== false) {
                $this->line('Saved '.$path.'.');
            } else {
                $this->error("Couldn't save the file.");
            }
        }
    }

    public static function getFilename(string $title): string
    {
        return config('app.slugifyFilenames')
            ? Str::slug($title).'.md'
            : $title.'.md';
    }

    public static function getOutputPath(string $filename): string
    {
        $outputDir = config('app.outputDirectory');

        if ($outputDir) {
            return rtrim($outputDir, '/').'/'.$filename;
        }

        return Storage::disk('local')->path($filename);
    }
}
