<?php

namespace App\Models;

class Period extends BaseModel
{

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'code',
        'type_period',
        'payment_date',
        'notes',
        'version',
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

}
