<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 * @property-read string $ContentID
 * @property-read string $ContentType
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
 * @property-read $FavouritesIndex
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
 * @property-read $ReadStateSynced
 * @property-read int $TimesStartedReading
 * @property-read int $TimesSpentReading
 * @property-read string $LastTimeStartedReading
 * @property-read string $LastTimeFinishedReading
 * @property-read string $ApplicableSubscriptions
 * @property-read string $ExternalIds
 * @property-read string $PurchaseRevisionId
 * @property-read string $SeriesID
 * @property-read $SeriesNumberFloat
 * @property-read string $AdobeLoanExpiration
 * @property-read $HideFromHomePage
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
}
