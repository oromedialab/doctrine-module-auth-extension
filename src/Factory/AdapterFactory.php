<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\DoctrineModuleAuthExtension\Factory;

use DoctrineModule\Service\Authentication\AdapterFactory as BaseAdapterFactory;
use Oml\DoctrineModuleAuthExtension\Adapter\ObjectRepository;
use Zend\ServiceManager\ServiceLocatorInterface;

class AdapterFactory extends BaseAdapterFactory
{
    /**
     * {@inheritDoc}
     *
     * @return Oml\Zf2User\Adapter\ObjectRepository
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $options \DoctrineModule\Options\Authentication */
        $options = $this->getOptions($serviceLocator, 'authentication');
        if (is_string($objectManager = $options->getObjectManager())) {
            $options->setObjectManager($serviceLocator->get($objectManager));
        }
        return new ObjectRepository($options);
    }
}
