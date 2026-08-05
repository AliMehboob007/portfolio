<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class HeroStat extends Model
{
    use Orderable;

    protected $fillable = ['number', 'label', 'short_label', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];
}
