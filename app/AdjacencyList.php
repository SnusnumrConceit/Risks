<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

abstract class AdjacencyList extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Model $model) {
            $model->children()->update(['parent_id' => $model->parent_id]);
        });
    }

    /**
     * Родитель
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne(static::class, 'id', 'parent_id');
    }

    /**
     * Дети
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }
}
