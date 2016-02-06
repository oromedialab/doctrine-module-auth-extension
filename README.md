Authentication Extension for DoctrineModule
=============
Developed and Maintained by Ibrahim Azhar Armar   

This module is an extension of [DoctrineModule Authentication Extension](https://github.com/doctrine/DoctrineModule/blob/master/docs/authentication.md)

Installation
------------

#### Install using composer
```
composer require oromedialab/doctrine-module-auth-extension
```

#### Install using GIT clone
```
git clone https://github.com/oromedialab/doctrine-module-auth-extension.git
```

#### Enable Zf2 Module
Enable the module by adding `Oml\DoctrineModuleAuthExtension` in your `config/application.config.php` file.

#### Features
  - Specify multiple condition for authentication using closure in the config file
  - Specify error messages per authentication result

#### Config
Authentication Extension from DoctrineModule only allows you to specify boolean value in the callback parameter of the config file, this is limiting if we want to have multiple check with different error messages, this extension solves this by allowing to specify multiple conditional check with different error messages for each result.

```php
return array(
	'doctrine' => array(
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'User\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credential_callable' => function(Entity\User $user, $password) {

                    function createAuthenticationResult($code, $message) {
                        $result = array();
                        $result['code'] = $code;
                        $result['messages'][] = $message;
                        return $result;
                    }

                    // User account is disabled
                    if (!$user->getEnabled()) {
                        return createAuthenticationResult(
                            \Zend\Authentication\Result::FAILURE_IDENTITY_AMBIGUOUS,
                            'User account is disabled, please contact administrator'
                        );
                    }

                    // Successfull authentication
                    return createAuthenticationResult(
                        \Zend\Authentication\Result::SUCCESS,
                        'Authenticated user successfully'
                    );
                }
            )
        )
    )
);
```