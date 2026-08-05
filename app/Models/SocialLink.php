<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use Orderable;

    protected $fillable = [
        'icon', 'label', 'value', 'url', 'sort_order',
        'in_contact', 'in_footer', 'is_social_btn', 'active',
    ];

    protected $casts = [
        'in_contact'    => 'boolean',
        'in_footer'     => 'boolean',
        'is_social_btn' => 'boolean',
        'active'        => 'boolean',
    ];

    /** External destinations open in a new tab; mailto/tel should not. */
    public function isExternal(): bool
    {
        return (bool) preg_match('#^https?://#i', trim((string) $this->url));
    }
}
