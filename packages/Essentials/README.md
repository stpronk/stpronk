## Installation

- Install the package with composer
```shell
composer require stpronk\filamentphp-essentials
```

- Make sure the service provider is loaded, else add it within `bootstrap/app.php`
```php
$app->withProviders([
    \Stpronk\Essentials\EssentialsServiceProvider::class,
    
    // Other providers
])
```

- Run migrations
```shell
php artisan migrate
```

---

#### Shareables

- Add the relationship to the resource
```php
class YourResource extends Resource
{

    public static function getRelations(): array
    {
        return [
            \Stpronk\Essentials\Filament\RelationshipManagers\ShareablesRelationshipManager::class,
            
            // Other relations
        ];
    }
}
```

- Add the shareables trait to the model
```php 
class YourModel extends Model
{
    use \Stpronk\Essentials\Traits\ModelHasShareable;
} 
```

- Add global scope to model to make sure the shared items are displayed
```php
class YourModel extends Model 
{

    use ModelHasShareable;

    protected static function boot(): void
    {
        static::addGlobalScope('shareables', function (Builder $builder) {
            static::searchableGlobalScopeBuilder($builder);
            
            // Other global scopes
        }
    }
}
```
