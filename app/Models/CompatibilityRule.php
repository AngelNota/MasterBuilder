<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompatibilityRule extends Model
{
    protected $fillable = [
        'component_type_from',
        'spec_from',
        'component_type_to',
        'spec_to',
        'compatible',
        'message',
    ];
}
