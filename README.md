# Product Inventories

Provides product inventory management, including tracking stock levels, movements, and related financial activities.

## Features

- Add, edit and delete inventories columns in products form

## Requirements

- PHP >=8.2
- Laravel Framework >= 12.x

## Installation

### 1. Add Git Repository to `composer.json`

```json
"repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/pavanraj92/admin-product-inventories.git"
        }
]
```

### 2. Require the package via Composer
    ```bash
    composer require admin/product_inventories:@dev
    ```

### 3. Publish assets
    ```bash
    php artisan inventories:publish --force
    ```
---


## Usage

Provides product inventory management, including tracking stock levels, movements, and related financial activities.

---

## Protecting Admin Routes

Protect your routes using the provided middleware:

```php
Route::middleware(['web','admin.auth'])->group(function () {
    // products routes here
});
```

## License

This package is open-sourced software licensed under the MIT license.
