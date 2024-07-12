<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\Uuids;

class Status extends Model
{
    use HasFactory, SoftDeletes, Uuids;


    protected $casts = [
        'id' => 'string',
    ];
    protected $primaryKey = "id";
    protected $table = "statuses";

    protected $guarded = [

    ];

    public function approvals(){
        return $this->hasMany('App\Models\Approval','status_id','id');
    }
}
