<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

abstract class MaterializedPath extends Model
{
    const ID_FIELD     = 'id';
    const PATH_FIELD   = 'path';
    const LEVEL_FIELD  = 'level';
    const PARENT_FIELD = 'parent_id';

    protected static function boot()
    {
        parent::boot();

        static::created(function(Model $model) {
            static::createdHandler($model);
        });

        static::updating(function(Model $model) {
            static::updatingHandler($model);
        });

        static::deleting(function(Model $model) {
            static::deletingHandler($model);
        });
    }

    /**
     * Хук после создания модели:
     * - обновление пути
     * - обновление уровня
     *
     * @param Model $model
     */
    public static function createdHandler(Model $model) : void
    {
        static::where(static::ID_FIELD, $model[static::ID_FIELD])->update([
            static::PATH_FIELD  => static::getPreparedModelPath($model),
            static::LEVEL_FIELD => static::getPreparedLevel($model)
        ]);
    }

    /**
     * Хук при обновлении модели:
     * - адрес/путь модели
     * - уровень
     * - адрес/путь потомков
     *
     * @param Model $model
     */
    public static function updatingHandler(Model $model) : void
    {
        $model[static::PATH_FIELD]  = static::getPreparedModelPath($model);
        $model[static::LEVEL_FIELD] = static::getPreparedLevel($model);

        if ($model->children()->exists()) {
            static::where(static::PATH_FIELD, 'LIKE', ( $originalField = $model->getOriginal(static::PATH_FIELD) ) . '%')->update([
                static::PATH_FIELD  => DB::raw(static::getPathReplaceStatement($originalField, $model[static::PATH_FIELD])),
                static::LEVEL_FIELD => DB::raw(static::LEVEL_FIELD . " + " . ( $model[static::LEVEL_FIELD] - $model->getOriginal(static::LEVEL_FIELD) ))
            ]);
        }
    }

    /**
     * Хук при удалении модели:
     * - родителя у прямых потомков
     * - обновление уровня потомков
     * - обновление адреса/пути потомков
     *
     * @param $model
     */
    public static function deletingHandler($model) : void
    {
        if ($model->children()->exists()) {
            $model->children()->update([
                static::PARENT_FIELD => $model[static::PARENT_FIELD]
            ]);

            static::where(static::PATH_FIELD, 'LIKE', $model[static::PATH_FIELD] . '%')->update([
                static::PATH_FIELD  => DB::raw(static::getPathReplaceStatement($model[static::PATH_FIELD], static::getParentModelPath($model))),
                static::LEVEL_FIELD => DB::raw(static::LEVEL_FIELD . " - " . 1)
            ]);
        }
    }

    /**
     * Получить итоговый адрес/путь модели
     *
     * @param Model $model
     * @return string
     */
    protected static function getPreparedModelPath(Model $model) : string
    {
        return static::getParentModelPath($model) . $model[static::ID_FIELD] . '/';
    }

    /**
     * Получить адрес/путь родителя
     *
     * @param Model $model
     * @return string
     */
    protected static function getParentModelPath(Model $model) : string
    {
        return $model->parent ? $model->parent[static::PATH_FIELD] : '';
    }

    /**
     * Получить итоговый уровень модели
     *
     * @param Model $model
     * @return int
     */
    protected static function getPreparedLevel(Model $model) : int
    {
        return $model->parent ? ( $model->parent[static::LEVEL_FIELD] + 1 ) : 0;
    }

    /**
     * Подготовленное SQL-выражение для динамической замены пути
     *
     * @param string $search
     * @param string $replace
     * @return string
     */
    protected static function getPathReplaceStatement(string $search, string $replace) : string
    {
        return "REPLACE (" . static::PATH_FIELD . ", '{$search}', '{$replace}')";
    }

    /**
     * Родитель
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parent()
    {
        return $this->hasOne(static::class, static::ID_FIELD, static::PARENT_FIELD);
    }

    /**
     * Дети
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, static::PARENT_FIELD,static::ID_FIELD);
    }
}
