<?php

namespace App\Observers;

use App\Models\Document;
use App\Models\Disk;

class DocumentObserver
{
    /**
     * Handle the Document "created" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function created(Document $document)
    {
        $document->disk()->increment('total_files');
        $document->disk()->increment('total_size', $document->file_size);

        $documentSlug = $document->slug;
        $documentID = $document->id;

        $altSlug1 = $documentSlug . '.html';
        $altSlug2 = $documentSlug . '-' . $documentID;
        $altSlug3 = $documentSlug . '-' . $documentID . '.html';
        $altSlug4 = $documentID . '-' . $documentSlug;
        $altSlug5 = $documentID . '-' . $documentSlug . '.html';

        $document->update([
            'alt_slug_1' => $altSlug1,
            'alt_slug_2' => $altSlug2,
            'alt_slug_3' => $altSlug3,
            'alt_slug_4' => $altSlug4,
            'alt_slug_5' => $altSlug5,
        ]);
    }

    /**
     * Handle the Document "updated" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function updated(Document $document)
    {
        //
    }

    /**
     * Handle the Document "deleted" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function deleted(Document $document)
    {
        $document->disk()->decrement('total_files');
        $document->disk()->decrement('total_size', $document->file_size);
    }

    /**
     * Handle the Document "restored" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function restored(Document $document)
    {
        //
    }

    /**
     * Handle the Document "force deleted" event.
     *
     * @param  \App\Models\Document  $document
     * @return void
     */
    public function forceDeleted(Document $document)
    {
        //
    }
}
