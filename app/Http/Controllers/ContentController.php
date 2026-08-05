<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\HeroStat;
use App\Models\Highlight;
use App\Models\NavLink;
use App\Models\SkillGroup;
use App\Models\SocialLink;
use App\Models\TechItem;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Schema-driven CRUD for the repeating blocks on the public site.
 *
 * Every content type below used to be a hard-coded PHP array in a Blade file.
 * They share one controller and one pair of views because the only thing that
 * differs between them is the field list declared in TYPES.
 */
class ContentController extends Controller
{
    /**
     * Field types understood by the shared form view:
     * text · textarea · number · checkbox · icon · url
     */
    private static function types(): array
    {
        return [
            'experiences' => [
                'label'    => 'Experience',
                'singular' => 'Experience',
                'icon'     => 'fas fa-briefcase',
                'intro'    => 'Your career timeline, shown in the Experience section. Drag order is set by the Sort Order number — lowest first.',
                'model'    => Experience::class,
                'columns'  => ['title' => 'Role', 'company' => 'Company', 'period' => 'Period'],
                'fields'   => [
                    'title'      => ['label' => 'Job Title', 'type' => 'text', 'rules' => 'required|string|max:150', 'placeholder' => 'Senior Full-Stack Developer', 'width' => 'half'],
                    'company'    => ['label' => 'Company', 'type' => 'text', 'rules' => 'required|string|max:150', 'placeholder' => 'Eden Prime', 'width' => 'half'],
                    'period'     => ['label' => 'Period', 'type' => 'text', 'rules' => 'required|string|max:100', 'placeholder' => 'Sep 2025 — Present', 'width' => 'half'],
                    'duration'   => ['label' => 'Duration', 'type' => 'text', 'rules' => 'nullable|string|max:60', 'placeholder' => '1 yr 5 mos', 'hint' => 'Optional. Leave empty to hide.', 'width' => 'half'],
                    'location'   => ['label' => 'Location', 'type' => 'text', 'rules' => 'nullable|string|max:150', 'placeholder' => 'Lahore, Pakistan · On-site'],
                    'is_current' => ['label' => 'This is my current job', 'type' => 'checkbox', 'hint' => 'Adds the green "Current" badge.'],
                    'bullets'    => ['label' => 'Responsibilities', 'type' => 'textarea', 'rows' => 5, 'rules' => 'nullable|string', 'hint' => 'One bullet point per line.', 'placeholder' => "Built scalable web applications with Laravel\nDesigned RESTful APIs"],
                    'tags'       => ['label' => 'Tech Tags', 'type' => 'text', 'rules' => 'nullable|string|max:300', 'hint' => 'Comma separated.', 'placeholder' => 'Laravel, Vue.js, MySQL'],
                ],
            ],

            'skills' => [
                'label'    => 'Skill Groups',
                'singular' => 'Skill Group',
                'icon'     => 'fas fa-layer-group',
                'intro'    => 'The cards in the Technical Skills section. Each card is one group of pills.',
                'model'    => SkillGroup::class,
                'columns'  => ['title' => 'Group', 'skills' => 'Skills'],
                'fields'   => [
                    'icon'   => ['label' => 'Icon Class', 'type' => 'icon', 'rules' => 'required|string|max:80', 'placeholder' => 'fas fa-code', 'width' => 'half'],
                    'title'  => ['label' => 'Group Title', 'type' => 'text', 'rules' => 'required|string|max:120', 'placeholder' => 'Languages & Frameworks', 'width' => 'half'],
                    'skills' => ['label' => 'Skills', 'type' => 'textarea', 'rows' => 3, 'rules' => 'nullable|string', 'hint' => 'Comma separated — each becomes a pill.', 'placeholder' => 'PHP, Laravel, Vue.js, JavaScript'],
                ],
            ],

            'tech' => [
                'label'    => 'Tech Stack',
                'singular' => 'Tech Item',
                'icon'     => 'fas fa-microchip',
                'intro'    => 'Icons inside the tech-stack card in the About section.',
                'model'    => TechItem::class,
                'columns'  => ['label' => 'Technology', 'icon' => 'Icon'],
                'fields'   => [
                    'icon'  => ['label' => 'Icon Class', 'type' => 'icon', 'rules' => 'required|string|max:80', 'placeholder' => 'fab fa-laravel', 'width' => 'half'],
                    'label' => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:80', 'placeholder' => 'Laravel', 'width' => 'half'],
                ],
            ],

            'highlights' => [
                'label'    => 'About Highlights',
                'singular' => 'Highlight',
                'icon'     => 'fas fa-award',
                'intro'    => 'The icon rows under your About text — education, location, availability, languages.',
                'model'    => Highlight::class,
                'columns'  => ['title' => 'Title', 'subtitle' => 'Subtitle'],
                'fields'   => [
                    'icon'     => ['label' => 'Icon Class', 'type' => 'icon', 'rules' => 'required|string|max:80', 'placeholder' => 'fas fa-graduation-cap', 'width' => 'half'],
                    'title'    => ['label' => 'Title', 'type' => 'text', 'rules' => 'required|string|max:120', 'placeholder' => 'BSCS Graduate', 'width' => 'half'],
                    'subtitle' => ['label' => 'Subtitle', 'type' => 'text', 'rules' => 'nullable|string|max:200', 'placeholder' => 'NCBA&E, Multan — 2023'],
                ],
            ],

            'stats' => [
                'label'    => 'Hero Stats',
                'singular' => 'Stat',
                'icon'     => 'fas fa-chart-simple',
                'intro'    => 'The counters under your hero text. They also appear as the summary row in the About section.',
                'model'    => HeroStat::class,
                'columns'  => ['number' => 'Number', 'label' => 'Label'],
                'fields'   => [
                    'number'      => ['label' => 'Number', 'type' => 'text', 'rules' => 'required|string|max:20', 'placeholder' => '3+', 'width' => 'half'],
                    'label'       => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:80', 'placeholder' => 'Years Experience', 'width' => 'half'],
                    'short_label' => ['label' => 'Short Label', 'type' => 'text', 'rules' => 'nullable|string|max:40', 'placeholder' => 'Years', 'hint' => 'Used in the compact About row. Falls back to the full label.'],
                ],
            ],

            'menu' => [
                'label'    => 'Menu Links',
                'singular' => 'Menu Link',
                'icon'     => 'fas fa-bars',
                'intro'    => 'Header, mobile and footer navigation. Use "/" for home, "#about" for a homepage section, or "/blog" for a page.',
                'model'    => NavLink::class,
                'columns'  => ['label' => 'Label', 'url' => 'URL'],
                'fields'   => [
                    'label'     => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:60', 'placeholder' => 'About', 'width' => 'half'],
                    'url'       => ['label' => 'URL', 'type' => 'text', 'rules' => 'required|string|max:255', 'placeholder' => '#about', 'width' => 'half', 'hint' => '"/", "#about", "/projects" or a full https:// link.'],
                    'in_header' => ['label' => 'Show in header menu', 'type' => 'checkbox', 'default' => true],
                    'in_footer' => ['label' => 'Show in footer navigation', 'type' => 'checkbox'],
                ],
            ],

            'social' => [
                'label'    => 'Contact & Social',
                'singular' => 'Link',
                'icon'     => 'fas fa-share-nodes',
                'intro'    => 'Contact cards on the homepage, the footer "Connect" list and the round social buttons.',
                'model'    => SocialLink::class,
                'columns'  => ['label' => 'Label', 'value' => 'Displayed Value'],
                'fields'   => [
                    'icon'          => ['label' => 'Icon Class', 'type' => 'icon', 'rules' => 'required|string|max:80', 'placeholder' => 'fas fa-envelope', 'width' => 'half'],
                    'label'         => ['label' => 'Label', 'type' => 'text', 'rules' => 'required|string|max:80', 'placeholder' => 'Email', 'width' => 'half'],
                    'value'         => ['label' => 'Displayed Text', 'type' => 'text', 'rules' => 'nullable|string|max:200', 'placeholder' => 'you@example.com', 'hint' => 'What visitors see on the contact card.'],
                    'url'           => ['label' => 'Link', 'type' => 'text', 'rules' => 'required|string|max:255', 'placeholder' => 'mailto:you@example.com', 'hint' => 'mailto:…, tel:…, https://wa.me/… or any URL.'],
                    'in_contact'    => ['label' => 'Show as contact card on homepage', 'type' => 'checkbox', 'default' => true],
                    'in_footer'     => ['label' => 'Show in footer "Connect" list', 'type' => 'checkbox', 'default' => true],
                    'is_social_btn' => ['label' => 'Show as round social button in footer', 'type' => 'checkbox'],
                ],
            ],
        ];
    }

    /** Every type, for the admin sidebar. */
    public static function menu(): array
    {
        $menu = [];
        foreach (static::types() as $key => $cfg) {
            $menu[] = ['key' => $key, 'label' => $cfg['label'], 'icon' => $cfg['icon']];
        }

        return $menu;
    }

    private function config(string $type): array
    {
        $types = static::types();

        if (! isset($types[$type])) {
            throw new NotFoundHttpException("Unknown content type [{$type}].");
        }

        return $types[$type] + ['key' => $type];
    }

    // ── LIST ──────────────────────────────────────────────────────────────

    public function index(string $type)
    {
        $config = $this->config($type);
        $model  = $config['model'];

        return view('admin.content.index', [
            'config' => $config,
            'type'   => $type,
            'items'  => $model::sorted(),
        ]);
    }

    // ── CREATE / EDIT ─────────────────────────────────────────────────────

    public function create(string $type)
    {
        $config = $this->config($type);

        return view('admin.content.form', [
            'config' => $config,
            'type'   => $type,
            'item'   => new $config['model'],
        ]);
    }

    public function edit(string $type, int $id)
    {
        $config = $this->config($type);

        return view('admin.content.form', [
            'config' => $config,
            'type'   => $type,
            'item'   => $config['model']::findOrFail($id),
        ]);
    }

    // ── WRITE ─────────────────────────────────────────────────────────────

    public function store(Request $request, string $type)
    {
        $config = $this->config($type);
        $config['model']::create($this->payload($request, $config));

        return redirect()
            ->route('admin.content.index', $type)
            ->with('success', $config['singular'] . ' added.');
    }

    public function update(Request $request, string $type, int $id)
    {
        $config = $this->config($type);
        $config['model']::findOrFail($id)->update($this->payload($request, $config));

        return redirect()
            ->route('admin.content.index', $type)
            ->with('success', $config['singular'] . ' updated.');
    }

    public function destroy(string $type, int $id)
    {
        $config = $this->config($type);
        $config['model']::findOrFail($id)->delete();

        return back()->with('success', $config['singular'] . ' deleted.');
    }

    /** Show/hide straight from the list — no round trip through the form. */
    public function toggle(string $type, int $id)
    {
        $config = $this->config($type);
        $item   = $config['model']::findOrFail($id);
        $item->update(['active' => ! $item->active]);

        return back()->with('success', $config['singular'] . ($item->active ? ' is now visible.' : ' is now hidden.'));
    }

    /** Re-order the whole list from the inline number inputs. */
    public function reorder(Request $request, string $type)
    {
        $config = $this->config($type);

        foreach ((array) $request->input('order', []) as $id => $position) {
            $config['model']::whereKey($id)->update(['sort_order' => (int) $position]);
        }

        return back()->with('success', 'Order saved.');
    }

    /**
     * Validate declared fields, then fold in the checkboxes and the two
     * housekeeping columns every content model shares.
     */
    private function payload(Request $request, array $config): array
    {
        $rules = [];
        foreach ($config['fields'] as $name => $field) {
            if (($field['type'] ?? 'text') !== 'checkbox') {
                $rules[$name] = $field['rules'] ?? 'nullable|string|max:255';
            }
        }
        $rules['sort_order'] = 'nullable|integer|min:0|max:9999';

        $data = $request->validate($rules);

        // Unchecked boxes are absent from the request, so they must be written
        // explicitly or an "unchecked" edit would silently keep the old value.
        foreach ($config['fields'] as $name => $field) {
            if (($field['type'] ?? 'text') === 'checkbox') {
                $data[$name] = $request->boolean($name);
            }
        }

        $data['sort_order'] = (int) ($request->input('sort_order') ?? 0);
        $data['active']     = $request->boolean('active');

        return $data;
    }
}
