<?php

namespace DigitalCloud\TestableEventListener;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Testing\Fakes\EventFake;

/**
 * @method static void listen(string|array $events, $listener)
 * @method static bool hasListeners(string $eventName)
 * @method static void subscribe(object|string $subscriber)
 * @method static array|null until(string|object $event, $payload = [])
 * @method static array|null dispatch(string|object $event, $payload = [], bool $halt = false)
 * @method static void push(string $event, array $payload = [])
 * @method static void flush(string $event)
 * @method static void forget(string $event)
 * @method static void forgetPushed()
 *
 * @see \Illuminate\Events\Dispatcher
 */
class EventFaker extends Facade
{
    /**
     * Replace the bound instance with a fake.
     *
     * @param  array|string  $eventsToFake
     * @return void
     */
    public static function fake($eventsToFake = [])
    {
        static::swap($fake = new FakeEventDispatcher(static::getFacadeRoot(), $eventsToFake));

        Model::setEventDispatcher($fake);

        foreach ($eventsToFake as $event => $listeners) {
            Event::forget($event);
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * Replace the bound instance with a fake during the given callable's execution.
     *
     * @param  callable  $callable
     * @param  array  $eventsToFake
     * @return callable
     */
    public static function fakeFor(callable $callable, array $eventsToFake = [])
    {
        $originalDispatcher = static::getFacadeRoot();

        static::fake($eventsToFake);

        return tap($callable(), function () use ($originalDispatcher) {
            static::swap($originalDispatcher);

            Model::setEventDispatcher($originalDispatcher);
        });
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'events';
    }
}
