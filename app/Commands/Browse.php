<?php

namespace App\Commands;

use App\Services\Kobo\Reader;
use LaravelZero\Framework\Commands\Command;
use Carbon\Carbon;
use lywzx\epub\EpubParser;

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
            /* @var EpubParser $parsedEpubData */
            $parsedEpubData = $selectedBook->getEpubData();
            $epubMeta = $parsedEpubData->getDcItem();
        } catch (\Throwable $exception) {

        }

        // TODO: find read time
        // TODO: find finished time
        $this->line('---');
        $this->line('title: ' . $selectedBook->BookTitle);

        if (isset($epubMeta)) {
            if (isset($epubMeta['creator'])) {
                $this->line('author: ' . $epubMeta['creator']);
            }

            if (isset($epubMeta['date'])) {
                $this->line('publicationDate: ' . $epubMeta['date']);
            }

            if (isset($epubMeta['source'])) {
                $this->line('isbn: ' . $epubMeta['source']);
            }
        }

        $this->line('---');

        $selectedBook->clippings()->each(function ($highlight) {
            // TODO: find location or page number
            $this->newLine();
            $this->line('> ' . trim($highlight->Text));
            $this->newLine();

            $formattedDate = Carbon::parse($highlight->DateCreated)->format('n/j/y \a\t g:ia');

            //if ($chapterTitle = $highlight->getChapterTitle()) {
                //$this->line('– ' . $formattedDate . ', *' . $chapterTitle . '*');
            //} else {
                $this->line('– ' . $formattedDate);
            //}

            $this->newLine();
        });
    }
}
