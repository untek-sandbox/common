<?php

namespace Untek\User\Authentication\Domain\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Untek\Core\Code\Helpers\DeprecateHelper;
use Untek\User\Authentication\Domain\Enums\AuthEventEnum;
use Untek\User\Authentication\Domain\Events\AuthEvent;
use Untek\User\Identity\Domain\Events\IdentityEvent;
use Untek\Core\Contract\User\Interfaces\Entities\IdentityEntityInterface;
use Untek\Model\Entity\Helpers\EntityHelper;
use Untek\Model\EntityManager\Interfaces\EntityManagerInterface;
use Untek\Model\EntityManager\Traits\EntityManagerAwareTrait;

DeprecateHelper::hardThrow();

class SymfonyAuthenticationIdentitySubscriber implements EventSubscriberInterface
{

    use EntityManagerAwareTrait;

    private $session;

    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session
    )
    {
        $this->session = $session;
        $this->setEntityManager($em);
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthEventEnum::BEFORE_GET_IDENTITY => 'onBeforeGetIdentity',
            AuthEventEnum::BEFORE_IS_GUEST => 'onBeforeGuest',
            AuthEventEnum::AFTER_LOGOUT => 'onAfterLogout',
            AuthEventEnum::AFTER_AUTH_SUCCESS => 'onAfterAuthSuccess',
        ];
    }

    public function onAfterAuthSuccess(AuthEvent $event)
    {
        $this->session->set('user.identity', EntityHelper::toArray($event->getIdentityEntity()));
    }

    public function onBeforeGetIdentity(IdentityEvent $event)
    {
        $identity = $this->session->get('user.identity');
        if ($identity) {
            /** @var IdentityEntityInterface $identityEntity */
            $identityEntity = $this->getEntityManager()->createEntity(IdentityEntityInterface::class, $identity);
            $event->setIdentityEntity($identityEntity);
        }
    }

    public function onBeforeGuest(IdentityEvent $event)
    {
        $identity = $this->session->get('user.identity');
        $event->setIsGuest(empty($identity));
    }

    public function onAfterLogout(IdentityEvent $event)
    {
        $this->session->remove('user.identity');
    }
}
