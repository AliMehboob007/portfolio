@extends('layouts.admin')
@section('admin-title', 'Site Settings')
@section('admin-content')

@php
  /**
   * Every editable text on the public site, grouped into tabs.
   * Field shape: [name, label, type, hint]
   * type: text | textarea | rich | toggle | email | url | number
   * "rich" fields are rendered with {!! !!} on the front end, so basic HTML
   * such as <em> and <strong> works there.
   */
  $tabs = [
    'profile' => ['label' => 'Profile', 'icon' => 'fas fa-user', 'fields' => [
      ['name',     'Full Name',        'text'],
      ['tagline',  'Tagline / Role',   'text',     'Shown in the footer and as the default page subtitle.'],
      ['initials', 'Logo Initials',    'text',     'The two letters in the header and footer logo.'],
      ['bio',      'Short Bio',        'textarea', 'Optional. A one-paragraph summary of you.'],
      ['email',    'Email',            'email'],
      ['phone',    'Phone (display)',  'text',     'How the number is printed, e.g. +92 314 616 7055'],
      ['whatsapp', 'WhatsApp Number',  'text',     'Digits only, with country code — used for the wa.me link.'],
      ['location', 'Location',         'text'],
      ['github',   'GitHub URL',       'url'],
      ['linkedin', 'LinkedIn URL',     'url'],
      ['cv_file',  'CV File Path',     'text',     'Set automatically when you upload a CV above. Only edit this to point at a file you placed in /public by hand.'],
    ]],

    'hero' => ['label' => 'Hero', 'icon' => 'fas fa-star', 'fields' => [
      ['hero_show',       'Show hero section',        'toggle'],
      ['hero_badge_show', 'Show availability badge',  'toggle'],
      ['hero_badge_text', 'Badge Text',               'text'],
      ['hero_eyebrow',    'Eyebrow Line',             'text',     'Small line above the headline.'],
      ['hero_title',      'Headline',                 'rich',     'Wrap a word in <em>…</em> to give it the accent style.'],
      ['hero_desc',       'Intro Paragraph',          'rich',     '<strong>…</strong> is allowed for emphasis.'],
      ['hero_btn1_label', 'Primary Button Label',     'text',     'Links to the Projects page.'],
      ['hero_btn2_label', 'CV Button Label',          'text',     'Downloads the CV file set under Profile.'],
      ['hero_btn3_label', 'Text Link Label',          'text',     'Scrolls to the contact section.'],
      ['hero_stats_show', 'Show stat counters',       'toggle',   'Edit the numbers under Site Content → Hero Stats.'],
    ]],

    'about' => ['label' => 'About', 'icon' => 'fas fa-address-card', 'fields' => [
      ['about_show',       'Show about section', 'toggle'],
      ['about_tag',        'Section Tag',        'text'],
      ['about_title',      'Section Title',      'rich'],
      ['about_text_1',     'Paragraph 1',        'rich'],
      ['about_text_2',     'Paragraph 2',        'rich'],
      ['about_btn_label',  'Button Label',       'text'],
      ['about_card_title', 'Tech Card Title',    'text', 'The filename shown on the tech-stack card.'],
    ]],

    'sections' => ['label' => 'Sections', 'icon' => 'fas fa-table-columns', 'fields' => [
      ['exp_show',       'Show experience section', 'toggle'],
      ['exp_tag',        'Experience — Tag',        'text'],
      ['exp_title',      'Experience — Title',      'text'],
      ['exp_sub',        'Experience — Subtitle',   'text'],
      ['projects_show',  'Show featured projects',  'toggle'],
      ['projects_tag',   'Projects — Tag',          'text'],
      ['projects_title', 'Projects — Title',        'text'],
      ['projects_sub',   'Projects — Subtitle',     'text'],
      ['projects_btn',   'Projects — Button',       'text'],
      ['projects_limit', 'Projects — How many',     'number', 'Featured projects shown on the homepage.'],
      ['skills_show',    'Show skills section',     'toggle'],
      ['skills_tag',     'Skills — Tag',            'text'],
      ['skills_title',   'Skills — Title',          'text'],
      ['skills_sub',     'Skills — Subtitle',       'text'],
      ['testi_show',     'Show testimonials',       'toggle'],
      ['testi_tag',      'Testimonials — Tag',      'text'],
      ['testi_title',    'Testimonials — Title',    'text'],
      ['testi_sub',      'Testimonials — Subtitle', 'text'],
      ['blog_show',      'Show blog teaser',        'toggle'],
      ['blog_tag',       'Blog — Tag',              'text'],
      ['blog_title',     'Blog — Title',            'text'],
      ['blog_sub',       'Blog — Subtitle',         'text'],
      ['blog_btn',       'Blog — Button',           'text'],
      ['blog_limit',     'Blog — How many',         'number', 'Latest posts shown on the homepage.'],
    ]],

    'contact' => ['label' => 'Contact', 'icon' => 'fas fa-envelope', 'fields' => [
      ['contact_show',          'Show contact section', 'toggle'],
      ['contact_tag',           'Section Tag',          'text'],
      ['contact_title',         'Section Title',        'rich'],
      ['contact_text',          'Intro Paragraph',      'textarea'],
      ['contact_btn_label',     'Submit Button Label',  'text'],
      ['contact_success_msg',   'Success Message',      'text',     'Shown after a visitor sends the form.'],
      ['contact_project_types', 'Project Type Options', 'textarea', 'One option per line — these fill the dropdown.'],
    ]],

    'pages' => ['label' => 'Pages', 'icon' => 'fas fa-file-lines', 'fields' => [
      ['projects_page_tag',   'Projects Page — Tag',         'text'],
      ['projects_page_title', 'Projects Page — Title',       'text'],
      ['projects_page_desc',  'Projects Page — Intro',       'textarea'],
      ['projects_page_meta',  'Projects Page — Browser Title', 'text'],
      ['blog_page_tag',       'Blog Page — Tag',             'text'],
      ['blog_page_title',     'Blog Page — Title',           'text'],
      ['blog_page_desc',      'Blog Page — Intro',           'textarea'],
      ['blog_page_meta',      'Blog Page — Browser Title',   'text'],
      ['author_bio',          'Blog Post — Author Line',     'text', 'Shown in the author box under every article.'],
    ]],

    'footer' => ['label' => 'Header & Footer', 'icon' => 'fas fa-shoe-prints', 'fields' => [
      ['nav_logo',              'Header Logo Text',     'text'],
      ['nav_hire_label',        'Header Button Label',  'text'],
      ['footer_brand_desc',     'Footer Brand Blurb',   'textarea', 'One line per row.'],
      ['footer_nav_title',      'Footer — Nav Heading', 'text'],
      ['footer_connect_title',  'Footer — Connect Heading', 'text'],
      ['footer_location_title', 'Footer — Location Heading', 'text'],
      ['footer_availability',   'Availability Line',    'text'],
      ['footer_copyright',      'Copyright Line',       'text', 'The year is added automatically.'],
    ]],

    'seo' => ['label' => 'SEO', 'icon' => 'fas fa-magnifying-glass', 'fields' => [
      ['site_title',       'Default Browser Title', 'text'],
      ['home_title',       'Homepage Title',        'text'],
      ['meta_description', 'Meta Description',      'textarea', 'Aim for 150–160 characters.'],
      ['meta_keywords',    'Meta Keywords',         'textarea'],
      ['og_description',   'Social Share Text',     'textarea', 'Used when the site is shared on LinkedIn, X, WhatsApp.'],
    ]],
  ];

  $activeTab = request('tab', 'profile');
  if (!isset($tabs[$activeTab])) { $activeTab = 'profile'; }
@endphp

<div class="settings-layout">

  {{-- ── TAB RAIL ── --}}
  <nav class="settings-tabs" aria-label="Settings sections">
    @foreach($tabs as $key => $tab)
      <a href="{{ route('admin.settings', ['tab' => $key]) }}"
         class="settings-tab {{ $activeTab === $key ? 'active' : '' }}">
        <i class="{{ $tab['icon'] }}" aria-hidden="true"></i>
        <span>{{ $tab['label'] }}</span>
      </a>
    @endforeach

    <div class="settings-tab-divider"></div>

    @foreach(\App\Http\Controllers\ContentController::menu() as $entry)
      <a href="{{ route('admin.content.index', $entry['key']) }}" class="settings-tab">
        <i class="{{ $entry['icon'] }}" aria-hidden="true"></i>
        <span>{{ $entry['label'] }}</span>
      </a>
    @endforeach
  </nav>

  <div class="settings-panel">

    {{-- ── PROFILE PHOTO (only on the Profile tab) ── --}}
    @if($activeTab === 'profile')
      <div class="admin-form-card" style="margin-bottom:1.5rem">
        <h3 class="card-heading">Profile Photo</h3>

        <div style="display:flex;gap:1.5rem;align-items:flex-start;flex-wrap:wrap">
          @if($settings['profile_image'] ?? false)
            <img src="{{ asset('storage/' . $settings['profile_image']) }}" alt="Current profile photo"
                 style="width:110px;height:130px;object-fit:cover;border-radius:12px;border:1px solid var(--border)">
          @endif

          <form action="{{ route('admin.settings.photo') }}" method="POST" enctype="multipart/form-data" style="flex:1;min-width:260px">
            @csrf
            <div class="form-group">
              <div class="img-upload-wrap" onclick="document.getElementById('photoInput').click()">
                <img class="img-preview" id="photoPreview" alt="">
                <div><i class="fas fa-camera" aria-hidden="true"></i></div>
                <p>Click to upload — JPG or PNG, max 2 MB</p>
                <p style="font-size:.75rem;color:var(--muted);margin-top:4px">Recommended: 400×450px portrait</p>
              </div>
              <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none"
                     onchange="var p=document.getElementById('photoPreview');p.src=URL.createObjectURL(this.files[0]);p.style.display='block'">
            </div>
            <button type="submit" class="btn btn-primary-sm">Upload Photo</button>
          </form>
        </div>
      </div>

      {{-- ── CV / RESUME ── --}}
      @php
        $cvPath   = $settings['cv_file'] ?? \App\Models\Setting::DEFAULTS['cv_file'] ?? '';
        $cvOnDisk = $cvPath && file_exists(public_path($cvPath));
      @endphp
      <div class="admin-form-card" style="margin-bottom:1.5rem">
        <h3 class="card-heading">CV / Resume</h3>

        @if($cvPath)
          <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;margin-bottom:1rem">
            <i class="fas fa-file-pdf" aria-hidden="true" style="font-size:1.5rem;color:{{ $cvOnDisk ? '#dc2626' : 'var(--muted)' }}"></i>
            <div style="flex:1;min-width:200px">
              <div style="font-size:.9rem;word-break:break-all">{{ basename($cvPath) }}</div>
              <small style="color:{{ $cvOnDisk ? 'var(--muted)' : '#dc2626' }}">
                {{ $cvOnDisk ? 'Live at /' . $cvPath : 'File not found at /' . $cvPath . ' — upload one below.' }}
              </small>
            </div>
            @if($cvOnDisk)
              <a href="{{ asset($cvPath) }}" target="_blank" rel="noopener" class="btn btn-outline-sm">View ↗</a>
            @endif
            <form action="{{ route('admin.settings.cv.delete') }}" method="POST" style="display:inline"
                  onsubmit="return confirm('Remove the CV? The download button will be hidden until you upload a new one.')">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-outline-sm" style="color:#dc2626;border-color:#dc2626">Remove</button>
            </form>
          </div>
        @else
          <p style="font-size:.85rem;color:var(--muted);margin-bottom:1rem">No CV set — the hero download button is hidden.</p>
        @endif

        <form action="{{ route('admin.settings.cv') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group">
            <div class="img-upload-wrap" onclick="document.getElementById('cvInput').click()">
              <div><i class="fas fa-file-arrow-up" aria-hidden="true"></i></div>
              <p id="cvName">Click to upload — PDF, DOC or DOCX, max 5 MB</p>
              <p style="font-size:.75rem;color:var(--muted);margin-top:4px">Replaces the current CV and updates the path below automatically.</p>
            </div>
            <input type="file" id="cvInput" name="cv" accept=".pdf,.doc,.docx,application/pdf" style="display:none"
                   onchange="document.getElementById('cvName').textContent = this.files[0] ? this.files[0].name : ''">
          </div>
          <button type="submit" class="btn btn-primary-sm">Upload CV</button>
        </form>
      </div>
    @endif

    {{-- ── FIELDS ── --}}
    <form action="{{ route('admin.settings.update') }}" method="POST" class="admin-form-card">
      @csrf
      <input type="hidden" name="tab" value="{{ $activeTab }}">

      <h3 class="card-heading">{{ $tabs[$activeTab]['label'] }}</h3>

      @foreach($tabs[$activeTab]['fields'] as $field)
        @php
          [$name, $label] = $field;
          $type = $field[2] ?? 'text';
          $hint = $field[3] ?? null;
          $value = old($name, $settings[$name] ?? \App\Models\Setting::DEFAULTS[$name] ?? '');
        @endphp

        @if($type === 'toggle')
          <div class="form-group">
            {{-- Paired hidden input so an unchecked box still posts a value. --}}
            <input type="hidden" name="{{ $name }}" value="0">
            <label class="form-check">
              <input type="checkbox" name="{{ $name }}" value="1" @checked((string) $value === '1')>
              <span>{{ $label }}</span>
            </label>
            @if($hint)<small class="field-hint">{{ $hint }}</small>@endif
          </div>

        @elseif(in_array($type, ['textarea', 'rich'], true))
          <div class="form-group">
            <label for="s-{{ $name }}">{{ $label }}</label>
            <textarea id="s-{{ $name }}" name="{{ $name }}" rows="{{ $type === 'rich' ? 3 : 4 }}">{{ $value }}</textarea>
            @if($hint)<small class="field-hint">{!! $hint !!}</small>@endif
          </div>

        @else
          <div class="form-group">
            <label for="s-{{ $name }}">{{ $label }}</label>
            <input id="s-{{ $name }}"
                   type="{{ in_array($type, ['email','url','number'], true) ? $type : 'text' }}"
                   name="{{ $name }}" value="{{ $value }}">
            @if($hint)<small class="field-hint">{!! $hint !!}</small>@endif
          </div>
        @endif
      @endforeach

      <div class="form-actions">
        <button type="submit" class="btn btn-primary-sm">Save {{ $tabs[$activeTab]['label'] }}</button>
        <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-sm">Preview Site ↗</a>
      </div>
    </form>

  </div>
</div>

@endsection
