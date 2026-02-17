<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\{Clinic, Patient, Appointment};

class PerformanceCheck extends Command
{
    protected $signature = 'performance:check';
    protected $description = 'Check application performance metrics';

    public function handle()
    {
        $this->info('🚀 Performance Check Started');
        
        // Database performance
        $start = microtime(true);
        $clinicCount = Clinic::count();
        $dbTime = round((microtime(true) - $start) * 1000, 2);
        
        $this->table(['Metric', 'Value', 'Status'], [
            ['Database Response', $dbTime . 'ms', $dbTime < 100 ? '✅ Good' : '⚠️ Slow'],
            ['Total Clinics', $clinicCount, '📊 Info'],
            ['Cache Driver', config('cache.default'), config('cache.default') !== 'file' ? '✅ OK' : '⚠️ Consider Redis'],
            ['DB Connection', config('database.default'), '📊 Info'],
        ]);
        
        // Memory usage
        $memory = round(memory_get_usage(true) / 1024 / 1024, 2);
        $this->info("💾 Memory Usage: {$memory}MB");
        
        // Recommendations
        $this->warn('🔧 Performance Recommendations:');
        $this->line('• Enable Redis caching for production');
        $this->line('• Add database indexes on frequently queried columns');
        $this->line('• Use eager loading for relationships');
        $this->line('• Enable query caching');
        
        return 0;
    }
}
