<?php

namespace Untek\User\Authentication\Domain\Services;

use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Untek\Core\Contract\Common\Exceptions\InvalidMethodParameterException;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;
use Untek\User\Authentication\Domain\Helpers\TokenHelper;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;

class MockTokenService implements TokenServiceInterface
{
    private array $tokens;

    public function __construct(private InMemoryUserProvider $inMemoryUserProvider, array $tokens)
    {
        $this->tokens = $tokens;
    }

    public function getTokenByIdentity(UserInterface $identityEntity): TokenValueEntity
    {
        $token = $this->generateToken($identityEntity->getUserIdentifier());
        $resultTokenEntity = new TokenValueEntity($token, 'bearer');
        return $resultTokenEntity;
    }

    public function getIdentityIdByToken(string $token): int
    {
    }

    public function findUserByToken(string $token): UserInterface
    {
        try {
            $tokenEntity = TokenHelper::parseToken($token);
        } catch (InvalidMethodParameterException $exception) {
            throw new UserNotFoundException();
        }

        foreach ($this->tokens as $item) {
            if ($item['value'] == $tokenEntity->getToken() && $item['type'] == $tokenEntity->getType()) {
                $user = $this->inMemoryUserProvider->loadUserByIdentifier($item['user_identifier']);
                $user->eraseCredentials();
                return $user;
            }
        }
        throw new UserNotFoundException();
    }

    protected function generateToken(string $identifier): string
    {
        return hash('sha256', $identifier);
    }
}
