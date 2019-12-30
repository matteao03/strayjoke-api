<?php

namespace App\Transformers\Admin;

use App\Models\CouponTemplate;
use League\Fractal\TransformerAbstract;

class CouponTemplateTransformer extends TransformerAbstract
{
    public function transform(CouponTemplate $template)
    {
        return [
            'id' => $template->id,
            'name' => $template->name,
            'type' => $template->type,
            'typeText' => CouponTemplate::$typeMap[$template->type],
            'value' => $template->value,
            'notBefore' => $template->not_before,
            'minAfter' => $template->not_after
        ];
    }
}
