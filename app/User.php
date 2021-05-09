<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'appointment',
        'uuid', 'role_uuid', 'division_id', 'is_responsible',
        'last_name', 'first_name', 'middle_name',
    ];

    protected $casts = [
        'is_responsible' => 'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
//    protected $casts = [
//        'email_verified_at' => 'datetime',
//    ];

    /**
     * Роль
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_uuid', 'uuid');
    }

    /**
     * Права
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->role->permissions();
    }

    /**
     * Подраделение
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * ФИО
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return trim(ucfirst($this->last_name) . ' ' . ucfirst($this->first_name) . ' ' . ucfirst($this->middle_name));
    }

    /**
     * Сокращённое имя
     * - Имя Ф.
     *
     * @return string
     */
    public function getShortNameAttribute()
    {
        return trim(ucfirst($this->first_name) . ' ' . mb_substr(ucfirst($this->last_name), 0 , 1));
    }

    /**
     * Фамилия с заглавной буквы
     *
     * @return string
     */
    public function getLastNameAttribute()
    {
        return ucfirst($this->attributes['last_name']);
    }

    /**
     * Имя с заглавной буквы
     *
     * @return string
     */
    public function getFirstNameAttribute()
    {
        return ucfirst($this->attributes['first_name']);
    }

    /**
     * Отчество с заглавной буквы
     *
     * @return string
     */
    public function getMiddleNameAttribute()
    {
        return ucfirst($this->attributes['middle_name']);
    }

    /**
     * Сеттер фамилии
     *
     * @param $value
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = strtolower($value);
    }

    /**
     * Сеттер имени
     *
     * @param $value
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = strtolower($value);
    }

    /**
     * Сеттер отчества
     *
     * @param $value
     */
    public function setMiddleNameAttribute($value)
    {
        $this->attributes['middle_name'] = strtolower($value);
    }

    /**
     * Сеттер пароля
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Наличие права
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission) : bool
    {
        if ( $this->permissions->contains('name', '=', 'full') ) return true;

        return $this->permissions->contains('name', '=', $permission);
    }

    /**
     * Поиск по пользователю:
     * - полное вхождение по ФИО
     * - email
     * - должности
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $keyword
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $keyword)
    {
        $escapedKeyword = preg_replace('/[^\p{L}\p{N}_]+/u', ' ', $keyword);
        $escapedKeyword = preg_replace('/[+\-><\(\)~*\"@]+/', ' ', $escapedKeyword);

        return $query->where('email', 'LIKE', $keyword . '%')
            ->orWhere('appointment', 'LIKE', $keyword . '%')
            ->orWhereRaw("MATCH(last_name,first_name,middle_name) AGAINST('+{$escapedKeyword}' IN BOOLEAN MODE)");
    }
}
