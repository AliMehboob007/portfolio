<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class SkillGroup extends Model
{
    use Orderable;

    protected $fillable = ['icon', 'title', 'skills', 'sort_order', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function skillList(): array
    {
        return $this->listOf('skills', ',');
    }
}
