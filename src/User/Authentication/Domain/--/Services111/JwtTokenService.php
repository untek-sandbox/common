<?php

namespace Untek\User\Authentication\Domain\Services;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\Crypt\Jwt\Domain\Entities\JwtEntity;

class JwtTokenService implements TokenServiceInterface
{

    public function getTokenByIdentity(IdentityEntityInterface $identityEntity): TokenValueEntity
    {
        $jwtEntity = new JwtEntity;
        $jwtEntity->subject = ['id' => $identityEntity->getId()];
        $token = $this->jwtService->sign($jwtEntity, 'auth');
        $tokenEntity = new TokenValueEntity;
        $tokenEntity->setIdentity($identityEntity);
        $tokenEntity->setType('jwt');
        $tokenEntity->setToken($token);
        return $tokenEntity;
    }

    public function getIdentityIdByToken(string $token): int
    {
        list($tokenType, $tokenValue) = explode(' ', $token);
        $jwtEntity = $this->jwtService->verify($tokenValue, 'auth');
        $dto = $this->jwtService->decode($tokenValue);
        return $dto->payload->subject->id;
    }
}
