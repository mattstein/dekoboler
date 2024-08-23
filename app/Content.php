<?php

namespace App;

use Carbon\Carbon;
use ePub\Definition\Package;
use ePub\Reader;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 * @property-read string $ContentID
 * @property-read string $ContentType `9` or `899` only?
 * @property-read string $MimeType
 * @property-read string $BookID
 * @property-read string $BookTitle
 * @property-read string $ImageId
 * @property-read string $Title
 * @property-read string $Attribution
 * @property-read string $Description
 * @property-read string $DateCreated
 * @property-read string $ShortCoverKey
 * @property-read string $adobe_location
 * @property-read string $Publisher
 * @property-read bool $IsEncrypted
 * @property-read string $DateLastRead
 * @property-read bool $FirstTimeReading
 * @property-read string $ChapterIDBookmarked
 * @property-read bool $ParagraphBookmarked
 * @property-read int $BookmarkWordOffset
 * @property-read int $NumShortcovers
 * @property-read int $VolumeIndex
 * @property-read int $ReadStatus
 * @property-read string $PublicationId
 * @property-read int $FavouritesIndex “empty” type in database and always seems to be `-1` (?)
 * @property-read int $Accessibility
 * @property-read string $ContentURL
 * @property-read string $Language
 * @property-read string $BookshelfTags
 * @property-read int $IsDownloaded
 * @property-read int $FeedbackType
 * @property-read int $AverageRating
 * @property-read int $Depth
 * @property-read string $PageProgressDirection
 * @property-read bool $InWishlist
 * @property-read string $ISBN
 * @property-read string $WishlistedDate
 * @property-read int $FeedbackTypeSynced
 * @property-read bool $IsSocialEnabled
 * @property-read int $EpubType
 * @property-read int $Monetization
 * @property-read string $ExternalId
 * @property-read string $Series
 * @property-read string $SeriesNumber
 * @property-read string $Subtitle
 * @property-read int $WordCount
 * @property-read string $Fallback
 * @property-read int $RestOfBookEstimate
 * @property-read int $CurrentChapterEstimate
 * @property-read float $CurrentChapterProgress
 * @property-read int $PocketStatus
 * @property-read string $UnsyncedPocketChanges
 * @property-read string $ImageUrl
 * @property-read string $DateAdded
 * @property-read string $WorkId
 * @property-read string $Properties
 * @property-read string $RenditionSpread
 * @property-read int $RatingCount
 * @property-read string $ReviewsSyncDate
 * @property-read string $MediaOverlay
 * @property-read string $MediaOverlayType
 * @property-read string $RedirectPreviewUrl
 * @property-read int $PreviewFileSize
 * @property-read string $EntitlementId
 * @property-read string $CrossRevisionId
 * @property-read string $DownloadUrl
 * @property-read bool $ReadStateSynced
 * @property-read int $TimesStartedReading
 * @property-read int $TimesSpentReading
 * @property-read string $LastTimeStartedReading
 * @property-read string $LastTimeFinishedReading
 * @property-read string $ApplicableSubscriptions
 * @property-read string $ExternalIds
 * @property-read string $PurchaseRevisionId
 * @property-read string $SeriesID
 * @property-read float $SeriesNumberFloat
 * @property-read string $AdobeLoanExpiration
 * @property-read bool $HideFromHomePage
 * @property-read bool $IsInternetArchive
 * @property-read string $titleKana
 * @property-read string $subtitleKana
 * @property-read string $seriesKana
 * @property-read string $attributionKana
 * @property-read string $publisherKana
 * @property-read bool $IsPurchaseable
 * @property-read bool $IsSupported
 * @property-read string $AnnotationsSyncToken
 * @property-read string $DateModified
 * @property-read int $StorePages
 * @property-read int $StoreWordCount
 * @property-read int $StoreTimeToReadLowerEstimate
 * @property-read int $StoreTimeToReadUpperEstimate
 * @property-read int $Duration
 * @property-read bool $IsAbridged
 * @property-read int $SyncConflictType
 */
class Content extends Model
{
    use ReadOnlyTrait;

    protected $table = 'content';

    private Package $epubData;

    public function getEpubPath(): string
    {
        return config('app.ePubDir').'/'.$this->BookID;
    }

    /**
     * @throws \Exception
     */
    public function getEpubData(): Package
    {
        if ($this->epubData !== null) {
            return $this->epubData;
        }

        $reader = new Reader;

        $parsed = $reader->load($this->getEpubPath());

        return $this->epubData = $parsed;
    }

    public function clippings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class, 'VolumeID', 'BookID')
            ->orderBy('DateCreated');
    }

    public function getClippingsAsMarkdown($rawLines = false): string|\Illuminate\Support\Collection
    {
        $lines = collect([]);

        try {
            $epubData = $this->getEpubData();
        } catch (\Throwable $exception) {

        }

        // TODO: find read time
        // TODO: find finished time
        $lines->push('---');
        $lines->push('title: '.$this->BookTitle);

        if (isset($epubData)) {
            $metaData = $epubData->getMetadata();

            if ($metaData->has('creator')) {
                $lines->push('author: '.$metaData->getValue('creator'));
            }

            if ($metaData->has('date')) {
                $lines->push('publicationDate: '.$metaData->getValue('date'));
            }

            if ($metaData->has('publisher')) {
                $lines->push('publisher: '.$metaData->getValue('publisher'));
            }

            if ($metaData->has('source')) {
                $lines->push('isbn: '.$metaData->getValue('source'));
            }
        }

        $lines->push('---');

        $this->clippings()->each(function ($highlight) use (&$lines) {
            /** @var Bookmark $highlight */
            // TODO: find location or page number
            $lines->push('');
            $lines->push('> '.trim($highlight->Text));
            $lines->push('');

            $formattedDate = Carbon::parse($highlight->DateCreated)
                ->format('n/j/y \a\t g:ia');

            //if ($chapterTitle = $highlight->getChapterTitle()) {
            //$lines->push('– ' . $formattedDate . ', *' . $chapterTitle . '*');
            //} else {
            $lines->push('– '.$formattedDate);
            //}

            $lines->push('');
        });

        if ($rawLines) {
            return $lines;
        }

        return $lines->join("\n");
    }
}
