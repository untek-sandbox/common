<?php

namespace Untek\User\Authentication\Domain\Services;

use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\User\Authentication\Domain\Entities\TokenEntity;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;
use Untek\User\Authentication\Domain\Interfaces\AuthorizationTokenGeneratorInterface;
use Untek\User\Authentication\Domain\Interfaces\Repositories\TokenRepositoryInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;

class BearerTokenService implements TokenServiceInterface
{

    private $tokenRepository;
    private $authorizationTokenGenerator;

    public function __construct(
        TokenRepositoryInterface $tokenRepository,
        AuthorizationTokenGeneratorInterface $authorizationTokenGenerator
    ) {
        $this->tokenRepository = $tokenRepository;
        $this->authorizationTokenGenerator = $authorizationTokenGenerator;
    }

    public function getTokenByIdentity(IdentityEntityInterface $identityEntity): TokenValueEntity
    {
        $token = $this->authorizationTokenGenerator->generateToken();

        try {
            $tokenEntity = $this->tokenRepository->findOneByValue($token, 'bearer');
        } catch (NotFoundException $exception) {
            $tokenEntity = new TokenEntity();
            $tokenEntity->setIdentityId($identityEntity->getId());
            $tokenEntity->setType('bearer');
            $tokenEntity->setValue($token);
            $this->tokenRepository->create($tokenEntity);
        }
        $resultTokenEntity = new TokenValueEntity($token, 'bearer', $identityEntity->getId());
        $resultTokenEntity->setId($tokenEntity->getId());
        return $resultTokenEntity;
    }

    public function getIdentityIdByToken(string $token): int
    {
        list($tokenType, $tokenValue) = explode(' ', $token);
        $tokenEntity = $this->tokenRepository->findOneByValue($tokenValue, 'bearer');
        return $tokenEntity->getIdentityId();
    }

//    private function generateToken(): string
//    {
//        return $this->authorizationTokenGenerator->generateToken();
//
//        // todo: отделить генератор пароля в отдельный класс
//        /*$random = new RandomString();
//        $random->setLength($this->tokenLength);
////        $random->addCharactersAll();
//        $random->addCharactersLower();
//        $random->addCharactersUpper();
//        $random->addCharactersNumber();
//        $random->addCustomChar('!#$%&()*+,-./:;<=>?@[]^_`{|}~');
//        return $random->generateString();*/
//    }
}
