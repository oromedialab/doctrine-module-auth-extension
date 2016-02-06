<?php
/**
 * @author Ibrahim Azhar <azhar@iarmar.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Oml\DoctrineModuleAuthExtension\Adapter;
 
use DoctrineModule\Authentication\Adapter\ObjectRepository as BaseObjectRepository;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\Authentication\Adapter\Exception;

class ObjectRepository extends BaseObjectRepository
{
    /**
     * {@inheritDoc}
     */
    public function authenticate()
    {
        $this->setup();
        $options  = $this->options;
        $em = $options->getObjectRepository();
        $identity = $em->findOneBy(array($options->getIdentityProperty() => $this->identity));

        if (!$identity) {
            $this->authenticationResultInfo['code'] = AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticationResultInfo['messages'][] = 'A record with the supplied identity could not be found.';
            return $this->createAuthenticationResult();
        }

        $credentialCallable = $this->options->getCredentialCallable();
        $callableResult = $credentialCallable($identity, $this->credential);

        if(!is_array($callableResult)) {
            throw new Exception\UnexpectedValueException(
                sprintf(
                    'credential_callable must return data type "array", "%s" given',
                    gettype($callableResult)
                )
            );
        }
        if (!array_key_exists('code', $callableResult) || !array_key_exists('messages', $callableResult)) {
            throw new Exception\UnexpectedValueException(
                sprintf(
                    'credential_callable requires to return an array[code] & array[message]',
                    gettype($callableResult)
                )
            );
        }
        $this->authenticationResultInfo['code'] = $callableResult['code'];
        $this->authenticationResultInfo['messages']  = $callableResult['messages'];
        if (AuthenticationResult::SUCCESS === $callableResult['code']) {
            $this->authenticationResultInfo['identity'] = $identity;
        }
        return $this->createAuthenticationResult();
    }
}
