<?php

/**
 * @param $provider
 * @return array
 */
function sp_auth($provider)
{
    return app('socialplus')->getAuthData($provider);
}

/**
 * @param $provider
 * @return \Laravel\Socialite\Contracts\User
 */
function sp_auth_user($provider)
{
    $data = sp_auth($provider);

    return isset($data['user']) ? $data['user'] : null;
}

/**
 * @param null $key
 * @return mixed
 */
function sp_auth_recent($key = null)
{
    $data = app('socialplus')->getRecentAuthData();

    return $key ? (isset($data[$key]) ? $data[$key] : null) : $data;
}

/**
 * @return mixed
 */
function sp_auth_recent_token()
{
    return sp_auth_recent('token');
}

/**
 * @return mixed
 */
function sp_auth_recent_provider()
{
    return sp_auth_recent('provider');
}

/**
 * @param $field
 * @param null $default
 * @return mixed
 */
function sp_auth_recent_user($field, $default = null)
{
    $user = sp_auth_user(sp_auth_recent_provider());

    if (!$user) {
        return $default;
    }

    if ($field === 'firstname') {
        $names = explode(' ', $user->getName(), 2);

        return $names[0];
    } elseif ($field === 'lastname') {
        $names = explode(' ', $user->getName(), 2);

        return $names[1];
    } elseif ($field === 'username') {
        return $user->getNickname();
    } else {
        return $user->{sprintf('get%s', camel_case($field))}();
    }
}