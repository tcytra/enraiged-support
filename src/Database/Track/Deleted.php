<?php

namespace Enraiged\Database\Track;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait Deleted
{
    use AtTimestamp, ByUser;

    /**
     *  @return void
     */
    public static function bootDeleted()
    {
        self::deleting(fn ($model) => $model->setDeletedBy());
    }

    /**
     *  @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deletedBy(): BelongsTo
    {
        return $this
            ->belongsTo(config('auth.providers.users.model'), 'deleted_by')
            ->withTrashed();
    }

    /**
     *  @return array
     */
    public function getDeletedAttribute()
    {
        return (object) [
            'at' => $this->atTimestamp($this->deleted_at),
            'by' => $this->byUser($this->deletedBy),
        ];
    }

    /**
     *  @return void
     */
    private function setDeletedBy()
    {
        if (Auth::check()) {
            $this->deleted_by = Auth::id();
        } else {
            $this->deleted_by = $this->id;
        }
    }
}
