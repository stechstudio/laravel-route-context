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

Imagine you need to list support tickets, and you have a controller and view that handles this. You have multiple endpoints where tickets might be displayed in a slightly different manner. You might, say, have a 'tickets/my' route that only lists your own assigned tickets.

With this package you can specify additional context right alongside your routes like this:

```php
Route::get('tickets', [TicketController::class, 'index'])->with(['user' => null]);
Route::get('tickets/my', [TicketController::class, 'index'])->with(['user' => fn() => auth()->user()]);
Route::get('tickets/{user}', [TicketController::class, 'index']);
```

Now in your `TicketController` you can inject the `$user` variable:

```php
public function index(User $user) {
    $query = Tickets::query()
        ->when($user->exists, fn($q) => $q->where('user', $user));
}
```

In other words, you can re-use controllers/views or even Livewire fullpage components where you just need a slight change of context, simply by providing this context in your routes file.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
