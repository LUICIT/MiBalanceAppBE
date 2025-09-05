<?php

namespace App\Models;

use App\Traits\Versionable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{

    use SoftDeletes, Versionable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
        'version',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
