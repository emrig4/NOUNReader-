<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Modules\Resource\Helpers\SubFieldHelper;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Modules\File\Http\Traits\HasFile;
use App\Modules\Resource\Models\ResourceAuthor;
use App\Modules\Resource\Models\ResourceReview;
use App\Modules\Resource\Models\ResourceReport;
use Illuminate\Database\Eloquent\Builder;

class Resource extends Model
{
    use Sluggable, HasFile;

    /**
     * ✅ ADDED: Fillable attributes to allow mass assignment
     * This allows setting pages, title, and other fields
     */
    protected $fillable = [
        'title',
        'overview',      // ✅ ADD THIS
        'slug',
        'description',
        'field',
        'type',
        'sub_fields',
        'price',
        'currency',
        'pages',
        'page_count',
        'is_published',
        'approval_status',
        'submitted_at',
        'approved_at',
        'user_id',
        'view_count',
        'read_count',
        'download_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(function (Builder $builder) {
            return $builder->where('is_published', true)->orderBy('created_at', 'ASC');
        });

        static::creating(function ($resource) {
            $user = auth()->user();
            $resource->sub_fields = SubFieldHelper::processSubfields($resource->sub_fields);
            $resource->user_id = $user->id;
        });
    }

    public function createCover($title)
    {
        $img = Image::make(public_path('images/codermen.jpg'));
        $img->text($title, 120, 100, function($font) {
            $font->file(public_path('path/font.ttf'));
            $font->size(28);
            $font->color('#4285F4');
            $font->align('center');
            $font->valign('bottom');
            $font->angle(0);
        });
        $img->save(public_path('images/text_with_image.jpg'));
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function related()
    {
        return self::where('field', $this->field)->limit(12)->get();
    }

    public function reviews()
    {
        return $this->hasMany(ResourceReview::class)->with('user');
    }

    public function reports()
    {
        return $this->hasMany(ResourceReport::class);
    }

    public function rating()
    {
        $reviews = $this->reviews()->get();
        $rating = 0;
        foreach ($reviews as $review) {
            $rating += $review->rating;
        }

        $rate = 0;
        if (count($reviews) > 0) {
            $rate = $rating / count($reviews);
        }
        return $rate;
    }

    public function authors()
    {
        return $this->hasMany(ResourceAuthor::class);
    }

    public function author()
    {
        return $this->authors()->whereIsLead(1)->first();
    }

    public function isNew()
    {
        return $this->created_at > now()->subDays(7);
    }

    public function isTop()
    {
        return false;
    }

    /**
     * ✅ NEW METHOD: Get formatted page count for display
     * Usage: {{ $resource->getPageCountFormatted() }}
     */
    public function getPageCountFormatted()
    {
        if ($this->pages && $this->pages > 0) {
            return $this->pages . ' page' . ($this->pages != 1 ? 's' : '');
        }
        return 'Pages not available';
    }
}