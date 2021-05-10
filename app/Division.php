<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Division extends MaterializedPath
{
    protected $guarded = ['id'];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    /**
     * Поле, по которому происходит сопоставление модели в url
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    /**
     * Выборка потомков по id и уровню родителя
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param int $id
     * @param int $level
     * @return Illuminate\Database\Query\Builder
     */
    public static function scopeDescendants($query, int $id, int $level = 0)
    {
        return Division::where('path', 'LIKE', "%{$id}/%")
            ->where('level', '>=', $level);
    }

    /**
     * Получить id потомков по id и уровню родителя
     *
     * @param int $id
     * @param int $level
     * @return array
     */
    public static function getDescendantsIds(int $id, int $level = 0)
    {
        return static::descendants($id, $level)->pluck('id')->all();
    }
}
