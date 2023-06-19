<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class file extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
    'file_code',
    'file_name',
    'file_description',
    'file_source',
    'file_destination',
    'file_receivedDate',
    'file_releasedDate',
    'file_receivedBy',
    'file_status',
    'sub_storage_id',
    'storage_id',
    'file_images',
    'documents',
    'file_link'
    ];

    protected $casts = [
        'file_images' => 'array',
        'documents' => 'array',
    ];
    public function sub_storage()
    {
        return $this->belongsTo(sub_storage::class);
    }
    public function storage()
    {
        return $this->belongsTo(storage::class);
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
