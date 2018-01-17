<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\SocialAuthBundle\Controller;

use League\OAuth1\Client\Server\Server;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Mindy\Bundle\MindyBundle\Controller\Controller;
use Mindy\Bundle\SocialAuthBundle\Provider\SocialUserInterface;
use Mindy\Bundle\SocialAuthBundle\Registry\SocialRegistry;
use Mindy\Bundle\SocialBundle\Model\SocialUser;
use Mindy\Bundle\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class SocialController extends Controller
{
    const SESSION_KEY = 'oauth_state';

    /**
     * Action for oauth2 clients
     *
     * @param Request          $request
     * @param string           $provider
     * @param AbstractProvider $providerInstance
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function procesOauth2(Request $request, string $provider, AbstractProvider $providerInstance)
    {
        $redirectUri = $this->generateUrl(
            'social_auth',
            ['provider' => $provider],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $session = $request->getSession();

        if (false === $request->query->has('code')) {
            $authUrl = $providerInstance->getAuthorizationUrl([
                'redirect_uri' => $redirectUri,
            ]);
            $session->set(self::SESSION_KEY, $providerInstance->getState());

            // Redirect user to provier auth
            return $this->redirect($authUrl);
        }

        $code = $request->query->get('code');

        if (
            false === $request->query->has('state') ||
            $request->query->get('state') !== $session->get(self::SESSION_KEY)
        ) {
            $session->remove(self::SESSION_KEY);
            throw new \RuntimeException('Invalid state');
        }

        try {
            $providerAccessToken = $providerInstance->getAccessToken('authorization_code', [
                'code' => $code,
                'redirect_uri' => $redirectUri,
            ]);

            /** @var SocialUserInterface|ResourceOwnerInterface $owner */
            $owner = $providerInstance->getResourceOwner($providerAccessToken);

            return $this->processUser($request, $provider, $owner);
        } catch (IdentityProviderException $e) {
            return $this->render('social_auth/error.html', [
                'error' => $e,
            ]);
        }
    }

    /**
     * @param Request             $request
     * @param string              $provider
     * @param SocialUserInterface $owner
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function processUser(Request $request, string $provider, SocialUserInterface $owner)
    {
        list($socialUser, $created) = SocialUser::objects()->getOrCreate([
            'owner_id' => $owner->getId(),
            'provider' => $provider,
        ]);
        $socialUser->params = json_encode($owner->toArray());
        if (false === $socialUser->save()) {
            throw new \RuntimeException('Failed to save social user information');
        }

        $email = $owner->getEmail();
        if (false === empty($email)) {
            $user = User::objects()->get(['email' => $email]);
            if (null === $user) {
                $user = new User([
                    'email' => $email,
                    'is_active' => true,
                ]);
                $user->save();
            }

            $socialUser->user = $user;
            $socialUser->save();
        } else {
            throw new \RuntimeException('Email required');
        }

        $this->authenticateUser($request, $user);

        return $this->redirect('/');
    }

    /**
     * Action for oauth1 clients like twitter
     *
     * @param Request $request
     * @param string  $provider
     * @param Server  $providerInstance
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function procesOauth1(Request $request, string $provider, Server $providerInstance)
    {
        $session = $request->getSession();

        if ($request->query->get('oauth_token') && $request->query->get('oauth_verifier')) {
            // Retrieve the temporary credentials from step 2
            $temporaryCredentials = unserialize($session->get('temporary_credentials'));
            // Third and final part to OAuth 1.0 authentication is to retrieve token
            // credentials (formally known as access tokens in earlier OAuth 1.0
            // specs).
            $tokenCredentials = $providerInstance->getTokenCredentials(
                $temporaryCredentials,
                $request->query->get('oauth_token'),
                $request->query->get('oauth_verifier')
            );

            /** @var \League\OAuth1\Client\Server\User|SocialUserInterface $owner */
            $owner = $providerInstance->getUserDetails($tokenCredentials);

            return $this->processUser($request, $provider, $owner);
        }

        $redirectUri = $this->generateUrl(
            'social_auth',
            ['provider' => $provider],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $providerInstance->getClientCredentials()
            ->setCallbackUri($redirectUri);

        // First part of OAuth 1.0 authentication is retrieving temporary credentials.
        // These identify you as a client to the server.
        $temporaryCredentials = $providerInstance->getTemporaryCredentials();

        // Store the credentials in the session.
        $session->set('temporary_credentials', serialize($temporaryCredentials));

        // Second part of OAuth 1.0 authentication is to redirect the
        // resource owner to the login screen on the server.
        return $this->redirect($providerInstance->getAuthorizationUrl($temporaryCredentials));
    }

    public function auth(Request $request, SocialRegistry $registry, $provider)
    {
        if (false === $registry->hasProvider($provider)) {
            throw new NotFoundHttpException();
        }

        $providerInstance = $registry->getProvider($provider);

        if ($providerInstance instanceof AbstractProvider) {
            return $this->procesOauth2($request, $provider, $providerInstance);
        }

        return $this->procesOauth1($request, $provider, $providerInstance);
    }

    protected function authenticateUser(Request $request, UserInterface $user)
    {
        // Here, $providerKey is the name of the firewall in your security.yml
        $providerKey = 'site';

        $token = new UsernamePasswordToken($user, $user->getPassword(), $providerKey, $user->getRoles());
        $this->get('security.token_storage')->setToken($token);

        $session = $request->getSession();
        $session->set(sprintf('_security_%s', $providerKey), serialize($token));
        $session->save();

        // Fire the login event
        // Logging the user in above the way we do it doesn't do this automatically
        $event = new InteractiveLoginEvent($request, $token);
        $this->get('event_dispatcher')->dispatch(SecurityEvents::INTERACTIVE_LOGIN, $event);
    }
}
