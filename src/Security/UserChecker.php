<?php


namespace App\Security;


use App\Entity\Participant;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof Participant) {
            return;
        }

        if ($user->getActif()===false){
            throw new CustomUserMessageAccountStatusException('Votre compte a été rendu inactif, pour plus d\'informations, veuillez contacter un administrateur');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}