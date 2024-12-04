<?php

return [
    /**
     * Keycloak Url
     *
     * Generally https://your-server.com/auth
     */
    'base_url' => env('KEYCLOAK_BASE_URL', 'http://host.docker.internal:5000/'),

    /**
     * Keycloak Realm
     *
     * Default is master
     */
    'realm' => env('KEYCLOAK_REALM', 'katana'),

    /**
     * The Keycloak Server realm public key (string).
     *
     * @see Keycloak >> Realm Settings >> Keys >> RS256 >> Public Key
     */
    'realm_public_key' => env('KEYCLOAK_PUBLIC_KEY', 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3lmTZxMQoI3aOgswW6BwS7MwejB9hAxVXl2zDX4TkrZlmq31qPaiFPCKZnSBPbe3qsX0OM/XcqMSNOzV0uxGnmeycKQ/5ho3ZbXBn0TFctlLZPgRk3WTsMLnC6ZOa3z5FMDbhHWRKRYyjMMWgg8N7jVRv8K5bHKkyuNLwW1fAl5EqDzmr1c986ziSHU6q5y0fm74dRC4h6BBcpnM1wnsaZWxwLE/emvfgUN2AmUlo3PdHusWcUBanaN6irwmpCPLwQryaCMU8CxRGzPVCHfdMz3xgE+wYlzd2QRk206ptF+smgtBj8xdWVxA5jUyDNJBfJae5dGh827ingbDWVQLqwIDAQAB'),

    /**
     * Keycloak Client ID
     *
     * @see Keycloak >> Clients >> Installation
     */
    'client_id' => env('KEYCLOAK_CLIENT_ID', 'node-app'),

    /**
     * Keycloak Client Secret
     *
     * @see Keycloak >> Clients >> Installation
     */
    'client_secret' => env('KEYCLOAK_CLIENT_SECRET', 'qei96eLw2aeRkLymvtg212EILyvrJJUa'),

    /**
     * We can cache the OpenId Configuration
     * The result from /realms/{realm-name}/.well-known/openid-configuration
     *
     * @link https://www.keycloak.org/docs/3.2/securing_apps/topics/oidc/oidc-generic.html
     */
    'cache_openid' => env('KEYCLOAK_CACHE_OPENID', false),

    /**
     * Page to redirect after callback if there's no "intent"
     *
     * @see Vizir\KeycloakWebGuard\Controllers\AuthController::callback()
     */
    'redirect_url' => env('KEYCLOAK_REDIRECT_URI', 'http://localhost/php/api/auth/callback'),

    /**
     * The routes for authenticate
     *
     * Accept a string as the first parameter of route() or false to disable the route.
     *
     * The routes will receive the name "keycloak.{route}" and login/callback are required.
     * So, if you make it false, you shoul register a named 'keycloak.login' route and extend
     * the Vizir\KeycloakWebGuard\Controllers\AuthController controller.
     */
    'routes' => [
        'login' => 'login',
        'logout' => 'logout',
        'register' => 'register',
        'callback' => 'callback',
    ],

    /**
    * GuzzleHttp Client options
    *
    * @link http://docs.guzzlephp.org/en/stable/request-options.html
    */
   'guzzle_options' => [],

    /**
     * Keycloak optional scopes
     *
     * array of strings
     */
    'scopes' => [],
];
