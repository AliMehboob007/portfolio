<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * Key/value store behind every piece of editable text on the public site.
 *
 * Values are read once per request and memoised — a page pulls ~80 keys and
 * should not pay 80 queries for them.
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /** @var array<string,string>|null */
    private static ?array $memo = null;

    /**
     * Fallbacks used when a key has never been saved. These mirror the copy the
     * site shipped with, so a fresh database still renders a complete page.
     *
     * @var array<string,string>
     */
    public const DEFAULTS = [
        // ── Identity ──────────────────────────────────────────────────────
        'name'            => 'Muhammad Ali',
        'tagline'         => 'Senior Full-Stack Developer',
        'initials'        => 'AA',
        'bio'             => '',
        'email'           => 'amarjafri1472@gmail.com',
        'phone'           => '+92 314 616 7055',
        'whatsapp'        => '923146167055',
        'github'          => 'https://github.com/Amarjafri',
        'linkedin'        => 'https://linkedin.com/in/amar-abbas-jafri',
        'location'        => 'Lahore, Punjab, Pakistan',
        // Set by the CV upload on the settings screen. Empty by default so the
        // hero download button stays hidden rather than linking at a missing file.
        'cv_file'         => '',

        // ── SEO / head ────────────────────────────────────────────────────
        'site_title'       => 'Muhammad Ali — Senior Full-Stack Developer',
        'home_title'       => 'Muhammad Ali — Senior Full-Stack Developer | Laravel · Vue.js',
        'meta_description' => 'Muhammad Ali — Senior Full-Stack Laravel Developer based in Lahore, Pakistan. Building scalable web applications for global clients.',
        'meta_keywords'    => 'Laravel Developer, Full Stack Developer, PHP Developer, Vue.js, Pakistan, Lahore, Web Development',
        'og_description'   => 'Senior Laravel & Vue.js Developer with 3+ years experience building scalable web apps.',

        // ── Navigation ────────────────────────────────────────────────────
        'nav_logo'       => 'AA',
        'nav_hire_label' => 'Hire Me',

        // ── Hero ──────────────────────────────────────────────────────────
        'hero_show'        => '1',
        'hero_badge_show'  => '1',
        'hero_badge_text'  => 'Available for new projects',
        'hero_eyebrow'     => 'Muhammad Ali — Lahore, Pakistan',
        'hero_title'       => 'Scalable web applications, built on <em>Laravel</em>.',
        'hero_desc'        => 'Senior full-stack developer with <strong>3+ years</strong> building production systems in <strong>PHP&nbsp;Laravel, Vue.js and REST APIs</strong> — eCommerce platforms, learning systems and business automation for clients worldwide.',
        'hero_btn1_label'  => 'View Projects →',
        'hero_btn2_label'  => 'Download CV ↓',
        'hero_btn3_label'  => 'Get in touch',
        'hero_stats_show'  => '1',

        // ── About ─────────────────────────────────────────────────────────
        'about_show'       => '1',
        'about_tag'        => 'About Me',
        'about_title'      => 'Building the web, one clean commit at a time.',
        'about_text_1'     => "I'm a results-driven <strong>Senior Full-Stack Developer</strong> specialising in the <strong>Laravel ecosystem</strong> — from API design to database architecture. I care about fast, secure, maintainable applications, and I ship them on time.",
        'about_text_2'     => "Over 3+ years across startups and agencies in Lahore I've delivered eCommerce platforms, SaaS products, educational systems and business automation tools for clients in Pakistan and internationally.",
        'about_btn_label'  => "Let's Talk →",
        'about_card_title' => 'tech-stack.json',

        // ── Experience ────────────────────────────────────────────────────
        'exp_show'  => '1',
        'exp_tag'   => 'Career',
        'exp_title' => 'Professional Experience',
        'exp_sub'   => 'A track record of building real products at real companies.',

        // ── Featured projects ─────────────────────────────────────────────
        'projects_show'  => '1',
        'projects_tag'   => 'Work',
        'projects_title' => 'Featured Projects',
        'projects_sub'   => 'Selected work — from eCommerce platforms to EdTech systems.',
        'projects_btn'   => 'View All Projects →',
        'projects_limit' => '6',

        // ── Skills ────────────────────────────────────────────────────────
        'skills_show'  => '1',
        'skills_tag'   => 'Expertise',
        'skills_title' => 'Technical Skills',
        'skills_sub'   => 'Full-stack capability across the entire development lifecycle.',

        // ── Testimonials ──────────────────────────────────────────────────
        'testi_show'  => '1',
        'testi_tag'   => 'Testimonials',
        'testi_title' => 'What Clients Say',
        'testi_sub'   => 'Trusted by teams across Pakistan and internationally.',

        // ── Blog teaser ───────────────────────────────────────────────────
        'blog_show'  => '1',
        'blog_tag'   => 'Blog',
        'blog_title' => 'Latest Articles',
        'blog_sub'   => 'Thoughts on Laravel, Vue.js and modern web development.',
        'blog_btn'   => 'View All Articles →',
        'blog_limit' => '3',

        // ── Contact ───────────────────────────────────────────────────────
        'contact_show'          => '1',
        'contact_tag'           => 'Contact',
        'contact_title'         => "Let's build something <em>great</em> together.",
        'contact_text'          => 'Available for freelance projects, full-time roles and consulting. Based in Lahore — open to remote work worldwide.',
        'contact_btn_label'     => 'Send Message →',
        'contact_success_msg'   => "Message sent — I'll reply within 24 hours.",
        'contact_project_types' => "Web Application\neCommerce Store\nREST API Development\nWordPress / Shopify\nFull-Time Role\nOther",

        // ── Projects listing page ─────────────────────────────────────────
        'projects_page_meta'  => 'Projects — Muhammad Ali | Laravel & Vue.js Developer',
        'projects_page_tag'   => 'Portfolio',
        'projects_page_title' => 'All Projects',
        'projects_page_desc'  => 'A complete collection of web applications, eCommerce platforms, SaaS tools, and digital solutions built for clients in Pakistan and globally.',

        // ── Blog listing page ─────────────────────────────────────────────
        'blog_page_meta'  => 'Blog — Muhammad Ali | Laravel & Web Dev Articles',
        'blog_page_tag'   => 'Blog',
        'blog_page_title' => 'Articles & Insights',
        'blog_page_desc'  => 'Thoughts on Laravel, Vue.js, web architecture, and modern development practices.',

        // ── Blog post author box ──────────────────────────────────────────
        'author_bio' => 'Senior Full-Stack Developer · Laravel & Vue.js · Lahore, Pakistan',

        // ── Footer ────────────────────────────────────────────────────────
        'footer_brand_desc'     => "Senior Full-Stack Developer\nLaravel · Vue.js · REST APIs",
        'footer_nav_title'      => 'Navigation',
        'footer_connect_title'  => 'Connect',
        'footer_location_title' => 'Location',
        'footer_availability'   => 'Available globally · Remote-friendly',
        'footer_copyright'      => 'Muhammad Ali. Built with Laravel in Lahore, Pakistan.',
    ];

    public static function get(string $key, $default = null)
    {
        $value = static::getAllSettings()[$key] ?? null;

        if ($value === null || $value === '') {
            return $default ?? static::DEFAULTS[$key] ?? $default;
        }

        return $value;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        static::$memo = null;
    }

    /**
     * All stored values, keyed. Falls back to an empty set if the table is not
     * migrated yet so the site does not hard-fail on first boot.
     *
     * @return array<string,string>
     */
    public static function getAllSettings(): array
    {
        if (static::$memo !== null) {
            return static::$memo;
        }

        try {
            return static::$memo = static::query()->pluck('value', 'key')->toArray();
        } catch (Throwable $e) {
            return static::$memo = [];
        }
    }

    /** Stored values merged over the shipped defaults — used by the admin form. */
    public static function withDefaults(): array
    {
        return array_merge(static::DEFAULTS, array_filter(
            static::getAllSettings(),
            fn($v) => $v !== null && $v !== ''
        ));
    }

    public static function flushCache(): void
    {
        static::$memo = null;
    }
}
