<?php

namespace App\Modules\File\Http\Traits;

use App\Modules\File\Models\File;

trait HasFile
{
    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    public static function bootHasFile()
    {
        static::saved(function ($entity) {
            $entity->syncFiles(request('files', []));
        });
    }

    /**
     * Get all of the files for the entity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function files()
    {
        return $this->morphToMany(File::class, 'entity', 'entity_files')
            ->withPivot(['id', 'label'])
            ->withTimestamps();
    }

    /**
     * Filter files by label.
     *
     * @param string $label
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function filterFiles($label)
    {
        return $this->files()->wherePivot('label', $label);
    }

    /**
     * Sync files for the entity.
     *
     * @param array $files
     */
    public function syncFiles($files = [])
    {
        $entityType = get_class($this);

        foreach ($files as $label => $fileIds) {
            $syncList = [];

            foreach (array_wrap($fileIds) as $fileId) {
                if (! empty($fileId)) {
                    $syncList[$fileId]['label'] = $label;
                    $syncList[$fileId]['entity_type'] = $entityType;
                }
            }

            $this->filterFiles($label)->detach();
            $this->filterFiles($label)->attach($syncList);
        }
    }
}
