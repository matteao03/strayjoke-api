<?php

namespace App\Transformers;

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
            'value' => (int) $template->value,
            'notBefore' => (string) $template->not_before,
            'notAfter' => (string) $template->not_after
        ];
    }
}
