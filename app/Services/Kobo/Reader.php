<?php

namespace App\Services\Kobo;

use App\Bookmark;
use App\Content;

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
}
