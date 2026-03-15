# Module System

The platform includes a module architecture for future expansion.

## Overview

- **Official modules**: Published by Online PR (e.g. `online-pr/projects`, `online-pr/invoicing`)
- **Future marketplace**: Exclusive module marketplace for premium add-ons
- **Config**: `config/modules.php` – register official modules
- **Database**: `modules` table – enabled modules per install

## Module Table

| Column     | Description                          |
|-----------|--------------------------------------|
| identifier| e.g. `online-pr/projects`            |
| name      | Display name                         |
| version   | Semantic version                     |
| enabled   | Whether the module is active         |
| config    | JSON – module-specific settings      |
| source    | `official` or `marketplace` (future)  |

## Usage

```php
use App\Services\ModuleService;

// Check if module is enabled
ModuleService::enabled('online-pr/projects');

// Enable a module
ModuleService::enable('online-pr/projects', ['key' => 'value']);

// Disable
ModuleService::disable('online-pr/projects');
```

## Future: Marketplace

The architecture supports a future exclusive marketplace where:

- Modules are published by Online PR
- Agencies can browse and install modules
- Premium modules may require a license
