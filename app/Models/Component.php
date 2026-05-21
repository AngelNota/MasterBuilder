<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Component extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'nombre',
        'marca',
        'modelo',
        'precio',
        'stock',
        'especificaciones',
        'imagen',
    ];

    protected $casts = [
        'especificaciones' => 'array',
        'precio' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function quotes(): BelongsToMany
    {
        return $this->belongsToMany(Quote::class)
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }

   
}
