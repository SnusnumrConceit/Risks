<?php

namespace App;

use App\Traits\ArrayConstable;
use Illuminate\Database\Eloquent\Model;

class Risk extends Model
{
    use ArrayConstable;

    protected $guarded = ['id'];

    protected $dates = [
        'created_at', 'updated_at', 'expired_at'
    ];

    const STATUS_CREATED    = 'created';
    const STATUS_PROCESSING = 'processing';
    const STATUS_FINISHED   = 'finished';
    const STATUS_EXPIRED    = 'expired';

    const LEVEL_CRITICAL      = 'critical';
    const LEVEL_HIGH          = 'high';
    const LEVEL_MODERATE      = 'moderate';
    const LEVEL_MEDIUM        = 'medium';
    const LEVEL_LOW           = 'low';
    const LEVEL_INSIGNIFICANT = 'insignificant';

    /**
     * Список статусов
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getStatuses()
    {
        return static::getConstsArray('STATUS_');
    }

    /**
     * Список уровней
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function getLevels()
    {
        return static::getConstsArray('LEVEL_');
    }

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
     * Факторы рисков
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function factors()
    {
        return $this->belongsToMany(Factor::class);
    }

    /**
     * Виды рисков
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function types()
    {
        return $this->belongsToMany(Type::class);
    }

    /**
     * Подразделения рисков
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function divisions()
    {
        return $this->belongsToMany(Division::class);
    }

    /**
     * Поиск по рискам
     *
     * @param $risksQuery
     * @param $request
     * @return mixed
     */
    public static function scopeSearch($risksQuery, $request)
    {
        /** поиск по названию и описанию */
        $risksQuery->when($request->keyword, function ($query, $keyword) {
            $escapedKeyword = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $keyword);
            $escapedKeyword = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $escapedKeyword);

            return $query->whereRaw("MATCH(name,description) AGAINST('+{$escapedKeyword}' IN BOOLEAN MODE)");
        });

        /** поиск по уровню */
        $risksQuery->when($request->level, function ($query, $level) {
            return $query->where('level', $level);
        });

        /** поиск по статусу */
        $risksQuery->when($request->status, function ($query, $status) {
            return $query->where('status', $status);
        });

        /** поиск по датам */
        $risksQuery->when($request->created_at, function ($query, $created_at) {
            return $query->where(function($query) use ($created_at) {
                return $query->where('created_at', '>=', $created_at . ' 00:00:00')
                    ->where('expired_at', '<=', request('expired_at') . ' 23:59:59');
            });
        });

        /** поиск по вероятности */
        $risksQuery->when($request->likelihood, function ($query, $likelihood) {
            return $query->where('likelihood', $likelihood);
        });

        /** поиск по влиянию */
        $risksQuery->when($request->impact, function ($query, $impact) {
            return $query->where('impact', $impact);
        });

        /** поиск по видам рисков */
        $risksQuery->when($request->types, function ($query, $types) {
            return $query->whereHas('types', function ($query) use ($types) {
                return $query->whereIn('types.id', $types);
            });
        });

        /** поиск по факторам рисков */
        $risksQuery->when($request->factors, function ($query, $factors) {
            return $query->whereHas('factors', function ($query) use ($factors) {
                return $query->whereIn('factors.id', $factors);
            });
        });

        return $risksQuery;
    }
}
