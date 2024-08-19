<?php

namespace App\Commands;

use App\Services\Kobo\Reader;
use LaravelZero\Framework\Commands\Command;
use Carbon\Carbon;

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
    protected $description = 'Browse Kobo books and print highlights';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        $reader = new Reader();

        $books = $reader->getBooks();
        $selectedBookTitle = $this->choice(
            'Which book?',
            $books->map(function ($book) { return $book->BookTitle; })->all(),
        );

        $selectedBook = $books->where('BookTitle', $selectedBookTitle)->first();

        try {
            $parsedEpubData = $reader->getParsedEpubForBook($selectedBook);
        } catch (\Throwable $exception) {

        }

        // TODO: find read time
        // TODO: find finished time
        $this->line('---');
        $this->line('title: ' . $selectedBook->BookTitle);

        if (isset($parsedEpubData)) {
            if (isset($parsedEpubData['creator'])) {
                $this->line('author: ' . $parsedEpubData['creator']);
            }

            if (isset($parsedEpubData['date'])) {
                $this->line('publicationDate: ' . $parsedEpubData['date']);
            }

            if (isset($parsedEpubData['source'])) {
                $this->line('isbn: ' . $parsedEpubData['source']);
            }
        }

        $this->line('---');

        $highlights = $reader->getClippingsForBook($selectedBook);

        $highlights->each(function ($highlight) {
            // TODO: find location or page number
            $this->newLine();
            $this->line('> ' . trim($highlight->Text));
            $this->newLine();
            $this->line('– ' . Carbon::parse($highlight->DateCreated)->format('n/j/y \a\t g:ia'));
            $this->newLine();
        });
    }
}
