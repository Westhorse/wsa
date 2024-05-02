<?php
namespace App\Http\Traits;

use App\Http\Resources\Common\MediaResource;
use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

trait HasMedia
{

    public function hasMedia(): bool
    {
        if($this->media->count()){
            return true ;
        }
        return false ;
    }

    public function getMediaResource($collection = 'default'): AnonymousResourceCollection
    {
        return MediaResource::collection($this->getMedia($collection));
    }

    public function getFirstMediaResource($collection = 'default'): MediaResource
    {
        return new MediaResource($this->getFirstMedia($collection));
    }

    public function getFirstMediaUrl($collection = 'default')
    {
        return $this->hasMedia() && $this->getFirstMedia($collection) ? $this->getFirstMedia($collection)->full_url  : asset('storage/images/default-logo.png');
    }

    public function getFirstMediaUrlNot($collection = 'default')
    {
        return $this->hasMedia() && $this->getFirstMedia($collection) ? $this->getFirstMedia($collection)->full_url  : null;
    }

    public function getMedia($collection = 'default')
    {
        return $this->media->where('pivot.collection' , $collection);
    }

    public function getFirstMedia($collection = 'default')
    {
        return $this->media->where('pivot.collection' , $collection)->first();
    }

    public function getAllMedia()
    {
        return $this->media;
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(
            Media::class,
            'model',
            'mediable',
            'model_id',
            'media_id'
        )->withPivot(['collection']);
    }

}
