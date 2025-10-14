<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomepageContent extends Model
{

    use HasFactory;

    protected $fillable
        = [
            'hero_title',
            'hero_subtitle',
            'hero_cta_experience',
            'hero_cta_contact',
            'sytatsu_text',
            'about_title',
            'about_paragraph',
            'skills_title',
            'experience_title',
            'contact_title',
            'footer_text',
        ];
}
