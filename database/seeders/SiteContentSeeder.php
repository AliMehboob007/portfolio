<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\HeroStat;
use App\Models\Highlight;
use App\Models\NavLink;
use App\Models\Setting;
use App\Models\SkillGroup;
use App\Models\SocialLink;
use App\Models\TechItem;
use Illuminate\Database\Seeder;

/**
 * Loads the copy the site previously had hard-coded into the database, so the
 * public pages look identical after the move to admin-editable content.
 *
 * Safe to re-run: settings are only written when missing, and the content
 * tables are only filled when empty, so your edits are never overwritten.
 */
class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedHeroStats();
        $this->seedHighlights();
        $this->seedTechItems();
        $this->seedExperiences();
        $this->seedSkillGroups();
        $this->seedNavLinks();
        $this->seedSocialLinks();

        Setting::flushCache();
    }

    /** Write every shipped default that has no stored value yet. */
    private function seedSettings(): void
    {
        $existing = Setting::query()->pluck('value', 'key')->toArray();
        $added    = 0;

        foreach (Setting::DEFAULTS as $key => $value) {
            if (! array_key_exists($key, $existing) || $existing[$key] === null || $existing[$key] === '') {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                $added++;
            }
        }

        $this->command?->info("Settings: {$added} key(s) written, " . (count(Setting::DEFAULTS) - $added) . ' left untouched.');
    }

    private function seedHeroStats(): void
    {
        if (HeroStat::count()) {
            return;
        }

        foreach ([
            ['number' => '3+',  'label' => 'Years Experience',   'short_label' => 'Years'],
            ['number' => '20+', 'label' => 'Projects Delivered', 'short_label' => 'Projects'],
            ['number' => '5',   'label' => 'Companies',          'short_label' => 'Companies'],
        ] as $i => $row) {
            HeroStat::create($row + ['sort_order' => $i, 'active' => true]);
        }
    }

    private function seedHighlights(): void
    {
        if (Highlight::count()) {
            return;
        }

        foreach ([
            ['icon' => 'fas fa-graduation-cap', 'title' => 'BSCS Graduate',     'subtitle' => 'NCBA&E, Multan — 2023'],
            ['icon' => 'fas fa-location-dot',   'title' => 'Based in Lahore',   'subtitle' => 'Punjab, Pakistan'],
            ['icon' => 'fas fa-earth-asia',     'title' => 'Available Globally','subtitle' => 'Remote · On-site · Hybrid'],
            ['icon' => 'fas fa-comments',       'title' => 'Languages',         'subtitle' => 'English (Fluent) · Urdu (Native)'],
        ] as $i => $row) {
            Highlight::create($row + ['sort_order' => $i, 'active' => true]);
        }
    }

    private function seedTechItems(): void
    {
        if (TechItem::count()) {
            return;
        }

        foreach ([
            ['fab fa-php',       'PHP'],
            ['fab fa-laravel',   'Laravel'],
            ['fab fa-vuejs',     'Vue.js'],
            ['fab fa-js',        'JavaScript'],
            ['fas fa-database',  'MySQL'],
            ['fas fa-link',      'REST APIs'],
            ['fab fa-aws',       'AWS Cloud'],
            ['fab fa-git-alt',   'Git / GitLab'],
            ['fas fa-fire',      'Firebase'],
            ['fab fa-wordpress', 'WordPress'],
            ['fab fa-shopify',   'Shopify'],
            ['fab fa-docker',    'Docker'],
            ['fas fa-gears',     'CI/CD'],
            ['fab fa-bootstrap', 'Bootstrap'],
            ['fab fa-figma',     'Figma'],
        ] as $i => [$icon, $label]) {
            TechItem::create([
                'icon'       => $icon,
                'label'      => $label,
                'sort_order' => $i,
                'active'     => true,
            ]);
        }
    }

    private function seedExperiences(): void
    {
        if (Experience::count()) {
            return;
        }

        $rows = [
            [
                'title'      => 'Senior Full-Stack Developer',
                'company'    => 'Eden Prime',
                'period'     => 'Sep 2025 — Present',
                'duration'   => '',
                'location'   => 'Lahore, Pakistan · On-site',
                'is_current' => true,
                'bullets'    => [
                    'Building scalable full-stack web applications for eCommerce & business clients using Laravel and Vue.js',
                    'Designing and implementing RESTful APIs for secure frontend/backend integration',
                    'Optimising application performance, database architecture and cloud deployment pipelines',
                    'Collaborating with product teams to ship high-quality digital products on schedule',
                ],
                'tags' => 'Laravel, Vue.js, REST API, MySQL, AWS, eCommerce',
            ],
            [
                'title'      => 'Senior Laravel Full-Stack Developer',
                'company'    => 'Digitt Solutions',
                'period'     => 'Jan 2025 — May 2026',
                'duration'   => '1 yr 5 mos',
                'location'   => 'Lahore, Pakistan · On-site',
                'is_current' => false,
                'bullets'    => [
                    'Led development of scalable web applications with Laravel, Vue.js and MySQL',
                    'Designed and optimised backend logic, APIs and database structures for high performance',
                    'Contributed to WordPress and Shopify projects — bug fixing, new features, performance tuning',
                    'Ensured clean code standards, best practices and efficient cloud deployments',
                ],
                'tags' => 'Laravel, Vue.js, Shopify, WordPress, CI/CD, MySQL',
            ],
            [
                'title'      => 'Laravel Full-Stack Developer',
                'company'    => 'Infobay (SMC PVT.) Ltd',
                'period'     => 'Oct 2023 — Dec 2024',
                'duration'   => '1 yr 3 mos',
                'location'   => 'Lahore, Pakistan · On-site',
                'is_current' => false,
                'bullets'    => [
                    'Led development of responsive web apps and RESTful APIs with Laravel and Vue.js',
                    'Integrated third-party APIs and maintained backend logic at scale',
                    'Managed CI/CD deployments via Git and GitLab with security best practices',
                ],
                'tags' => 'Laravel, Vue.js, GitLab, CI/CD, REST API',
            ],
            [
                'title'      => 'Laravel Developer',
                'company'    => 'Avicenna Enterprise Solutions',
                'period'     => '2023',
                'duration'   => '',
                'location'   => 'Lahore, Pakistan',
                'is_current' => false,
                'bullets'    => [
                    'Developed server-side logic and database structures with MySQL and Firebase',
                    'Built RESTful APIs; managed website hosting via cPanel and various server environments',
                ],
                'tags' => 'Laravel, Firebase, MySQL, cPanel',
            ],
            [
                'title'      => 'Junior Developer',
                'company'    => 'CyberifyDesign.co',
                'period'     => '2020 — 2022',
                'duration'   => '',
                'location'   => 'Multan, Pakistan',
                'is_current' => false,
                'bullets'    => [
                    'Assisted with bug fixing, feature rollouts and Agile sprints',
                    'Gained hands-on experience with PHP, Laravel and Git version control',
                ],
                'tags' => 'PHP, Laravel, Git, Agile',
            ],
        ];

        foreach ($rows as $i => $row) {
            $row['bullets']    = implode("\n", $row['bullets']);
            $row['sort_order'] = $i;
            $row['active']     = true;
            Experience::create($row);
        }
    }

    private function seedSkillGroups(): void
    {
        if (SkillGroup::count()) {
            return;
        }

        foreach ([
            ['fas fa-code',          'Languages & Frameworks', 'PHP, Laravel, Vue.js, JavaScript, jQuery, HTML5, CSS3, Bootstrap'],
            ['fas fa-database',      'Databases',              'MySQL, Firebase Realtime DB, Database Design, Query Optimization, Migrations'],
            ['fas fa-cloud',         'Cloud & Hosting',        'AWS, Google Cloud, VPS, cPanel, Cloudflare, SSL/TLS, FTP'],
            ['fas fa-gears',         'Tools & DevOps',         'Git, GitLab, GitHub, Postman, Figma, Docker, CI/CD'],
            ['fas fa-shopping-cart', 'CMS & eCommerce',        'WordPress, Shopify, WooCommerce, Theme Development, Plugin Development'],
            ['fas fa-sitemap',       'Architecture',           'RESTful APIs, MVC Pattern, Agile/Scrum, Web Security, Code Optimization, TDD'],
        ] as $i => [$icon, $title, $skills]) {
            SkillGroup::create([
                'icon'       => $icon,
                'title'      => $title,
                'skills'     => $skills,
                'sort_order' => $i,
                'active'     => true,
            ]);
        }
    }

    private function seedNavLinks(): void
    {
        if (NavLink::count()) {
            return;
        }

        foreach ([
            ['Home',       '/',           true,  true],
            ['About',      '#about',      true,  false],
            ['Experience', '#experience', true,  false],
            ['Projects',   '/projects',   true,  true],
            ['Skills',     '#skills',     true,  false],
            ['Blog',       '/blog',       true,  true],
            ['Contact',    '#contact',    true,  true],
        ] as $i => [$label, $url, $header, $footer]) {
            NavLink::create([
                'label'      => $label,
                'url'        => $url,
                'in_header'  => $header,
                'in_footer'  => $footer,
                'sort_order' => $i,
                'active'     => true,
            ]);
        }
    }

    private function seedSocialLinks(): void
    {
        if (SocialLink::count()) {
            return;
        }

        foreach ([
            [
                'icon' => 'fas fa-envelope', 'label' => 'Email',
                'value' => 'amarjafri1472@gmail.com', 'url' => 'mailto:amarjafri1472@gmail.com',
                'in_contact' => true, 'in_footer' => true, 'is_social_btn' => true,
            ],
            [
                'icon' => 'fab fa-whatsapp', 'label' => 'Phone / WhatsApp',
                'value' => '+92 314 616 7055', 'url' => 'https://wa.me/923146167055',
                'in_contact' => true, 'in_footer' => true, 'is_social_btn' => false,
            ],
            [
                'icon' => 'fab fa-linkedin-in', 'label' => 'LinkedIn',
                'value' => 'linkedin.com/in/amar-abbas-jafri', 'url' => 'https://linkedin.com/in/amar-abbas-jafri',
                'in_contact' => true, 'in_footer' => true, 'is_social_btn' => true,
            ],
            [
                'icon' => 'fab fa-github', 'label' => 'GitHub',
                'value' => 'github.com/Amarjafri', 'url' => 'https://github.com/Amarjafri',
                'in_contact' => true, 'in_footer' => true, 'is_social_btn' => true,
            ],
        ] as $i => $row) {
            SocialLink::create($row + ['sort_order' => $i, 'active' => true]);
        }
    }
}
