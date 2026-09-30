<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerModuleViews();
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }

    /**
     * 自动注册 app/Modules 目录下各模块的 Blade 视图命名空间
     */
    protected function registerModuleViews(): void
    {
        $moduleDirs = glob(app_path('Modules/*'), GLOB_ONLYDIR);
        if (! empty($moduleDirs)) {
            foreach ($moduleDirs as $dir) {
                $viewsPath = $dir.'/Views';
                if (is_dir($viewsPath)) {
                    $namespace = strtolower(basename($dir));
                    $this->loadViewsFrom($viewsPath, $namespace);
                }
            }
        }
    }
}
