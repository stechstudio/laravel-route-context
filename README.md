# Route context in Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/stechstudio/laravel-route-context.svg?style=flat-square)](https://packagist.org/packages/stechstudio/laravel-route-context)

This is a super small package that enables you to provide additional context to your routes. Any context variables you specify will be treated as route parameters.

## Installation

You know the drill...

```bash
composer require stechstudio/laravel-route-context
```

## Usage

The idea is that sometimes you want to reuse a controller method or Livewire fullpage component, while providing additional context at the routing layer.

Imagine you need to list support tickets, and you have a controller and view that handles this. You have multiple endpoints where tickets might be displayed in a slightly different manner.

With this package you can specify additional context right alongside your routes like this:

```php
Route::get('tickets', [TicketController::class, 'index']);
Route::get('tickets/archived', [TicketController::class, 'index'])->with([
    'archived' => true
]);
Route::get('tickets/mine', [TicketController::class, 'index'])->with([
    'user' => fn() => auth()->user()
]);
Route::get('tickets/{user}', [TicketController::class, 'index']);
```

Now in your `TicketController` you can inject your context variables, just as if they had been route parameters:

```php
public function index(User $user, $archived = false) {
    $tickets = Tickets::query()
        ->when($user->exists, fn($q) => $q->where('user_id', $user->id))
        ->when(!$archived, fn($q) => $q->whereNull('archived_at'))
        ->paginate();
}
```

Notice that context values can be callbacks, if you need it evaluated after your app has bootstrapped, session is started, auth is available, etc.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
