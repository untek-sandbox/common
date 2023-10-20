<?php

namespace Untek\User\Authentication\Domain\Services;

use Untek\User\Authentication\Domain\Forms\AuthImitationForm;
use Untek\User\Authentication\Domain\Interfaces\Services\ImitationAuthServiceInterface;
use Symfony\Component\Validator\Constraints\Email;
use Untek\Model\Validator\Helpers\UnprocessableHelper;
use Untek\Model\Validator\Helpers\ValidationHelper;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Component\I18Next\Facades\I18Next;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;

class ImitationAuthAuthService extends AuthService implements ImitationAuthServiceInterface
{

    public function tokenByImitation(AuthImitationForm $loginForm): TokenValueEntity
    {
        $userEntity = $this->getIdentityByForm($loginForm);

        $this->logger->info('auth tokenByForm');
        //$authEvent = new AuthEvent($loginForm);
        $tokenEntity = $this->tokenService->getTokenByIdentity($userEntity);
        $tokenEntity->setIdentity($userEntity);
        $this->em->loadEntityRelations($userEntity, ['assignments']);
        return $tokenEntity;
    }

    private function getIdentityByForm(AuthImitationForm $loginForm): IdentityEntityInterface
    {
        ValidationHelper::validateEntity($loginForm);
//        $authEvent = new AuthEvent($loginForm);
//        $this->getEventDispatcher()->dispatch($authEvent, AuthEventEnum::BEFORE_AUTH);
        try {
            $errorCollection = ValidationHelper::validateValue($loginForm->getLogin(), [new Email()]);
            $isEmail = $errorCollection->count() <= 0;

            if ($isEmail) {
                $credentialEntity = $this->credentialRepository->findOneByCredential($loginForm->getLogin(), 'email');
            } else {
                $credentialEntity = $this->credentialRepository->findOneByCredential($loginForm->getLogin(), 'login');
            }
        } catch (NotFoundException $e) {
            $message = I18Next::t('authentication', 'auth.user_not_found');
            $this->logger->warning('auth authenticationByForm');
            UnprocessableHelper::throwItem('login', $message);
        }

        $userEntity = $this->findOneIdentityById($credentialEntity->getIdentityId());
//        $userEntity = $this->identityRepository->findOneById($credentialEntity->getIdentityId());
        return $userEntity;
    }
}
