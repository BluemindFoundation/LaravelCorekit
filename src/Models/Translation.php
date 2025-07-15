<?php

namespace Corekit\Models;


use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = ['table_name', 'column_name', 'row_id', 'locale', 'value',];
}