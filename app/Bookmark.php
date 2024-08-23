<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

/**
 * @property-read string $BookmarkID
 * @property-read string $VolumeID
 * @property-read string $ContentID
 * @property-read string $StartContainerPath
 * @property-read int $StartContainerChildIndex
 * @property-read int $StartOffset
 * @property-read string $EndContainerPath
 * @property-read int $EndContainerChildIndex
 * @property-read int $EndOffset
 * @property-read string $Text
 * @property-read string $Annotation
 * @property-read string $ExtraAnnotationData
 * @property-read string $DateCreated
 * @property-read $ChapterProgress
 * @property-read bool $Hidden
 * @property-read string $Version
 * @property-read string $DateModified
 * @property-read string $Creator
 * @property-read string $UUID
 * @property-read string $UserID
 * @property-read string $SyncTime
 * @property-read $Published
 * @property-read string $ContextString
 * @property-read string $Type
 */
class Bookmark extends Model
{
    use ReadOnlyTrait;

    protected $table = 'Bookmark';

    public function content(): BelongsToMany
    {
        return $this->belongsToMany(
            Content::class,
            'VolumeID',
            'BookID'
        );
    }

    public function getChapterTitle(): ?string
    {
        if (! $chapter = Content::where('ContentID', $this->ContentID)->first()) {
            return null;
        }

        return $chapter->Title;
    }
}
