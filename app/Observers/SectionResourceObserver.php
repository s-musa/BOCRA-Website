<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class SectionResourceObserver
{
    public function saved($model): void
    {
        $this->clearRelatedCache($model);
    }
    
    public function deleted($model): void
    {
        $this->clearRelatedCache($model);
    }
    
    protected function clearRelatedCache($model): void
    {
        $cacheKey = 'section_' . strtolower(class_basename($model)) . 's';
        Cache::forget($cacheKey);
    }
}
