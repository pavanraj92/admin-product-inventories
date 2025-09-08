<?php

namespace admin\product_inventories\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishProductInventoryModuleCommand extends Command
{
    protected $signature = 'inventories:publish {--force : Force overwrite existing files}';
    protected $description = 'Publish Inventories module files with proper namespace transformation';

    public function handle()
    {
        $this->info('Publishing Inventories module files...');

        // Check if module directory exists
        $moduleDir = base_path('Modules/Inventories');
        if (!File::exists($moduleDir)) {
            File::makeDirectory($moduleDir, 0755, true);
        }

        // Publish with namespace transformation
        $this->publishWithNamespaceTransformation();
        
        // Publish other files
        $this->call('vendor:publish', [
            '--tag' => 'inventory',
            '--force' => $this->option('force')
        ]);

        // Update composer autoload
        $this->updateComposerAutoload();

        $this->info('Inventories module published successfully!');
        $this->info('Please run: composer dump-autoload');
    }

    protected function publishWithNamespaceTransformation()
    {
        $basePath = dirname(dirname(__DIR__)); // Go up to packages/admin/inventories/src

        $filesWithNamespaces = [
            // Models
            $basePath . '/Models/ProductInventory.php' => base_path('Modules/Inventories/app/Models/ProductInventory.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname($destination));
                
                $content = File::get($source);
                $content = $this->transformNamespaces($content, $source);
                
                File::put($destination, $content);
                $this->info("Published: " . basename($destination));
            } else {
                $this->warn("Source file not found: " . $source);
            }
        }
    }

    protected function transformNamespaces($content, $sourceFile)
    {
        // Define namespace mappings
        $namespaceTransforms = [
            // Main namespace transformations
            'namespace admin\\product_inventories\\Models;' => 'namespace Modules\\Inventories\\app\\Models;',

            // Use statements transformations
            'use admin\\product_inventories\\Models\\' => 'use Modules\\Inventories\\app\\Models\\',
        ];

        // Apply transformations
        foreach ($namespaceTransforms as $search => $replace) {
            $content = str_replace($search, $replace, $content);
        }

        // Handle specific file types
        if (str_contains($sourceFile, 'Controllers')) {
            $content = str_replace(
                'use admin\\product_inventories\\Models\\ProductInventory;',
                'use Modules\\Inventories\\app\\Models\\ProductInventory;',
                $content
            );
        } elseif (str_contains($sourceFile, 'Models')) {
            $content = str_replace(
                'use admin\\products\\Models\\Product;',
                'use Modules\\Products\\app\\Models\\Product;',
                $content
            );
        }

        return $content;
    }

    protected function updateComposerAutoload()
    {
        $composerFile = base_path('composer.json');
        $composer = json_decode(File::get($composerFile), true);

        // Add module namespace to autoload
        if (!isset($composer['autoload']['psr-4']['Modules\\Inventories\\'])) {
            $composer['autoload']['psr-4']['Modules\\Inventories\\'] = 'Modules/Inventories/app/';

            File::put($composerFile, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->info('Updated composer.json autoload');
        }
    }
}