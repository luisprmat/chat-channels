<?php

namespace App\Providers;

use App\Pulse\Recorders\ReverbConnections;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Pulse\Recorders\Servers;
use Laravel\Reverb\Pulse\Recorders\ReverbConnections as BaseReverbConnections;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BaseReverbConnections::class, ReverbConnections::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configurePulseServerDetection();
    }

    /**
     * Detect CPU and memory usage on Windows without the deprecated "wmic" utility.
     */
    protected function configurePulseServerDetection(): void
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            return;
        }

        Servers::detectCpuUsing(fn (): int => (int) trim((string) shell_exec(
            'powershell -NoProfile -Command "(Get-CimInstance Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average"'
        )));

        Servers::detectMemoryUsing(function (): array {
            [$totalKilobytes, $freeKilobytes] = array_map('intval', preg_split('/\s+/', trim((string) shell_exec(
                'powershell -NoProfile -Command "$os = Get-CimInstance Win32_OperatingSystem; $os.TotalVisibleMemorySize; $os.FreePhysicalMemory"'
            ))) + [0, 0]);

            return [
                'total' => intdiv($totalKilobytes, 1024),
                'used' => intdiv($totalKilobytes - $freeKilobytes, 1024),
            ];
        });
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
}
