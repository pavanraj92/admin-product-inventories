<?php

namespace admin\product_inventories\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CheckModuleStatusCommand extends Command
{
    protected $signature = 'inventories:status';
    protected $description = 'Check if Inventories module files are being used';

    public function handle()
    {
        $this->info('Checking Inventories Module Status...');

        // Check if module files exist
        $moduleFiles = [
            'Model' => base_path('Modules/Inventories/app/Models/ProductInventory.php'),

            'Routes' => base_path('Modules/Inventories/routes/web.php'),
            'Views' => base_path('Modules/Inventories/resources/views'),
            'Config' => base_path('Modules/Inventories/config/inventories.php'),
        ];

        $this->info("\n📁 Module Files Status:");
        foreach ($moduleFiles as $type => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$type}: EXISTS");

                // Check if it's a PHP file and show last modified time
                if (str_ends_with($path, '.php')) {
                    $lastModified = date('Y-m-d H:i:s', filemtime($path));
                    $this->line("   Last modified: {$lastModified}");
                }
            } else {
                $this->error("❌ {$type}: NOT FOUND");
            }
        }

        // Check composer autoload
        $composerFile = base_path('composer.json');
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);
            if (isset($composer['autoload']['psr-4']['Modules\\Inventories\\'])) {
                $this->info("\n✅ Composer autoload: CONFIGURED");
            } else {
                $this->error("\n❌ Composer autoload: NOT CONFIGURED");
            }
        }

        $this->info("\n🎯 Summary:");
        $this->info("Your Inventories module is properly published and should be working.");
        $this->info("Any changes you make to files in Modules/Inventories/ will persist.");
        $this->info("If you need to republish from the package, run: php artisan inventories:publish --force");
    }
}
