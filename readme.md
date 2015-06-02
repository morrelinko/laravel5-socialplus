Laravel 5 SocialPlus
------------------------------

## Installation

Add this to your `composer.json` file

Append this to your list of service providers in `config/app.php`

    'Morrelinko\SocialPlus\SocialPlusServiceProvider'

Run the command below

    $ php artisan vendor:publish --provider="Morrelinko\SocialPlus\SocialPlusServiceProvider"
    
## Social Providers

[TODO - Extract from socialite]

Add provider configuration in `config/services.php`

    "facebook": {
        "client_id": "...",
        "client_secret": "..."
    }

## Handlers

Handlers are a way SocialPlus delegates performing custom behaviours in your application 

during pre-authorization (authorize request) and post-authorization (callback request).
 
This is where you may implement your login workflow and whatever.

Handlers are registered in `config/socialplus.php` like so

    <?php
    
    return [
        'authorize_handlers' => [
            'App\Handlers\SocialPlus\AuthHandler'
        ]
    ];


Suggestion: Handlers should be placed in `app/Handlers/SocialPlus` folder

#### Example Handler

    namespace App\Handlers\SocialPlus;

    use Morrelinko\SocialPlus\AuthorizeHandler;
    use Laravel\Socialite\Contracts\User as SocialiteUser;
    
    class AuthHandler implements AuthorizeHandler
    {
        public function getIdentifier()
        {
            return 'auth';
        }
        
        public function authorize($provider)
        {
            // Called before any request is made to the provider
        }
        
        public function callback(SocialiteUser $socialiteUser, $accessToken, $provider)
        {
            // Use the information provided as arguments for whatever your needs are.
        }
    }
    
Once you done registering the handler, you create a link like so;

    // - route('socialplus.authorize', ['provider' => 'facebook', 'a' => 'auth'])

    // - eg
    
    <a href="{{ route('socialplus.authorize', ['provider' => 'facebook', 'a' => 'auth']) }}">Login With Facebook</a>

notice the 'a' parameter? .... That's the value you return from `getIdentifier()` in the handler we just created and

its what tells socialplus what handler to use before and after authorization.
    
## Contribute

[WIP] This documentation is a work in progress and would really appreciate contributions.
