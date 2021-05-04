<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factor extends AdjacencyList
{
    protected $guarded = ['id'];
    public $timestamps = false;
}
