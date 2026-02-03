<?php

namespace App\Jobs;

use App\Models\Section;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CacheSectionStructureByLocale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Section $section;

    protected string $locale;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Section $section, string $locale)
    {
        $this->section = $section;
        $this->locale = $locale;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (! $this->section->cacheable()) {
            return;
        }

        $key = $this->section->structure_cache_key($this->locale);
        if (Cache::has($key)) {
            return;
        }

        Cache::put($key, $this->section->getFlexibleStructure()->setLocale($this->locale)->cacheableData());
    }

    public function retryUntil()
    {
        return now()->addMinute();
    }
}
