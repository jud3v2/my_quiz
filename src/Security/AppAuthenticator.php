<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * @see https://symfony.com/doc/current/security/custom_authenticator.html
 */
class AppAuthenticator extends AbstractAuthenticator
{
        use TargetPathTrait;

        public const LOGIN_ROUTE = 'app_login';
        /**
         * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface
         */
        private UrlGeneratorInterface $urlGenerator;

        public function __construct(UrlGeneratorInterface $urlGenerator)
        {

                $this->urlGenerator = $urlGenerator;
        }

        /**
         * Called on every request to decide if this authenticator should be
         * used for the request. Returning `false` will cause this authenticator
         * to be skipped.
         */
        public function supports(Request $request): ?bool
        {
                // check if user is already authenticated
                return $request->headers->has('X-AUTH-TOKEN');
        }

        public function authenticate(Request $request): Passport
        {
                $username = $request->request->get('username', '');

                $request->getSession()->set(
                    SecurityRequestAttributes::LAST_USERNAME,
                    $username
                );

                return new Passport(
                    new UserBadge($username),
                    new PasswordCredentials($request->request->get('password', '')),
                    [
                        new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                        new RememberMeBadge(),
                    ]
                );
        }

        public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
        {
                // if user wanted to access a specific page before being authenticated
                // redirect to that page
                if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
                        return new RedirectResponse($targetPath);
                }

                // on success, let the request continue
                return new RedirectResponse('/');
        }

        public function getLoginUrl(): string
        {
                return $this->urlGenerator->generate(self::LOGIN_ROUTE);
        }

        public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
        {
               if($request->hasSession()) {
                        $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
                        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $request->request->get('username'));
                }

               $url =$this->getLoginUrl();

                return new RedirectResponse($url);
        }

        // public function start(Request $request, AuthenticationException $authException = null): Response
        // {
        //     /*
        //      * If you would like this class to control what happens when an anonymous user accesses a
        //      * protected page (e.g. redirect to /login), uncomment this method and make this class
        //      * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface.
        //      *
        //      * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
        //      */
        // }
}
