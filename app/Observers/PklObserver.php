<?php

namespace App\Observers;

use App\Models\Pkl;

class PklObserver
{
    /**
     * Handle the Pkl "created" event.
     */
    public function created(Pkl $pkl): void
    {
        // Update siswa status_pkl to true when PKL record is created
        $pkl->siswa()->update(['status_pkl' => true]);
    }

    /**
     * Handle the Pkl "deleted" event.
     */
    public function deleted(Pkl $pkl): void
    {
        // Check if the student has any other PKL records
        $hasOtherPkl = Pkl::where('siswa_id', $pkl->siswa_id)
            ->where('id', '!=', $pkl->id)
            ->exists();

        // Only set status_pkl to false if there are no other PKL records
        if (!$hasOtherPkl) {
            $pkl->siswa()->update(['status_pkl' => false]);
        }
    }
}
