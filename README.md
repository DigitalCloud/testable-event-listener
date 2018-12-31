# Laravel Testable Event Listener.

When developing using TTD pattern, 
## Installation

You can install the package via composer:

```bash
composer require digitalcloud/testable-event-listener
```

## Usage

<script src="https://gist.github.com/devmtm/5770c82647fda3f182b1f00b2e3e92b5.js"></script>

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


