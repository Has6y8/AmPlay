<?php

namespace App\Console\Commands;

use App\Services\AniDBService;
use Illuminate\Console\Command;

class RefreshAnimeCache extends Command
{
    protected $signature = 'anime:refresh {animeId?} {--all}';
    protected $description = 'Refresh cache untuk anime tertentu atau semua anime yang di-cache';

    protected AniDBService $anidb;

    public function __construct(AniDBService $anidb)
    {
        parent::__construct();
        $this->anidb = $anidb;
    }

    public function handle()
    {
        $animeId = $this->argument('animeId');
        $all = $this->option('all');

        if ($all) {
            $this->warn('Fitur refresh all belum diimplementasikan. Gunakan ID spesifik.');
            return 1;
        }

        if (!$animeId) {
            $this->error('Mohon berikan anime ID atau gunakan --all');
            return 1;
        }

        $this->info('Refreshing cache untuk anime ID: ' . $animeId);
        $this->anidb->clearAnimeCache($animeId);

        $this->info('Pre-fetching episodes...');
        $episodes = $this->anidb->getEpisodes($animeId, 1);
        $this->info('Done! ' . $episodes->total() . ' episode tersedia.');

        return 0;
    }
}