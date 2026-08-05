@extends('layouts.app')
@section('title', setting('home_title'))

@section('content')

{{-- Every string, list and toggle on this page is editable from the admin panel:
     text under Settings & Text, repeating blocks under Site Content.
     "rich" settings are printed unescaped so <em>/<strong> work — they are only
     ever written by an authenticated admin. --}}

{{-- ═══════════════════════════════ HERO ═══════════════════════════════ --}}
@if(setting_on('hero_show'))
<section class="hero" id="hero">
  <div class="hero-inner">
    <div class="hero-content">
      @if(setting_on('hero_badge_show') && setting('hero_badge_text'))
        <span class="hero-badge">
          <span class="badge-dot"></span>
          {{ setting('hero_badge_text') }}
        </span>
      @endif

      @if(setting('hero_eyebrow'))
        <span class="hero-eyebrow">{{ setting('hero_eyebrow') }}</span>
      @endif

      <h1 class="hero-title">{!! setting('hero_title') !!}</h1>

      <p class="hero-desc">{!! setting('hero_desc') !!}</p>

      <div class="hero-actions">
        @if(setting('hero_btn1_label'))
          <a href="{{ route('projects.index') }}" class="btn-primary">{{ setting('hero_btn1_label') }}</a>
        @endif

        @if(setting('hero_btn2_label') && setting('cv_file'))
          @php
            // Uploaded files carry a timestamp in their name — hand the visitor a clean one.
            $cvExt = pathinfo(setting('cv_file'), PATHINFO_EXTENSION) ?: 'pdf';
            $cvAs  = \Illuminate\Support\Str::slug(setting('name') ?: 'cv') . '-cv.' . $cvExt;
          @endphp
          <a href="{{ asset(setting('cv_file')) }}" download="{{ $cvAs }}" class="btn-ghost">{{ setting('hero_btn2_label') }}</a>
        @endif

        @if(setting('hero_btn3_label'))
          <a href="#contact" class="btn-link">{{ setting('hero_btn3_label') }}</a>
        @endif
      </div>

      @if(setting_on('hero_stats_show') && $heroStats->count())
        <div class="hero-stats">
          @foreach($heroStats as $stat)
            <div class="stat">
              <span class="stat-n">{{ $stat->number }}</span>
              <span class="stat-l">{{ $stat->label }}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <div class="hero-photo-wrap">
      <div class="photo-frame">
        @if($settings['profile_image'] ?? false)
          <img src="{{ asset('storage/' . $settings['profile_image']) }}" alt="{{ setting('name') }}" class="profile-photo" width="380" height="475">
        @else
          <div class="photo-placeholder">
            <span>{{ setting('initials') }}</span>
            <p>Upload your photo<br>in the admin panel</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endif


{{-- ═══════════════════════════════ ABOUT ═══════════════════════════════ --}}
@if(setting_on('about_show'))
<section class="about section-pad" id="about">
  <div class="container">
    <div class="about-grid">

      <div class="about-left reveal">
        <div class="section-tag">{{ setting('about_tag') }}</div>
        <h2 class="section-title">{!! setting('about_title') !!}</h2>

        @if(setting('about_text_1'))<p>{!! setting('about_text_1') !!}</p>@endif
        @if(setting('about_text_2'))<p>{!! setting('about_text_2') !!}</p>@endif

        @if($highlights->count())
          <div class="about-highlights">
            @foreach($highlights as $highlight)
              <div class="highlight-item">
                <span class="hi-icon"><i class="{{ $highlight->icon }}" aria-hidden="true"></i></span>
                <div>
                  <strong>{{ $highlight->title }}</strong>
                  @if($highlight->subtitle)<small>{{ $highlight->subtitle }}</small>@endif
                </div>
              </div>
            @endforeach
          </div>
        @endif

        @if(setting('about_btn_label'))
          <a href="#contact" class="btn-primary">{{ setting('about_btn_label') }}</a>
        @endif
      </div>

      <div class="about-right reveal">
        @if($techItems->count())
          <div class="tech-card">
            <div class="tech-card-header">
              <span class="tc-dot tc-red"></span>
              <span class="tc-dot tc-yellow"></span>
              <span class="tc-dot tc-green"></span>
              <span class="tc-title">{{ setting('about_card_title') }}</span>
            </div>

            <div class="tech-card-body">
              <div class="tech-grid">
                @foreach($techItems as $tech)
                  <div class="tech-item">
                    <i class="{{ $tech->icon }}" aria-hidden="true"></i>
                    <small>{{ $tech->label }}</small>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        @if($heroStats->count())
          <div class="exp-summary-cards">
            @foreach($heroStats as $stat)
              <div class="esc-item">
                <span class="esc-n">{{ $stat->number }}</span>
                <span class="esc-l">{{ $stat->short_label ?: $stat->label }}</span>
              </div>
            @endforeach
          </div>
        @endif
      </div>

    </div>
  </div>
</section>
@endif


{{-- ═══════════════════════════════ EXPERIENCE ═══════════════════════════════ --}}
@if(setting_on('exp_show') && $experiences->count())
<section class="experience section-pad" id="experience">
  <div class="container">
    <div class="section-head reveal">
      <div class="section-tag">{{ setting('exp_tag') }}</div>
      <h2 class="section-title">{{ setting('exp_title') }}</h2>
      <p class="section-sub">{{ setting('exp_sub') }}</p>
    </div>

    <div class="timeline">
      @foreach($experiences as $exp)
        <div class="tl-item {{ $exp->is_current ? 'tl-current' : '' }} reveal">

          <div class="tl-when">
            <span class="tl-period">{{ $exp->period }}</span>
            @if($exp->duration)
              <span class="tl-duration">{{ $exp->duration }}</span>
            @endif
          </div>

          <div class="tl-rail" aria-hidden="true">
            <span class="tl-node"></span>
          </div>

          <div class="tl-body">
            <h3 class="tl-role">{{ $exp->title }}</h3>
            <span class="tl-company">{{ $exp->company }}</span>
            @if($exp->is_current)
              <span class="tl-now">Current</span>
            @endif

            @if($exp->location)
              <span class="tl-location">
                <i class="fas fa-location-dot" aria-hidden="true"></i> {{ $exp->location }}
              </span>
            @endif

            @if($exp->bulletList())
              <ul class="tl-bullets">
                @foreach($exp->bulletList() as $bullet)
                  <li>{{ $bullet }}</li>
                @endforeach
              </ul>
            @endif

            @if($exp->tagList())
              <div class="tl-tags">
                @foreach($exp->tagList() as $tag)
                  <span class="tl-tag">{{ $tag }}</span>
                @endforeach
              </div>
            @endif
          </div>

        </div>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- ═══════════════════════════════ FEATURED PROJECTS ═══════════════════════════════ --}}
@if(setting_on('projects_show') && $featuredProjects->count())
<section class="projects-home section-pad" id="projects-home">
  <div class="container">
    <div class="section-head reveal">
      <div class="section-tag">{{ setting('projects_tag') }}</div>
      <h2 class="section-title">{{ setting('projects_title') }}</h2>
      <p class="section-sub">{{ setting('projects_sub') }}</p>
    </div>

    <div class="proj-grid-home">
      @foreach($featuredProjects as $project)
        <article class="proj-card-home {{ $loop->first ? 'is-featured' : '' }} reveal">
          <div class="proj-img-wrap">
            @if($project->image)
              <img src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->title }}" class="proj-img" loading="lazy">
            @else
              <div class="proj-img-placeholder"><span>{{ Str::substr($project->title, 0, 2) }}</span></div>
            @endif

            <div class="proj-overlay">
              <a href="{{ route('projects.show', $project->slug) }}" class="proj-view-btn">View Project →</a>
              @if($project->live_url)
                <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="proj-live-btn">Live ↗</a>
              @endif
            </div>
          </div>

          <div class="proj-info">
            <span class="proj-category">{{ $project->category }}</span>
            <h3 class="proj-title-card">{{ $project->title }}</h3>
            <p class="proj-excerpt">{{ Str::limit($project->description, $loop->first ? 180 : 100) }}</p>

            {{-- One measurable outcome per project. Fill "Impact" in the admin
                 panel — this is what turns a screenshot grid into a case-study grid. --}}
            @if(!empty($project->impact))
              <p class="proj-impact">{{ $project->impact }}</p>
            @endif

            <div class="proj-tech-list">
              @foreach(array_slice(explode(',', $project->tech_stack), 0, 5) as $tech)
                <span>{{ trim($tech) }}</span>
              @endforeach
            </div>
          </div>
        </article>
      @endforeach
    </div>

    @if(setting('projects_btn'))
      <div class="section-cta reveal">
        <a href="{{ route('projects.index') }}" class="btn-outline">{{ setting('projects_btn') }}</a>
      </div>
    @endif
  </div>
</section>
@endif


{{-- ═══════════════════════════════ SKILLS ═══════════════════════════════ --}}
@if(setting_on('skills_show') && $skillGroups->count())
<section class="skills section-pad" id="skills">
  <div class="container">
    <div class="section-head reveal">
      <div class="section-tag">{{ setting('skills_tag') }}</div>
      <h2 class="section-title">{{ setting('skills_title') }}</h2>
      <p class="section-sub">{{ setting('skills_sub') }}</p>
    </div>

    {{-- Grid, not tabs: recruiters skim and Ctrl-F. Tabs would hide five of six
         categories behind a click and cost you keyword matches. --}}
    <div class="skills-grid">
      @foreach($skillGroups as $group)
        <div class="skill-card reveal">
          <div class="skill-icon"><i class="{{ $group->icon }}" aria-hidden="true"></i></div>
          <h3 class="skill-group-title">{{ $group->title }}</h3>
          <div class="skill-pills">
            @foreach($group->skillList() as $skill)
              <span class="skill-pill">{{ $skill }}</span>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- ═══════════════════════════════ TESTIMONIALS ═══════════════════════════════ --}}
@if(setting_on('testi_show') && $testimonials->count())
<section class="testimonials section-pad" id="testimonials">
  <div class="container">
    <div class="section-head reveal">
      <div class="section-tag">{{ setting('testi_tag') }}</div>
      <h2 class="section-title">{{ setting('testi_title') }}</h2>
      <p class="section-sub">{{ setting('testi_sub') }}</p>
    </div>

    {{-- Star rows removed: five identical 5-star ratings read as fabricated and
         cost more trust than they buy. Company and role carry the credibility. --}}
    <div class="testi-grid">
      @foreach($testimonials as $t)
        <figure class="testi-card reveal">
          <div class="testi-quote" aria-hidden="true">&ldquo;</div>
          <blockquote class="testi-text">{{ $t->message }}</blockquote>
          <figcaption class="testi-author">
            @if($t->avatar)
              <img src="{{ asset('storage/' . $t->avatar) }}" alt="" class="testi-avatar" loading="lazy">
            @else
              <div class="testi-avatar-placeholder" aria-hidden="true">{{ Str::substr($t->name, 0, 1) }}</div>
            @endif
            <div>
              <strong>{{ $t->name }}</strong>
              <small>{{ $t->position }} · {{ $t->company }}</small>
            </div>
          </figcaption>
        </figure>
      @endforeach
    </div>
  </div>
</section>
@endif


{{-- ═══════════════════════════════ BLOG ═══════════════════════════════ --}}
@if(setting_on('blog_show') && $latestPosts->count())
<section class="blog-preview section-pad" id="blog">
  <div class="container">
    <div class="section-head reveal">
      <div class="section-tag">{{ setting('blog_tag') }}</div>
      <h2 class="section-title">{{ setting('blog_title') }}</h2>
      <p class="section-sub">{{ setting('blog_sub') }}</p>
    </div>

    {{-- A hairline list, not a card grid. Three cards look like an empty shelf;
         three rows look like a deliberate index. --}}
    <div class="blog-list reveal">
      @foreach($latestPosts as $post)
        <a href="{{ route('blog.show', $post->slug) }}" class="blog-row">
          <span class="blog-row-date">{{ $post->created_at->format('d M Y') }}</span>

          <span class="blog-row-main">
            <span class="blog-row-title">{{ $post->title }}</span>
            <span class="blog-row-excerpt">{{ Str::limit($post->excerpt, 120) }}</span>
          </span>

          <span class="blog-row-meta">{{ $post->read_time ?? 5 }} min read →</span>
        </a>
      @endforeach
    </div>

    @if(setting('blog_btn'))
      <div class="section-cta reveal">
        <a href="{{ route('blog.index') }}" class="btn-outline">{{ setting('blog_btn') }}</a>
      </div>
    @endif
  </div>
</section>
@endif


{{-- ═══════════════════════════════ CONTACT ═══════════════════════════════ --}}
@if(setting_on('contact_show'))
<section class="contact section-pad" id="contact">
  <div class="container">
    <div class="contact-grid">

      <div class="contact-info reveal">
        <div class="section-tag">{{ setting('contact_tag') }}</div>
        <h2 class="section-title">{!! setting('contact_title') !!}</h2>
        <p>{{ setting('contact_text') }}</p>

        @php $contactLinks = ($socialLinks ?? collect())->where('in_contact', true); @endphp

        @if($contactLinks->count())
          <div class="contact-items">
            @foreach($contactLinks as $link)
              <a href="{{ $link->url }}"
                 @if($link->isExternal()) target="_blank" rel="noopener noreferrer" @endif
                 class="contact-item">
                <span class="ci-icon"><i class="{{ $link->icon }}" aria-hidden="true"></i></span>
                <span class="ci-text">
                  <strong>{{ $link->label }}</strong>
                  <span class="ci-value">{{ $link->value }}</span>
                </span>
              </a>
            @endforeach
          </div>
        @endif
      </div>

      <div class="contact-form-wrap reveal">
        <form action="{{ route('contact.send') }}" method="POST" class="contact-form" id="contactForm">
          @csrf

          @if(session('success'))
            <div class="form-success" role="status">
              <i class="fas fa-check" aria-hidden="true"></i>
              {{ setting('contact_success_msg') }}
            </div>
          @endif

          <div class="form-row">
            <label for="cf-name">Full Name *</label>
            <input type="text" id="cf-name" name="name" placeholder="John Smith" required value="{{ old('name') }}"
                   @error('name') aria-invalid="true" @enderror>
            @error('name')<span class="form-error">{{ $message }}</span>@enderror
          </div>

          <div class="form-row-2">
            <div class="form-row">
              <label for="cf-email">Email *</label>
              <input type="email" id="cf-email" name="email" placeholder="john@company.com" required value="{{ old('email') }}"
                     @error('email') aria-invalid="true" @enderror>
              @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
              <label for="cf-phone">Phone</label>
              <input type="tel" id="cf-phone" name="phone" placeholder="+1 234 567 890" value="{{ old('phone') }}">
            </div>
          </div>

          @php $projectTypes = setting_lines('contact_project_types'); @endphp
          @if($projectTypes)
            <div class="form-row">
              <label for="cf-type">Project Type</label>
              <select id="cf-type" name="project_type">
                <option value="">Select project type</option>
                @foreach($projectTypes as $type)
                  <option value="{{ $type }}" @selected(old('project_type') === $type)>{{ $type }}</option>
                @endforeach
              </select>
            </div>
          @endif

          <div class="form-row">
            <label for="cf-message">Message *</label>
            <textarea id="cf-message" name="message" rows="5" placeholder="Tell me about your project, timeline and budget…" required
                      @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
            @error('message')<span class="form-error">{{ $message }}</span>@enderror
          </div>

          <button type="submit" class="btn-primary form-btn">{{ setting('contact_btn_label') }}</button>
        </form>
      </div>

    </div>
  </div>
</section>
@endif

@endsection
