# Laravel Testable Event Listener.

When developing using TTD pattern, 
## Installation

You can install the package via composer:

```bash
composer require digitalcloud/testable-event-listener
```

## Usage Example

Suppose we have an event fired when we create a new user, and in the normal behavior we have many listener to this event, but during testing we want some of those event to be executed and the other to be ignored.

The user model will fire the \App\Events\UserCreating Event when the Eloquent creating event fired.

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

Our EventServiceProvider look like this:

```php
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    // ...
    
    protected $listen = [
        \App\Events\UserCreating::class => [
            // ...
            \App\Listeners\UserCreating::class,
            \App\Listeners\UserCreated::class,
            // ...
        ],
    ];
    
    // ...
}

```

But when testing we need to run only one listener, \App\Listeners\UserCreating::class, and ignoring all other listener. To do this we can call the EventFaker:fake and pass it the array of fackable event with the required listener to be executed.


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

the result is:

PHPUnit 7.5.1 by Sebastian Bergmann and contributors.

"App\Listeners\UserCreating::handle"

Time: 863 ms, Memory: 14.00MB

OK (1 test, 1 assertion)

Process finished with exit code 0


