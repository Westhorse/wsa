<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $networkSlug = getNetworkSlug();
        $pageSections= PageSectionResource::collection($this->pageSections)->where('active', true) ?? '';
        $pageSectionsData = [];
        foreach ($pageSections as $pageSection) {
            $pageSectionsData[] = [

                'id'=>$pageSection->id ?? null,
                'title'=>$pageSection->title ?? null,
                'sub_title'=>$pageSection->sub_title ?? null,
                'des'=>$pageSection->des ?? null,
                'type'=>$pageSection->type ?? null,
                'active'=>$pageSection->active ?? null,
                'button_text_one'=>$pageSection->button_text_one ?? null,
                'button_style_one'=>$pageSection->button_style_one ?? null,
                'button_route_one'=>$pageSection->button_route_one ?? null,
                'button_icon_one'=>$pageSection->button_icon_one ?? null,
                'button_link_type_one'=>$pageSection->button_link_type_one ?? null,
                'button_text_two'=>$pageSection->button_text_two ?? null,
                'button_style_two'=>$pageSection->button_style_two ?? null,
                'button_route_two'=>$pageSection->button_route_two ?? null,
                'button_icon_two'=>$pageSection->button_icon_two ?? null,
                'button_link_type_two'=>$pageSection->button_link_type_two ?? null,
                'button_one_active'=>$pageSection->button_one_active ?? null,
                'button_two_active'=>$pageSection->button_two_active ?? null,

                'children' => PageSectionResource::collection($pageSection->children) ?? null,

                'image_url' => $pageSection->getFirstMediaUrl($networkSlug) ?? null,

                'order_id_pivot' =>  $pageSection->pivot->order_id ?? null,
            ];
        }
        usort($pageSectionsData, function($a, $b) {
            return ($a['order_id_pivot'] ?? 0) - ($b['order_id_pivot'] ?? 0);
        });
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'slug' => $this->slug ?? null,
            'des' => $this->des ?? null,
            'order_id' => $this->order_id ?? null,
            'active' => $this->active ?? null,
            'pageSections'=>$pageSectionsData ?? null,
            'deleted' => isset($this->deleted_at)?? null,

        ];
    }
}
