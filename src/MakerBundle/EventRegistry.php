<?php

namespace MakerBundle;

use Eccube\Event\TemplateEvent;
use Symfony\Bundle\MakerBundle\EventRegistry as BaseEventRegistry;
use Symfony\Component\EventDispatcher\Event as LegacyEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @internal
 */
class EventRegistry extends BaseEventRegistry
{
    private static $newEventsMap = [
        'kernel.exception' => ExceptionEvent::class,
        'kernel.request' => RequestEvent::class,
        'kernel.response' => ResponseEvent::class,
        'kernel.view' => ViewEvent::class,
        'kernel.controller_arguments' => ControllerArgumentsEvent::class,
        'kernel.controller' => ControllerEvent::class,
        'kernel.terminate' => TerminateEvent::class,
    ];

    private static $eventsMap = [
        'Product/detail.twig' => TemplateEvent::class,
        'Product/list.twig' => TemplateEvent::class,
        'console.command' => ConsoleCommandEvent::class,
        'console.terminate' => ConsoleTerminateEvent::class,
        'console.error' => ConsoleErrorEvent::class,
        'kernel.request' => GetResponseEvent::class,
        'kernel.exception' => GetResponseForExceptionEvent::class,
        'kernel.view' => GetResponseForControllerResultEvent::class,
        'kernel.controller' => FilterControllerEvent::class,
        'kernel.controller_arguments' => FilterControllerArgumentsEvent::class,
        'kernel.response' => FilterResponseEvent::class,
        'kernel.terminate' => PostResponseEvent::class,
        'kernel.finish_request' => FinishRequestEvent::class,
        'security.authentication.success' => AuthenticationEvent::class,
        'security.authentication.failure' => AuthenticationFailureEvent::class,
        'security.interactive_login' => InteractiveLoginEvent::class,
        'security.switch_user' => SwitchUserEvent::class,
    ];

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        // Loop through the new event classes
        foreach (self::$newEventsMap as $eventName => $newEventClass) {
            //Check if the new event classes exist, if so replace the old one with the new.
            if (isset(self::$eventsMap[$eventName]) && class_exists($newEventClass)) {
                self::$eventsMap[$eventName] = $newEventClass;
            }
        }
    }

    /**
     * Returns all known event names in the system.
     */
    public function getAllActiveEvents(): array
    {
        $activeEvents = [];
        foreach (self::$eventsMap as $eventName => $eventClass) {
            if (!class_exists($eventClass)) {
                continue;
            }

            $activeEvents[] = $eventName;
        }

        $listeners = $this->eventDispatcher->getListeners();

        // Check if these listeners are part of the new events.
        foreach (array_keys($listeners) as $listenerKey) {
            if (isset(self::$newEventsMap[$listenerKey])) {
                unset($listeners[$listenerKey]);
            }

            if (!isset(self::$eventsMap[$listenerKey])) {
                self::$eventsMap[$listenerKey] = $this->getEventClassName($listenerKey);
            }
        }

        $activeEvents = array_unique(array_merge($activeEvents, array_keys($listeners)));

        asort($activeEvents);

        return $activeEvents;
    }

    /**
     * Attempts to get the event class for a given event.
     */
    public function getEventClassName(string $event)
    {
        // if the event is already a class name, use it
        if (class_exists($event)) {
            return $event;
        }

        if (isset(self::$eventsMap[$event])) {
            return self::$eventsMap[$event];
        }

        $listeners = $this->eventDispatcher->getListeners($event);
        if (empty($listeners)) {
            return null;
        }

        foreach ($listeners as $listener) {
            if (!\is_array($listener) || 2 !== \count($listener)) {
                continue;
            }

            $reflectionMethod = new \ReflectionMethod($listener[0], $listener[1]);
            $args = $reflectionMethod->getParameters();
            if (!$args) {
                continue;
            }

            if (null !== $type = $args[0]->getType()) {
                $type = $type instanceof \ReflectionNamedType ? $type->getName() : $type->__toString();

                if (LegacyEvent::class === $type && class_exists(Event::class)) {
                    return Event::class;
                }

                // ignore an "object" type-hint
                if ('object' === $type) {
                    continue;
                }

                return $type;
            }
        }

        return null;
    }

    public function listActiveEvents(array $events)
    {
        foreach ($events as $key => $event) {
            $events[$key] = sprintf('%s (<fg=yellow>%s</>)', $event, self::$eventsMap[$event]);
        }

        return $events;
    }

}
