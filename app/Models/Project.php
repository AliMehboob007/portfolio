<?php
// ═══════════════════════════════════════════════════════
// app/Models/Project.php
// ═══════════════════════════════════════════════════════
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model {
    use HasFactory;
    protected $fillable = [
        'title','slug','category','project_type','description','impact','challenges',
        'tech_stack','image','gallery','live_url','github_url',
        'client_name','year','is_featured','sort_order','status'
    ];
    protected $casts = ['is_featured' => 'boolean'];
}
