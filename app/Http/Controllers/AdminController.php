<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Models\Testimonial;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /** Where the CV lives, relative to /public — served as a plain static file. */
    private const CV_DIR = 'files';

    // ── AUTH ──────────────────────────────────────────────────────────────

    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }

    // ── DASHBOARD ─────────────────────────────────────────────────────────

    public function dashboard()
    {
        return view('admin.dashboard', [
            'projectCount'      => Project::count(),
            'featuredCount'     => Project::where('is_featured', true)->count(),
            'missingImpact'     => Project::whereNull('impact')->orWhere('impact', '')->count(),

            'postCount'         => BlogPost::count(),
            'publishedCount'    => BlogPost::where('published', true)->count(),

            'messageCount'      => ContactMessage::where('read', false)->count(),
            'totalMessages'     => ContactMessage::count(),

            'testimonialCount'  => Testimonial::count(),
            'activeTestimonials'=> Testimonial::where('active', true)->count(),

            'latestMessages'    => ContactMessage::latest()->take(6)->get(),
            'latestProjects'    => Project::latest()->take(5)->get(),
        ]);
    }

    // ── SETTINGS / PROFILE ────────────────────────────────────────────────

    public function settings()
    {
        $settings = Setting::getAllSettings();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        // The settings screen posts one tab at a time, so only the keys present
        // in this request are written — and only keys the app actually knows.
        $allowed = array_keys(Setting::DEFAULTS);

        foreach ($allowed as $key) {
            if ($request->has($key)) {
                Setting::set($key, (string) $request->input($key));
            }
        }

        return redirect()
            ->route('admin.settings', ['tab' => $request->input('tab', 'profile')])
            ->with('success', 'Settings saved — your site is updated.');
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate(['photo' => 'required|image|max:2048']);
        $path = $request->file('photo')->store('profile', 'public');
        Setting::set('profile_image', $path);
        return back()->with('success', 'Profile photo updated!');
    }

    public function uploadCv(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $this->deleteStoredCv();

        $file = $request->file('cv');
        // Slug + timestamp: readable when downloaded, and a fresh URL each upload
        // so a browser never serves the previously cached CV.
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) ?: 'cv')
              . '-' . time() . '.' . $file->getClientOriginalExtension();

        // Straight into public/files rather than the storage disk: the download
        // is then a plain static file, with no storage symlink in the way.
        $file->move(public_path(self::CV_DIR), $name);
        Setting::set('cv_file', self::CV_DIR . '/' . $name);

        return back()->with('success', 'CV uploaded — the download button now serves the new file.');
    }

    public function deleteCv()
    {
        $this->deleteStoredCv();
        Setting::set('cv_file', '');

        return back()->with('success', 'CV removed — the download button is now hidden.');
    }

    /**
     * Delete the CV we uploaded, if any. Only files we put there ourselves are
     * touched — a path typed in by hand pointing elsewhere in /public is left
     * alone, so nobody can aim this at an arbitrary file.
     */
    private function deleteStoredCv(): void
    {
        $current = Setting::getAllSettings()['cv_file'] ?? '';

        if (! $current) {
            return;
        }

        // Uploads made before the switch to public/files sat on the storage disk.
        if (Str::startsWith($current, 'storage/')) {
            Storage::disk('public')->delete(Str::after($current, 'storage/'));
            return;
        }

        $file = realpath(public_path($current));
        $dir  = realpath(public_path(self::CV_DIR));

        if ($file && $dir && is_file($file) && Str::startsWith($file, $dir . DIRECTORY_SEPARATOR)) {
            @unlink($file);
        }
    }

    // ── PROJECTS ──────────────────────────────────────────────────────────

    public function projects()
    {
        $projects = Project::orderBy('sort_order')->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    public function createProject()
    {
        return view('admin.projects.form', ['project' => null]);
    }

    public function storeProject(Request $request)
    {
        $data = $this->validateProject($request);
        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        if ($request->hasFile('gallery')) {
            $gallery = [];
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('projects/gallery', 'public');
            }
            $data['gallery'] = json_encode($gallery);
        }

        Project::create($data);
        return redirect()->route('admin.projects')->with('success', 'Project created!');
    }

    public function editProject($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.form', compact('project'));
    }

    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        $data = $this->validateProject($request);

        if ($request->hasFile('image')) {
            if ($project->image) Storage::disk('public')->delete($project->image);
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $request->validate([
            'remove_gallery'   => 'nullable|array',
            'remove_gallery.*' => 'string',
        ]);

        $gallery = $project->gallery ? json_decode($project->gallery, true) : [];
        if (!is_array($gallery)) $gallery = [];

        // Delete the images the user marked for removal
        $remove = (array) $request->input('remove_gallery', []);
        $removed = array_intersect($gallery, $remove);
        if ($removed) {
            foreach ($removed as $path) {
                Storage::disk('public')->delete($path);
            }
            $gallery = array_values(array_diff($gallery, $removed));
        }

        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $gallery[] = $file->store('projects/gallery', 'public');
            }
        }

        if ($removed || $request->hasFile('gallery')) {
            $data['gallery'] = $gallery ? json_encode(array_values($gallery)) : null;
        }

        $project->update($data);
        return redirect()->route('admin.projects')->with('success', 'Project updated!');
    }

    public function deleteProject($id)
    {
        $project = Project::findOrFail($id);
        if ($project->image) Storage::disk('public')->delete($project->image);
        if ($project->gallery) {
            $gallery = json_decode($project->gallery, true);
            if (is_array($gallery) && $gallery) Storage::disk('public')->delete($gallery);
        }
        $project->delete();
        return back()->with('success', 'Project deleted!');
    }

    private function validateProject(Request $request): array
    {
        return $request->validate([
            'title'        => 'required|string|max:200',
            'category'     => 'required|string|max:100',
            'project_type' => 'nullable|string|max:150',
            'description'  => 'required|string',
            'impact'       => 'nullable|string|max:160',
            'challenges'   => 'nullable|string',
            'tech_stack'   => 'required|string',
            'live_url'     => 'nullable|url',
            'github_url'   => 'nullable|url',
            'client_name'  => 'nullable|string|max:100',
            'year'         => 'nullable|digits:4',
            'is_featured'  => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
            'status'       => 'nullable|string|max:50',
        ]);
    }

    // ── BLOG ──────────────────────────────────────────────────────────────

    public function blog()
    {
        $posts = BlogPost::latest()->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function createPost()
    {
        return view('admin.blog.form', ['post' => null]);
    }

    public function storePost(Request $request)
    {
        $data = $this->validatePost($request);
        $data['slug'] = Str::slug($request->title) . '-' . time();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        BlogPost::create($data);
        return redirect()->route('admin.blog')->with('success', 'Post created!');
    }

    public function editPost($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('admin.blog.form', compact('post'));
    }

    public function updatePost(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $data = $this->validatePost($request);

        if ($request->hasFile('image')) {
            if ($post->image) Storage::disk('public')->delete($post->image);
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        $post->update($data);
        return redirect()->route('admin.blog')->with('success', 'Post updated!');
    }

    public function deletePost($id)
    {
        $post = BlogPost::findOrFail($id);
        if ($post->image) Storage::disk('public')->delete($post->image);
        $post->delete();
        return back()->with('success', 'Post deleted!');
    }

    private function validatePost(Request $request): array
    {
        return $request->validate([
            'title'     => 'required|string|max:200',
            'excerpt'   => 'required|string|max:300',
            'body'      => 'required|string',
            'category'  => 'required|string|max:100',
            'published' => 'nullable|boolean',
            'read_time' => 'nullable|integer',
        ]);
    }

    // ── MESSAGES ──────────────────────────────────────────────────────────

    public function messages()
    {
        $messages = ContactMessage::latest()->paginate(20);
        ContactMessage::where('read', false)->update(['read' => true]);
        return view('admin.messages', compact('messages'));
    }

    public function deleteMessage($id)
    {
        ContactMessage::findOrFail($id)->delete();
        return back()->with('success', 'Message deleted!');
    }

    // ── TESTIMONIALS ──────────────────────────────────────────────────────

    public function testimonials()
    {
        $testimonials = Testimonial::orderBy('sort_order')->get();
        return view('admin.testimonials', compact('testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'position' => 'required|string|max:100',
            'company'  => 'required|string|max:100',
            'message'  => 'required|string',
            'rating'   => 'required|integer|min:1|max:5',
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        Testimonial::create($data);
        return back()->with('success', 'Testimonial added!');
    }

    public function deleteTestimonial($id)
    {
        Testimonial::findOrFail($id)->delete();
        return back()->with('success', 'Testimonial deleted!');
    }
}
