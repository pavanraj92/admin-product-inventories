<?php

namespace admin\product_inventories;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductInventoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load routes, views, migrations from the package  
        $this->loadViewsFrom([
            base_path('Modules/Inventories/resources/views'), // Published module views first
            resource_path('views/admin/inventory'), // Published views second
            __DIR__ . '/../resources/views'      // Package views as fallback
        ], 'inventory');

        $this->mergeConfigFrom(__DIR__ . '/../config/inventory.php', 'inventory.constants');

        // Also register module views with a specific namespace for explicit usage
        if (is_dir(base_path('Modules/Inventories/resources/views'))) {
            $this->loadViewsFrom(base_path('Modules/Inventories/resources/views'), 'inventories-module');
        }
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Also load migrations from published module if they exist
        if (is_dir(base_path('Modules/Inventories/database/migrations'))) {
            $this->loadMigrationsFrom(base_path('Modules/Inventories/database/migrations'));
        }

        // Also merge config from published module if it exists
        if (file_exists(base_path('Modules/Inventories/config/inventories.php'))) {
            $this->mergeConfigFrom(base_path('Modules/Inventories/config/inventories.php'), 'inventory.constants');
        }

        // Only publish automatically during package installation, not on every request
        // Use 'php artisan products:publish' command for manual publishing
        // $this->publishWithNamespaceTransformation();

        // Standard publishing for non-PHP files
        $this->publishes([
            __DIR__ . '/../config/' => base_path('Modules/Inventories/config/'),
            __DIR__ . '/../database/migrations' => base_path('Modules/Inventories/database/migrations'),
            __DIR__ . '/../resources/views' => base_path('Modules/Inventories/resources/views/'),
        ], 'inventory');

    }


    public function register()
    {
        // Register the publish command
        if ($this->app->runningInConsole()) {
            $this->commands([
                \admin\product_inventories\Console\Commands\PublishProductInventoryModuleCommand::class,
                \admin\product_inventories\Console\Commands\CheckModuleStatusCommand::class,
                \admin\product_inventories\Console\Commands\DebugProductInventoryCommand::class,
            ]);
        }
    }

    /**
     * Publish files with namespace transformation
     */
    protected function publishWithNamespaceTransformation()
    {
        // Define the files that need namespace transformation
        $filesWithNamespaces = [
            // Models
            __DIR__ . '/../src/Models/ProductInventory.php' => base_path('Modules/Inventories/app/Models/ProductInventory.php'),
        ];

        foreach ($filesWithNamespaces as $source => $destination) {
            if (File::exists($source)) {
                // Create destination directory if it doesn't exist
                File::ensureDirectoryExists(dirname($destination));

                // Read the source file
                $content = File::get($source);

                // Transform namespaces based on file type
                $content = $this->transformNamespaces($content, $source);

                // Write the transformed content to destination
                File::put($destination, $content);
            }
        }
    }

    /**
     * Transform namespaces in PHP files
     */
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
        if (str_contains($sourceFile, 'Models')) {
            $content = $this->transformModelNamespaces($content);
        }

        return $content;
    }

    /**
     * Transform controller-specific namespaces
     */
    protected function transformControllerNamespaces($content)
    {
        // Update use statements for models and requests
        $content = str_replace(
            'use admin\\product_inventories\\Models\\ProductInventory;',
            'use Modules\\Inventories\\app\\Models\\ProductInventory;',
            $content
        );

        return $content;
    }

    /**
     * Transform model-specific namespaces
     */
    protected function transformModelNamespaces($content)
    {
        // Any model-specific transformations
        $content = str_replace(
            'use admin\\products\\Models\\Product;',
            'use Modules\\Products\\app\\Models\\Product;',
            $content
        );
        return $content;
    }

    /**
     * Transform request-specific namespaces
     */
    protected function transformRequestNamespaces($content)
    {
        // Any request-specific transformations
        return $content;
    }

    /**
     * Transform route-specific namespaces
     */
    protected function transformRouteNamespaces($content)
    {
        // Update controller references in routes
        return $content;
    }
}