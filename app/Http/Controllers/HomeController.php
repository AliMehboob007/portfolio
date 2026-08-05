<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Experience;
use App\Models\HeroStat;
use App\Models\Highlight;
use App\Models\Project;
use App\Models\Setting;
use App\Models\SkillGroup;
use App\Models\SocialLink;
use App\Models\TechItem;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        // How many featured items to show is itself an admin setting.
        $projectLimit = max(1, (int) Setting::get('projects_limit', 6));
        $blogLimit    = max(1, (int) Setting::get('blog_limit', 3));

        $featuredProjects = Project::where('is_featured', true)
            ->orderBy('sort_order')
            ->take($projectLimit)
            ->get();

        $latestPosts = BlogPost::where('published', true)
            ->orderBy('created_at', 'desc')
            ->take($blogLimit)
            ->get();

        $testimonials = Testimonial::where('active', true)
            ->orderBy('sort_order')
            ->get();

        return view('home.index', [
            'featuredProjects' => $featuredProjects,
            'latestPosts'      => $latestPosts,
            'testimonials'     => $testimonials,
            'settings'         => Setting::getAllSettings(),
            'experiences'      => Experience::live(),
            'skillGroups'      => SkillGroup::live(),
            'techItems'        => TechItem::live(),
            'highlights'       => Highlight::live(),
            'heroStats'        => HeroStat::live(),

            // The layout composer shares this too, but a child view is rendered
            // before its layout — so the contact section needs its own copy.
            'socialLinks'      => SocialLink::live(),
        ]);
    }
}
