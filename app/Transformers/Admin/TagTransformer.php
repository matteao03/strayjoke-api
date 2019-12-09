<?php

namespace App\Transformers\Admin;

use App\Models\Tag;
use League\Fractal\TransformerAbstract;

class TagTransformer extends TransformerAbstract
{
    public function transform(Tag $tag)
    {
        return [
            'id' => $tag->id,
            'title' => $tag->title,
            'icon' => $tag->icon,
            'updatedTime' => (string) $tag->updated_at
        ];
    }
}
