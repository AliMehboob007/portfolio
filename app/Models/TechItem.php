<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class TechItem extends Model
{
    use Orderable;

    protected $fillable = ['icon', 'label', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];
}
