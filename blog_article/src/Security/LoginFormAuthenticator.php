<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;


class LoginFormAuthenticator extends AbstractAuthenticator{

    private $userRepository;
    private $urlGenerator;

    public function __construct(UserRepository $userRepository, UrlGeneratorInterface $urlGenerator){
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    /**
     * @throws AuthenticationException
     */
    public function authenticate(Request $request): PassportInterface
    {
        $password = $request->request->get('password');
        $email = $request->request->get('email');
        $csrfToken = $request->request->get('csrf_token');

       //chercher un utilsateur avec un email 
       $user = $this->userRepository->findOneByEmail($email);
       if(!$user){
           throw new CustomUserMessageAuthenticationException('Invalid email');
       } 

       return new Passport( $user, new PasswordCredentials($password), [
        new CsrfTokenBadge('login_form', $csrfToken) 
            ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
      $request->getSession()->getFlashBag()->add('error', 'Email ou mot de passe incorrect!');
      return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }



}    