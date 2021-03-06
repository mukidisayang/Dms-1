<?php
/**
 * github.com/buse974/Dms (https://github.com/buse974/Dms).
 *
 * CodingFactory.php
 */
namespace Dms\ServiceFactory;

use Dms\Coding\CodingInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Interop\Container\ContainerInterface;

/**
 * Class Factory encode/decode.
 */
class CodingFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a Mapper with name.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     *
     * @return bool
     *
     * {@inheritdoc}
     *
     * @see \Zend\ServiceManager\Factory\AbstractFactoryInterface::canCreate()
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        return substr($requestedName, -6) === 'Coding';
    }

    /**
     * Create Mapper with name.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * {@inheritdoc}
     *
     * @see \Zend\ServiceManager\Factory\FactoryInterface::__invoke()
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $factory = $container->get(ucfirst($requestedName));
        if (!$factory instanceof CodingInterface) {
            throw new \Exception('not type CodingInterface');
        }

        return $factory;
    }
}
