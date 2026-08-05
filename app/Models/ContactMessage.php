<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model {
    protected $fillable = ['name','email','phone','project_type','message','read'];
    protected $casts    = ['read' => 'boolean'];
}
