<?php

namespace App\Traits;

use App\Models\MetaSeo;

trait HasMetaSeo
{
    public function metaSeo()
    {
        return $this->morphOne(MetaSeo::class, 'seo_able');
    }
}
