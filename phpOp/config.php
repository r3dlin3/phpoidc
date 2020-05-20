<?php

/**
 * Copyright 2013 Nomura Research Institute, Ltd.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0

 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
require_once(__DIR__ . '/libs/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$theme_name = getenv('THEME_NAME') ?: 'default';
$theme_path =  getenv('THEME_PATH') ?: (__DIR__ . '/theme/' . $theme_name);

$op_server_name = getenv('OP_SERVER_NAME') ?: $_SERVER['SERVER_NAME'];


// variables to construct OP_URL
$protocol = $_SERVER['REQUEST_SCHEME'] .'://';
$port='';
if ( !($_SERVER['REQUEST_SCHEME'] === "http" && $_SERVER['SERVER_PORT'] == 80)
        || !($_SERVER['REQUEST_SCHEME'] === "https" && $_SERVER['SERVER_PORT'] == 443))
{
    $port= ':'.$_SERVER['SERVER_PORT'];
}
$path = getenv('OP_URL') ? parse_url(getenv('OP_URL'))['path'] : dirname($_SERVER['SCRIPT_NAME']);

$op_url = getenv('OP_URL') ?: ($protocol . $_SERVER['SERVER_NAME'] . $port . $path);

/**
 * OP endpoints and metadata
 */
define('OP_INDEX_PAGE', $op_url . '/index.php');
define('OP_AUTH_EP', OP_INDEX_PAGE . '/auth');
define('OP_TOKEN_EP', OP_INDEX_PAGE . '/token');
define('OP_USERINFO_EP', OP_INDEX_PAGE . '/userinfo');
define('OP_CHECKSESSION_EP', OP_INDEX_PAGE . '/checksession');
define('OP_SESSIONINFO_EP', OP_INDEX_PAGE . '/sessioninfo');
define('OP_REGISTRATION_EP', OP_INDEX_PAGE . '/register_form');
define('OP_PASSWORD_RESET_EP', OP_INDEX_PAGE . '/passreset_form');

/**
 * Global config
 */
$config = [
    'site' => [
        'theme_name' => $theme_name,
        'theme_path' => $theme_path,
        'theme_uri' => getenv('THEME_URI') ?: (dirname($_SERVER['SCRIPT_NAME']) . '/theme/' . $theme_name),
        'views_path' => getenv('VIEWS_PATH') ?: $theme_path . '/views',
        'name' => getenv('SITE_NAME') ?: $op_server_name,
        "url" => $op_url,
        'enable_password_reset' =>  array_key_exists('ENABLE_PASSWORD_RESET', $_ENV) ? (getenv('ENABLE_PASSWORD_RESET') === 'true') : true,
        'password_reset_url' => getenv('PASSWORD_RESET_URL') ?: OP_PASSWORD_RESET_EP,
        'enable_registration' => array_key_exists('ENABLE_REGISTRATION', $_ENV) ? (getenv('ENABLE_REGISTRATION') === 'true') : true,
        'registration_url' => getenv('REGISTRATION_URL') ?: OP_REGISTRATION_EP,
    ],

    'twig' => [
        'cache' => getenv('TWIG_CACHE') ?: (__DIR__ . '/cache'),
        'auto_reload' => array_key_exists('TWIG_AUTO_RELOAD', $_ENV) ? (getenv('TWIG_AUTO_RELOAD') === 'true') : true,
    ],

    'OP' => [
        'op_server_name'=> $op_server_name,
        'op_url' => $op_url,
        'enable_pkce' => getenv('ENABLE_PKCE') ?: false,
        'path' => $path,
        'sig_pkey_passphrase' => getenv('OP_SIG_PKEY_PASSPHRASE') ?:  '',
        'enc_pkey' => getenv('OP_ENC_PKEY') ?:  dirname($_SERVER['SCRIPT_FILENAME']) . '/op_enc.key',
        'enc_pkey_passphrase' => getenv('OP_ENC_PKEY_PASSPHRASE') ?:  '',
        'jwk_url' => getenv('OP_JWK_URL') ?:  $op_url . '/op.jwk',
        'sig_kid' => getenv('OP_SIG_KID') ?:  'PHPOP-00S',
        'enc_kid' => getenv('OP_ENC_KID') ?:  'PHPOP-00E',
    ],

    'DB' => [
        'type' => getenv('DB_TYPE') ?: 'mysql',
        'user' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_HOST') ?: '3306',
        'database' => getenv('DB_DATABASE') ?: 'phpoidc'
    ]
];

///////////////
// I18n
///////////////

// create the accept factory
$accept_factory = new Aura\Accept\AcceptFactory($_SERVER);

// factory the accept object
$accept = $accept_factory->newInstance();

// language negotiation
$available_languages = array('en', 'fr');
$language = $accept->negotiateLanguage($available_languages);
$locale = $language->getValue();

// Set language
putenv('LANGUAGE=' . $locale);
setlocale(LC_ALL, $locale);

define('DOMAIN', 'messages');
// Specify the location of the translation tables
bindtextdomain(DOMAIN, __DIR__ . '/locales');
bind_textdomain_codeset(DOMAIN, 'UTF-8');
// Choose domain
textdomain(DOMAIN);

$loader = new \Twig\Loader\FilesystemLoader($config['site']['views_path']);
$twig = new \Twig\Environment($loader, [
    'cache' => $config['twig']['cache'],
    'auto_reload' => $config['twig']['auto_reload']
]);
$twig->addGlobal('site', $config['site']);
$twig->addExtension(new Twig_Extensions_Extension_I18n());

$register_form = [
    [
        'name' => 'email',
        'type' => 'email',
        'error_message' => 'A valid e-mail address is required',
        'attr' => "required autofocus"
    ],
    [
        'name' => 'login',
        'type' => 'computed',   
    ],
    
    [
        'name' => 'given_name',
        'type' => 'text',   
        'attr' => "required"
    ],
    [
        'name' => 'family_name',
        'type' => 'text',   
        'attr' => "required"
    ],
    [
        'name' => 'password',
        'type' => 'password',   
        'attr' => "required data-eye"
    ],
];
