<?php
/**
 * github.com/buse974/Dms (https://github.com/buse974/Dms).
 *
 * AbstractStorage.php
 */
namespace Dms\Storage;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

/**
 * Class AbstractStorage.
 */
abstract class AbstractStorage implements EventManagerAwareInterface, StorageInterface
{
    /**
     * Gestionnaire Event.
     *
     * @var EventManagerInterface
     */
    protected $events;

    /**
     * Storage Option.
     *
     * @var StorageOption
     */
    protected $options;

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = new StorageOption($options);
    }

    /**
     * Inject an EventManager instance.
     *
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;

        return $this;
    }

    /**
     * (non-PHPdoc).
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }
}
