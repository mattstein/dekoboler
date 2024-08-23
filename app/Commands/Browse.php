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
            $filename = Str::slug($selectedBookTitle).'.md';
            $content = $selectedBook->getClippingsAsMarkdown();

            if (Storage::disk('local')->put($filename, $content)) {
                $this->line('Saved '.Storage::disk('local')->path($filename).'.');
            } else {
                $this->error('Couldn’t save the file.');
            }
        }
    }
}
