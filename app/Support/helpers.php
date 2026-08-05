<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Read an editable site value. Falls back to the key's shipped default when
     * it has never been saved, so views never render an empty string.
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('setting_on')) {
    /** Section visibility toggles — unset means visible. */
    function setting_on(string $key, bool $default = true): bool
    {
        $value = Setting::get($key, $default ? '1' : '0');

        return in_array((string) $value, ['1', 'on', 'true', 'yes'], true);
    }
}

if (! function_exists('setting_lines')) {
    /**
     * Split a multi-line setting into a trimmed list. Used for anything the
     * admin edits as "one per line" (project types, footer blurb).
     */
    function setting_lines(string $key, string $separator = "\n"): array
    {
        $raw = (string) Setting::get($key, '');

        if (trim($raw) === '') {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', explode($separator, str_replace("\r\n", "\n", $raw))),
            fn ($v) => $v !== ''
        ));
    }
}
