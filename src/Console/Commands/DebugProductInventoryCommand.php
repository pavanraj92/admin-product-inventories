<?php

namespace admin\product_inventories\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DebugProductInventoryCommand extends Command
{
    protected $signature = 'inventories:debug';
    protected $description = 'Debug Inventories module routing and view resolution';

    public function handle()
    {
        $this->info('🔍 Debugging Inventories Module...');
        
        // Check view loading priority
        $this->info("\n👀 View Loading Priority:");
        $viewPaths = [
            'Module views' => base_path('Modules/Inventories/resources/views'),
            'Published views' => resource_path('views/admin/inventories'),
            'Package views' => base_path('packages/admin/product_inventories/resources/views'),
        ];
        
        foreach ($viewPaths as $name => $path) {
            if (File::exists($path)) {
                $this->info("✅ {$name}: {$path}");
            } else {
                $this->warn("⚠️  {$name}: NOT FOUND - {$path}");
            }
        }
        
    }
}
