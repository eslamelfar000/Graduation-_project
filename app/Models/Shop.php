<?php

namespace App\Models;

use App\Models\Review;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Shop extends Model implements HasMedia
{

    use InteractsWithMedia;
    
    use HasFactory;
    protected $fillable = [
        'title','newPrice','oldPrice','offer','category','color','size'  ,'abstract ','featuer','pin_code','description','videos'
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class,'product_id');
    }
    /**
     * Returns a list of the allowed files to be uploaded.
     *
     * @return array
     */
    public static function formRequestFileKeys(): array
    {
        return [
            'image' => [
                'image_1',
            ],
            'images' => [
                'image_2',
                'image_3',
                'image_4',
                'image_5',
                'image_6',
            ],
        ];
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('images')
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, config('media-library.allowed_image_types'));
            })
            ->useFallbackUrl(asset('assets/img/no-image.jpg'));

        $this
            ->addMediaCollection('image')
            ->singleFile()
            ->acceptsFile(function (File $file) {
                return in_array($file->mimeType, config('media-library.allowed_image_types'));
            })
            ->useFallbackUrl(asset('assets/img/no-image.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        foreach (['50', '200'] as $size) {
            $this->addMediaConversion($size)
                ->performOnCollections('images', 'image')
                ->format(Manipulations::FORMAT_JPG)
                ->quality(90)
                ->fit(Manipulations::FIT_CROP, $size, $size)
                ->optimize()
                ->{$size == 200 ? 'nonQueued' : 'queued'}();
        }

        $this->addMediaConversion('large')
            ->performOnCollections('images', 'image')
            ->format(Manipulations::FORMAT_JPG)
            ->quality(90)
            // ->width(1200)
            ->fit(Manipulations::FIT_CROP, 1200, ceil(1200 / 16 * 9))
            ->optimize();

        $this->addMediaConversion('medium')
            ->performOnCollections('images', 'image')
            ->format(Manipulations::FORMAT_JPG)
            ->quality(90)
            ->width(640)
            // ->fit(Manipulations::FIT_CROP, 640, ceil(640/16*9))
            ->optimize();

        $this->addMediaConversion('small')
            ->performOnCollections('images', 'image')
            ->format(Manipulations::FORMAT_JPG)
            ->quality(90)
            ->fit(Manipulations::FIT_CROP, 360, ceil(360 / 4 * 3))
            // ->width(360)
            ->optimize();
    }
    
}
