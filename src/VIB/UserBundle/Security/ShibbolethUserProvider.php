<?php

namespace VIB\UserBundle\Security;

use FOS\UserBundle\Security\UserProvider as BaseUserProvider;
use FOS\UserBundle\Model\UserManagerInterface;
use KULeuven\ShibbolethBundle\Security\ShibbolethUserProviderInterface;
use KULeuven\ShibbolethBundle\Security\ShibbolethUserToken; 

/**
 * Description of ShibbolethUserProvider
 *
 * @author Radoslaw Kamil Ejsmont <radoslaw@ejsmont.net>
 */
class ShibbolethUserProvider extends BaseUserProvider implements ShibbolethUserProviderInterface {
    
    public function createUser(ShibbolethUserToken $token) {
        $user = $this->userManager->createUser();
        $user->setUsername($token->getUsername());
        $user->setPlainPassword('no_passwd');
        if (null != $token->getMail()) {
            $user->setEmail($token->getMail());
        } else {
            $user->setEmail($token->getUsername() . '@kuleuven.be');
        }
        if ($token->isStudent()) {
            $user->addRole('ROLE_STUDENT');
        }
        elseif ($token->isStaff()) {
            $user->addRole('ROLE_STAFF');
        }
        else {
            $user->addRole('ROLE_GUEST');
        }
        $user->addRole('ROLE_USER');
        $user->setEnabled(true);

        $this->userManager->updateUser($user);
        return $user;
    }
}

?>
