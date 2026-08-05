<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use Orderable;

    protected $fillable = [
        'title', 'company', 'period', 'duration', 'location',
        'is_current', 'bullets', 'tags', 'sort_order', 'active',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'active'     => 'boolean',
    ];

    /** One responsibility per line in the admin textarea. */
    public function bulletList(): array
    {
        return $this->listOf('bullets');
    }

    public function tagList(): array
    {
        return $this->listOf('tags', ',');
    }
}
