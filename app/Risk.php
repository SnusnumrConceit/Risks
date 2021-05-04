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
}
