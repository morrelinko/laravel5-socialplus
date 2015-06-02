<?php

namespace Morrelinko\SocialPlus;

use Exception;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
class AuthorizeController extends Controller
{
    use DispatchesCommands;

    /**
     * @var \Morrelinko\SocialPlus\SocialPlus
     */
    protected $socialPlus;

    public function __construct(SocialPlus $socialPlus)
    {
        $this->socialPlus = $socialPlus;
    }

    /**
     * @param Request $request
     * @param $provider
     * @return mixed
     */
    public function authorize(Request $request, $provider)
    {
        $action = $request->query('a');

        $this->socialPlus->setAuthData($provider, [
            'action' => $action
        ]);

        $this->socialPlus->getAuthorizeHandler($action)->authorize($provider);

        return $this->socialPlus->getSocialite()->with($provider)->redirect();
    }

    /**
     * @param Request $request
     * @param $provider
     * @return $this
     */
    public function callback(Request $request, $provider)
    {
        $data = $this->socialPlus->getAuthData($provider);

        try
        {
            $user = $this->socialPlus->getSocialite()->with($provider)->user();

            $data = $this->socialPlus->getAuthData($provider);

            // Prepare token
            $token = property_exists($user, 'tokenSecret')
                ? implode(':', [$user->token, $user->tokenSecret])
                : $user->token;

            $this->socialPlus->setAuthData($provider, array_merge($data, [
                'token' => $token,
                'user' => $user
            ]));

            return $this->socialPlus->getAuthorizeHandler(
                $data['action']
            )->callback($user, $token, $provider);
        }
        catch (Exception $e)
        {
            return $this->socialPlus->getAuthorizeHandler(
                $data['action']
            )->exception($e, $provider);
        }
    }
}
