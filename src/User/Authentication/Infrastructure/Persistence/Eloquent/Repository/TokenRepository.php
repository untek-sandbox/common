<?php

namespace Untek\User\Authentication\Infrastructure\Persistence\Eloquent\Repository;

use Symfony\Component\Security\Core\User\UserInterface;
use Untek\Core\Contract\Common\Exceptions\NotFoundException;
use Untek\Core\Text\Libs\RandomString;
use Untek\Database\Doctrine\Domain\Base\AbstractDoctrineCrudRepository;
use Untek\Database\Eloquent\Infrastructure\Abstract\AbstractEloquentCrudRepository;
use Untek\User\Authentication\Domain\Entities\CredentialEntity;
use Untek\User\Authentication\Domain\Entities\TokenValueEntity;
use Untek\User\Authentication\Domain\Interfaces\Services\TokenServiceInterface;
use Untek\User\Authentication\Domain\Model\Token;

class TokenRepository extends AbstractEloquentCrudRepository implements TokenServiceInterface
{

    public function getTableName(): string
    {
        return 'user_token';
    }

    public function getClassName(): string
    {
        return Token::class;
    }

    public function getTokenByIdentity(UserInterface $identityEntity): TokenValueEntity
    {
        $token = $this->generateToken($identityEntity);
        $resultTokenEntity = new TokenValueEntity($token, 'bearer');
        return $resultTokenEntity;
    }

    public function getIdentityIdByToken(string $token): int
    {
        list($type, $value) = explode(' ', $token);
        $value = trim($value);
        $item = $this->findOneBy(['value' => $value]);
        if($item) {
            return $item->getIdentityId();
        }
        throw new NotFoundException('Token not found.');
    }

    protected function hydrate(array $item): object
    {
        $token = new Token($item['user_id'], $item['value'], $item['type']);
        return $token;
    }

    protected function dehydrate(object $entity): array
    {
        /** @var Token $entity */
        return [
            'user_id' => $entity->getIdentityId(),
            'type' => $entity->getType(),
            'value' => $entity->getValue(),
            'created_at' => (new \DateTime())->format(\DateTime::ISO8601),
        ];
    }

    protected function generateToken(UserInterface $identityEntity): string
    {
        $random = new RandomString();
        $random->addCharactersUpper();
        $random->addCharactersLower();
        $random->addCharactersNumber();
        $random->setLength(64);

        do {
            $value = $random->generateString();
            $item = $this->findOneBy(['value' => $value]);
        } while($item);

        $token = new Token($identityEntity->getId(), $value, 'bearer');
        $this->create($token);

        return $value;
    }
}