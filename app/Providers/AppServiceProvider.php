<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'news' => \App\Models\NewsModel::class,
            'guardian_news' => \App\Models\GuardianModel::class,
            'nytimes_news' => \App\Models\NYTimesModel::class,
        ]);

    
    }
}
