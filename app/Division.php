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
}
