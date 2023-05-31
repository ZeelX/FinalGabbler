<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class DiscordAuthenticator extends AbstractAuthenticator
{
    const DISCORD_AUTH_KEY = 'discord-auth';

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly RouterInterface $router)
    {
    }


    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route')==='app_discord_auth' && $this->isValidRequest($request);

    }

    public function authenticate(Request $request): Passport
    {
        if (!$this->isValidRequest($request)){
            throw new AuthenticationException('Invalide request');
        }
        $accessToken = $request->query->get('accessToken');
        if (!$accessToken){
            throw new AuthenticationException('No access token provided');
        }

        $user = $this->userRepository->findOneId(['accessToken' => $accessToken]);

        if (!$user){
            throw new AuthenticationException('wrong access token');
        }

        $userBadge = new UserBadge($user->getUserIdentifier(), function() use ($user){
            return $user;
        });

        return new SelfValidatingPassport($userBadge);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $request->getSession()->remove(self::DISCORD_AUTH_KEY);
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $session = $request->getSession();
        $session->remove(self::DISCORD_AUTH_KEY);
        $session->getFlashBag()->set('danger', $exception->getMessage());

        return new RedirectResponse(($this->router->generate('app_login')));
    }

    private function IsValidRequest (Request $request): bool
    {
        return true == $request->getSession()->get(self::DISCORD_AUTH_KEY);
    }
}