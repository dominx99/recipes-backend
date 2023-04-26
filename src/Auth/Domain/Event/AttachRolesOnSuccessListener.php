<?php

declare(strict_types=1);

namespace App\Auth\Domain\Event;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onCustomEvent')]
final class AttachRolesOnSuccessListener
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function onCustomEvent(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        $data = $event->getData();
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            return;
        }

        $data['roles'] = $user->getRoles();

        $event->setData($data);
    }
}
