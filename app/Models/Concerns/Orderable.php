<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

/**
 * Shared behaviour for the small "site content" models that are sorted by hand
 * in the admin panel and can be hidden without being deleted.
 */
trait Orderable
{
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /** Visible rows in display order — what the public views ask for. */
    public static function live(): Collection
    {
        try {
            return static::query()->active()->ordered()->get();
        } catch (Throwable $e) {
            // A missing table must never take the public site down.
            return new Collection();
        }
    }

    /** Everything, in display order — what the admin list asks for. */
    public static function sorted(): Collection
    {
        try {
            return static::query()->ordered()->get();
        } catch (Throwable $e) {
            return new Collection();
        }
    }

    /** Split a textarea / comma field into a clean list. */
    public function listOf(string $field, string $separator = "\n"): array
    {
        $raw = (string) ($this->{$field} ?? '');
        if (trim($raw) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode($separator, $raw)), fn ($v) => $v !== ''));
    }
}
