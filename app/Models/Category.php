<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Category extends Model
{
    use SoftDeletes, Traits\Uuid;
    protected $fillable = ['name', 'description','is_active'];
    protected $dates = ['deleted_at'];
    protected $casts = [
        'id' => 'string',
        'is_active' => 'boolean'
    ];
    public $incrementing = false;
/*
    public static function boot()
    {
        parent::boot();
        static::creating(function($obj){
            $obj->id = Uuid::uuid4();
        });
    }
*/
}
