<?php

namespace Morrelinko\SocialPlus;

/**
 * @author Laju Morrison <morrelinko@gmail.com>
 */
interface AuthorizeHandler
{
    public function authorize($provider);

    public function callback($user, $token, $provider);

    public function exception($exception, $provider);
}
