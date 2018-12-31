# Laravel Testable Event Listener.

When developing using TTD pattern, 
## Installation

You can install the package via composer:

```bash
composer require digitalcloud/testable-event-listener
```

## Usage

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\UserCreating::class => [
            \App\Listeners\UserCreating::class,
            \App\Listeners\UserCreated::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}

```

```php
<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $dispatchesEvents = [
        'creating' => \App\Events\UserCreating::class
    ];
}

```

```php

<?php

namespace Tests\Unit;

use App\User;
use DigitalCloud\TestableEventListener\EventFaker;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        EventFaker::fake([
            \App\Events\UserCreating::class => [
                \App\Listeners\UserCreating::class
            ],
        ]);
        $user = factory(User::class)->create();
        EventFaker::assertDispatched(\App\Events\UserCreating::class);
    }
}

```

PHPUnit 7.5.1 by Sebastian Bergmann and contributors.

"App\Listeners\UserCreating::handle"

Time: 863 ms, Memory: 14.00MB

OK (1 test, 1 assertion)

Process finished with exit code 0


