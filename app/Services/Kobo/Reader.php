<?php

namespace App\Services\Kobo;

use App\Bookmark;
use App\Content;
use lywzx\epub\EpubParser;

class Reader
{
    public function getBooks(): \Illuminate\Support\Collection
    {
        return Content::whereNotNull('BookTitle')
            ->groupBy('BookTitle')
            ->get();
    }

    public function getClippingsForBook($book): \Illuminate\Support\Collection
    {
        return Bookmark::where('VolumeID', $book->BookID)
            ->orderBy('DateCreated')
            ->get();
    }

    /**
     * @throws \Exception
     */
    public function getParsedEpubForBook($book): bool|array|string
    {
        $ebookPath = config('app.ePubDir') . '/' . $book->BookID;
        $parser = new EpubParser($ebookPath);
        $parser->parse();

        return $parser->getDcItem();
    }
}