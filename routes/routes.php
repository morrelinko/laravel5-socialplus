<?php

/**
 * @var \Illuminate\Routing\Router $router
 */

$router = app('router');

$router->match(['GET', 'POST'], '/social/{provider}/authorize', [
    'as' => 'socialplus.authorize',
    'uses' => 'Morrelinko\SocialPlus\AuthorizeController@authorize'
])->where('provider', 'facebook|twitter|linkedin|googleplus');

$router->match(['GET', 'POST'], '/social/{provider}/callback', [
    'as' => 'socialplus.callback',
    'uses' => 'Morrelinko\SocialPlus\AuthorizeController@callback'
])->where('provider', 'facebook|twitter|linkedin|googleplus');
