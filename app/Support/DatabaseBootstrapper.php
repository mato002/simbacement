<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class DatabaseBootstrapper
{
    public static function ensureReady(): void
    {
        if (app()->runningInConsole() || app()->environment('testing')) {
            return;
        }

        if (self::signature() === self::readSentinel()) {
            return;
        }

        try {
            self::run();
        } catch (Throwable $e) {
            report($e);
        }
    }

    public static function run(bool $forceSeed = false): string
    {
        $lockPath = storage_path('framework/migrations.lock');
        $lock = fopen($lockPath, 'c');

        if ($lock === false) {
            throw new RuntimeException('Unable to create a migration lock file. Check that storage/framework is writable.');
        }

        if (! flock($lock, LOCK_EX)) {
            fclose($lock);
            throw new RuntimeException('Unable to acquire the migration lock.');
        }

        $output = '';

        try {
            Artisan::call('migrate', ['--force' => true]);
            $output .= Artisan::output();

            $shouldSeed = $forceSeed
                || (Schema::hasTable('users') && User::query()->doesntExist());

            if ($shouldSeed) {
                Artisan::call('db:seed', ['--force' => true]);
                $output .= Artisan::output();
            }

            if (! file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
                $output .= Artisan::output();
            }

            file_put_contents(storage_path('framework/migrations.complete'), self::signature());
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }

        return trim($output) === '' ? 'Migrations are already up to date.' : $output;
    }

    private static function signature(): string
    {
        $files = glob(database_path('migrations/*.php')) ?: [];
        sort($files);

        return hash('sha256', implode('|', array_map('basename', $files)));
    }

    private static function readSentinel(): string
    {
        $path = storage_path('framework/migrations.complete');

        return is_file($path) ? trim((string) file_get_contents($path)) : '';
    }
}
