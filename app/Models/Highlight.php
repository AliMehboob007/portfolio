<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    use Orderable;

    protected $fillable = ['icon', 'title', 'subtitle', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];
}
