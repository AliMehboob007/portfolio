<?php

namespace App\Models;

use App\Models\Concerns\Orderable;
use Illuminate\Database\Eloquent\Model;

class NavLink extends Model
{
    use Orderable;

    protected $fillable = ['label', 'url', 'sort_order', 'in_header', 'in_footer', 'active'];

    protected $casts = [
        'in_header' => 'boolean',
        'in_footer' => 'boolean',
        'active'    => 'boolean',
    ];

    /**
     * Menu entries are stored as "/", "#about" or "/blog" so the admin never has
     * to know about route names. Hash-only links must hang off the home page or
     * they break on /projects and /blog.
     */
    public function href(): string
    {
        $url = trim((string) $this->url);

        if ($url === '') {
            return url('/');
        }

        if (str_starts_with($url, '#')) {
            return url('/') . $url;
        }

        if (preg_match('#^(https?://|mailto:|tel:)#i', $url)) {
            return $url;
        }

        return url($url);
    }

    /** True when this link points at the page currently being viewed. */
    public function isActive(): bool
    {
        $url = trim((string) $this->url);

        if ($url === '' || str_starts_with($url, '#')) {
            return false;
        }

        return rtrim(parse_url($this->href(), PHP_URL_PATH) ?? '', '/') === rtrim(request()->getPathInfo(), '/');
    }
}
