<?php

declare(strict_types=1);

namespace App\Jobs\News;

use App\Actions\AggregateNewsAction;
use App\Enums\SourceCode;
use App\Models\Source;
use App\Services\Normalizers\GuardianNormalizer;
use App\Services\Normalizers\NewsApiNormalizer;
use App\Services\Normalizers\NYTimesNormalizer;
use App\Services\Providers\GuardianProvider;
use App\Services\Providers\NewsApiProvider;
use App\Services\Providers\NYTimesProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param AggregateNewsAction $aggregate
     * @param NewsApiNormalizer $newsNormalizer
     * @param GuardianNormalizer $guardianNormalizer
     * @param NYTimesNormalizer $NYTimesNormalizer
     *
     * @return void
     * @throws \Throwable
     */
    public function handle(
        AggregateNewsAction $aggregate,
        NewsApiNormalizer $newsNormalizer,
        GuardianNormalizer $guardianNormalizer,
        NYTimesNormalizer $NYTimesNormalizer,
    ): void {
        Log::channel('news')->info('SyncNewsJob started');

        try {
            $sources = Source::whereIn('code', [
                SourceCode::NEWSAPI->value,
                SourceCode::GUARDIAN->value,
                SourceCode::NYTIMES->value,
            ])->get();

            foreach ($sources as $source) {
                Log::channel('news')->info("SyncNewsJob: Processing {$source->code}");

                $provider = match ($source->code) {
                    SourceCode::NEWSAPI->value   => new NewsApiProvider($source, $newsNormalizer),
                    SourceCode::GUARDIAN->value  => new GuardianProvider($source, $guardianNormalizer),
                    SourceCode::NYTIMES->value  => new NYTimesProvider($source, $NYTimesNormalizer),
                    default => null,
                };

                if (! $provider) {
                    Log::channel('news')->warning("Provider not implemented for {$source->code}");
                    continue;
                }

                $aggregate->handle($provider);
            }

            Log::channel('news')->info('SyncNewsJob finished');
        } catch (\Throwable $e) {
            Log::channel('news')->error('SyncNewsJob failed: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}
