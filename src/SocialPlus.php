<?php

namespace Morrelinko\SocialPlus;

use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Laravel\Socialite\SocialiteManager;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class SocialPlus
{
    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var SessionManager|Store
     */
    protected $session;

    /**
     * @var SocialiteManager
     */
    protected $socialite;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * @param SocialiteManager $socialiteManager
     */
    public function setSocialite(SocialiteManager $socialiteManager)
    {
        $this->socialite = $socialiteManager;
    }

    /**
     * @return SocialiteManager
     */
    public function getSocialite()
    {
        return $this->socialite;
    }

    /**
     * @param $identifier
     * @param AuthorizeHandler $handler
     */
    public function registerAuthorizeHandler($identifier, $handler)
    {
        $this->handlers[$identifier] = $handler;
    }

    /**
     * Recent / Providers
     * @param $provider
     * @param $data
     */
    public function setAuthData($provider, $data)
    {
        $this->session->set('__sp_auth.r', $provider);
        $this->session->set('__sp_auth.p.' . $provider, array_merge([
            'provider' => $provider
        ], $data));

        $this->session->save();
    }

    /**
     * @param $provider
     * @return mixed
     */
    public function getAuthData($provider)
    {
        return $this->session->get('__sp_auth.p.' . $provider);
    }

    /**
     * Allows you retrieve the most recent social provider
     * authorized with.
     *
     * @return mixed
     */
    public function getRecentAuthData()
    {
        $providers = $this->session->get('__sp_auth.p');

        if (!$providers)
        {
            return null;
        }

        return array_first($providers, function ($provider)
        {
            return $provider === $this->session->get('__sp_auth.r');
        });
    }

    /**
     * @param $action
     * @return mixed
     */
    public function getAuthorizeHandler($action)
    {
        if (!isset($this->handlers[$action]))
        {
            throw new \RuntimeException(
                sprintf('No handlers registered for action [%s]', $action)
            );
        }

        return $this->handlers[$action];
    }
}
