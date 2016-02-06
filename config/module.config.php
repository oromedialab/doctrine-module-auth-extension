<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
return array(
	'doctrine_factories' => array(
        'authenticationadapter' => 'Oml\DoctrineModuleAuthExtension\Factory\AdapterFactory'
    ),
	'service_manager' => array(
        'factories' => array(
            'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                return $serviceManager->get('doctrine.authenticationservice.orm_default');
            }
        )
    )
);
