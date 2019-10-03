<?php


declare(strict_types=1);

require_once (__DIR__ . '/../libdb2.php');


use PHPUnit\Framework\TestCase;

final class CDatabaseTest extends  TestCase
{

    private static $em;
    private static $tool;
    private static $classes;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $schemaManager = self::$em->getConnection()->getSchemaManager();
        if ($schemaManager->tablesExist(array('account', 'provider', 'client', 'request_file', 'token', 'user_trusted_client')) == true) {

//            $lines = self::$tool->getCreateSchemaSql(self::$classes);
//            foreach ($lines as $line) {
//                echo $line . "\n";
//            }

            self::$tool->dropSchema(self::$classes);
        }

//        $lines = self::$tool->getCreateSchemaSql(self::$classes);
//        foreach($lines as $line) {
//            echo $line . "\n";
//        }

        self::$tool->createSchema(self::$classes);

        $alice = new Account();
        $alice->setLogin("alice");
        $alice->setEnabled(true);
        $alice->setCryptedPassword('b6263bb14858294c08e4bdfceba90363e10d72b4');
        $alice->setName('Alice Yamada');
        $alice->setNameJaKanaJp("ヤマダアリサ");
        $alice->setNameJaHaniJp("山田亜理紗");
        $alice->setGivenName('Alice');
        $alice->setGivenNameJaKanaJp('アリサ');
        $alice->setGivenNameJaHaniJp("亜理紗");
        $alice->setFamilyName('Yamada');
        $alice->setFamilyNameJaKanaJp("ヤマダ");
        $alice->setFamilyNameJaHaniJp("山田");
        $alice->setNickname('Standard Alice Nickname');
        $alice->setPreferredUsername('AlicePreferred');
        $alice->setProfile('http://www.wonderland.com/alice');
        $alice->setPicture('http://www.wonderland.com/alice/smiling_woman.jpg');
        $alice->setWebsite('http://www.wonderland.com');
        $alice->setEmail('alice@wonderland.com');
        $alice->setEmailVerified(true);
        $alice->setGender('female');
        $alice->setBirthdate('2000-08-08');
        $alice->setZoneinfo('America/Los Angeles');
        $alice->setLocale('en');
        $alice->setPhoneNumber('1-81-234-234234234');
        $alice->setPhoneNumberVerified(true);
        $alice->setAddress('123 wonderland way');
        $alice->setUpdatedAt(23453453);


        $bob = new Account();
        $bob->setLogin("bob");
        $bob->setEnabled(true);
        $bob->setCryptedPassword('cc8684eed2b6544e89242558df73a7208c9391b4');
        $bob->setName('Bob Ikeda');
        $bob->setNameJaKanaJp("イケダボブ");
        $bob->setNameJaHaniJp("池田保夫");
        $bob->setGivenName('Bob');
        $bob->setGivenNameJaKanaJp('ボブ');
        $bob->setGivenNameJaHaniJp("保夫");
        $bob->setFamilyName('Ikeda');
        $bob->setFamilyNameJaKanaJp("イケダ");
        $bob->setFamilyNameJaHaniJp("池田");
        $bob->setNickname('BobNick');
        $bob->setPreferredUsername('BobPreferred');
        $bob->setProfile('http://www.underland.com/bob');
        $bob->setPicture('http://www.underland.com/bob/smiling_man.jpg');
        $bob->setWebsite('http://www.underland.com');
        $bob->setEmail('bob@underland.com');
        $bob->setEmailVerified(true);
        $bob->setGender('male');
        $bob->setBirthdate('2111-11-11');
        $bob->setZoneinfo('France/Paris');
        $bob->setLocale('fr');
        $bob->setPhoneNumber('1-81-234-234234234');
        $bob->setPhoneNumberVerified(true);
        $bob->setAddress('456 underland ct.');
        $bob->setUpdatedAt(43453453);


        $now = new DateTime();
        $aliceToken1 = new Token();
        $aliceToken1->setToken('code1');
        $aliceToken1->setInfo('alice auth code info1');
        $aliceToken1->setDetails('alice auth code 1');
        $aliceToken1->setTokenType(0);
        $aliceToken1->setClient('client1');
        $aliceToken1->setExpirationAt($now->add(new DateInterval('P1D')));
        $aliceToken1->setIssuedAt($now);
        $alice->addToken($aliceToken1);


        $aliceToken2 = new Token();
        $aliceToken2->setToken('accesstoken2');
        $aliceToken2->setInfo('alice accesstoken info2');
        $aliceToken2->setDetails('alice accesstoken detail 2');
        $aliceToken2->setTokenType(1);
        $aliceToken2->setClient('client2');
        $aliceToken2->setExpirationAt($now->add(new DateInterval('P1D')));
        $aliceToken2->setIssuedAt($now);
        $alice->addToken($aliceToken2);

        $aliceToken3 = new Token();
        $aliceToken3->setToken('refreshtoken3');
        $aliceToken3->setInfo('alice refreshtoken3 info3');
        $aliceToken3->setDetails('alice refreshtoken3 detail 3');
        $aliceToken3->setTokenType(2);
        $aliceToken3->setClient('client3');
        $aliceToken3->setExpirationAt($now->add(new DateInterval('P1D')));
        $aliceToken3->setIssuedAt($now);
        $alice->addToken($aliceToken3);

        $client1 = new Client();
        $client1->setApplicationType('web');
        $client1->setClientId('client_id_1');
        $client1->setClientIdIssuedAt($now->getTimestamp());
        $client1->setClientSecret('client_secret_1');
        $client1->setClientName('client_name_1');
        $client1->setClientSecretExpiresAt($now->getTimestamp() + 30000);
        $client1->setRedirectUris("client_redirect_uris_1");
        $client1->setRegistrationAccessToken('reg_token_1');
        $client1->setRegistrationClientUriPath('/reg_path_1');
        $alice->addTrustedClient($client1);

        $client2 = new Client();
        $client2->setApplicationType('web');
        $client2->setClientId('client_id_2');
        $client2->setClientIdIssuedAt($now->getTimestamp());
        $client2->setClientSecret('client_secret_2');
        $client2->setClientName('client_name_2');
        $client2->setClientSecretExpiresAt($now->getTimestamp() + 30000);
        $client2->setRedirectUris("client_redirect_uris_2");
        $client2->setRegistrationAccessToken('reg_token_2');
        $client2->setRegistrationClientUriPath('/reg_path_2');
        $alice->addTrustedClient($client2);


        $john = new Account();
        $john->setLogin("john");
        $john->setEnabled(true);
        $john->setCryptedPassword(create_hash("smithland"));
        $john->setName('John Smith');
        $john->setGivenName('John');
        $john->setFamilyName('Smith');
        $john->setNickname('Standard John Nickname');
        $john->setPreferredUsername('JohnPreferred');
        $john->setEmail('johm@smithco.com');
        $john->setEmailVerified(true);
        $john->setGender('male');
        $john->setBirthdate('2000-08-08');
        $john->setZoneinfo('America/Los Angeles');
        $john->setLocale('en');
        $john->setPhoneNumber('1-916-554-2342');
        $john->setPhoneNumberVerified(true);
        $john->setUpdatedAt(93453453);


        $provider1 = new Provider();
        $provider1->setName('Provider 1');
        $provider1->setKeyId('ID 1');
        $provider1->setIssuer('Issuer 1');
        $provider1->setUrl('URL 1');
        $provider1->setClientId('client ID 1');
        $provider1->setClientSecret('client Secret 1');
        $provider1->setClientIdIssuedAt(time());
        $provider1->setClientSecretExpiresAt(time() + (60 * 5));
        $provider1->setRegistrationAccessToken('reg token 1');
        $provider1->setRegistrationClientUri('reg uri 1');
        $provider1->setAuthorizationEndpoint('auth ep 1');
        $provider1->setTokenEndpoint('token ep 1');
        $provider1->setUserinfoEndpoint('user ep 1');
        $provider1->setJwksUri('jwks uri 1');

        $provider2 = new Provider();
        $provider2->setName('Provider 2');
        $provider2->setKeyId('ID 2');
        $provider2->setIssuer('Issuer 2');
        $provider2->setUrl('URL 2');
        $provider2->setClientId('client ID 2');
        $provider2->setClientSecret('client Secret 2');
        $provider2->setClientIdIssuedAt(time());
        $provider2->setClientSecretExpiresAt(time() + (60 * 5));
        $provider2->setRegistrationAccessToken('reg token 2');
        $provider2->setRegistrationClientUri('reg uri 2');
        $provider2->setAuthorizationEndpoint('auth ep 2');
        $provider2->setTokenEndpoint('token ep 2');
        $provider2->setUserinfoEndpoint('user ep 2');
        $provider2->setJwksUri('jwks uri 2');


        $requestFile1 = new RequestFile();
        $requestFile1->setFileid("file_id_001");
        $requestFile1->setJwt('jwt_001');
        $requestFile1->setRequest('request_001');
        $requestFile1->setType(0);

        $requestFile2 = new RequestFile();
        $requestFile2->setFileid("file_id_002");
        $requestFile2->setJwt('jwt_002');
        $requestFile2->setRequest('request_002');
        $requestFile2->setType(1);

        $requestFile3 = new RequestFile();
        $requestFile3->setFileid("file_id_003");
        $requestFile3->setJwt('jwt_003');
        $requestFile3->setRequest('request_003');
        $requestFile3->setType(1);

        self::$em->persist($alice);
        self::$em->persist($bob);
        self::$em->persist($john);
        self::$em->persist($aliceToken1);
        self::$em->persist($aliceToken2);
        self::$em->persist($aliceToken3);
        self::$em->persist($client1);
        self::$em->persist($client2);
        self::$em->persist($provider1);
        self::$em->persist($provider2);
        self::$em->persist($requestFile1);
        self::$em->persist($requestFile2);
        self::$em->persist($requestFile3);
        self::$em->flush();
    }


    protected function tearDown(): void
    {
//        echo "tearDown--" . "\n";
        self::$em->clear();
        parent::tearDown(); // TODO: Change the autogenerated stub

    }

    public static function setUpBeforeClass(): void
    {
//        echo "setUpBeforeClass--" . "\n";

        self::$em = DbEntity::getInstance()->getEntityManager();
        self::$tool = new \Doctrine\ORM\Tools\SchemaTool(self::$em);
        self::$classes = array(
            self::$em->getClassMetadata("Account"),
            self::$em->getClassMetaData("Provider"),
            self::$em->getClassMetaData("Client"),
            self::$em->getClassMetaData("RequestFile"),
            self::$em->getClassMetaData("Token")
        );
    }

    public static function tearDownAfterClass(): void
    {

//        echo "teardownafterclass--" . "\n";

    }

    public function test_db_get_user(): void
    {
        $account = db_get_user('alice');
        $this->assertInstanceOf('Account', $account, 'Result is not Account class');
        $this->assertNotNull($account, 'Account is null');
        $this->assertEquals('alice', $account->getLogin(), 'login name is not alice');
    }

    public function test_db_get_user_not_exists(): void
    {
        $account = db_get_user('alice1');
        $this->assertNull($account, 'Account is not null');
    }

    public function test_db_get_account(): void
    {
        $account = db_get_account('alice');
        $this->assertInstanceOf('Account', $account, 'Result is not Account class');
        $this->assertNotNull($account, 'Account is null');
        $this->assertEquals('alice', $account->getLogin(), 'login name is not alice');
    }


    public function test_db_check_credential_with_migration(): void
    {
        $status = db_check_credential('alice', 'wonderland');
        $this->assertEquals(true, $status);
        $status2 = db_check_credential('alice', 'wonderland');
        $this->assertEquals(true, $status2);
    }

    public function test_db_check_credential_with_no_migration(): void
    {
        $status = db_check_credential('john', 'smithland');
        $this->assertEquals(true, $status);
    }

    public function test_db_check_credential_array_with_migration(): void
    {
        $status = db_check_credential_array('alice', 'wonderland');
        $this->assertEquals(true, $status);
        $status2 = db_check_credential_array('alice', 'wonderland');
        $this->assertEquals(true, $status2);
    }

    public function test_db_check_credential_array_with_no_migration(): void
    {
        $status = db_check_credential_array('john', 'smithland');
        $this->assertEquals(true, $status);
    }

    public function test_db_find_token(): void
    {
        $token = db_find_token('accesstoken2');
        $this->assertNotNull($token, "Access Token is null");
        $this->assertInstanceOf('Token', $token, 'Result is not a Token');
        $this->assertInstanceOf('Account', $token->getAccount(), 'Token account is not Account');
        $this->assertEquals('alice', $token->getAccount()->getLogin(), "Account is not Alice");
    }

    public function test_db_find_token_null(): void
    {
        $token = db_find_token('token3');
        $this->assertNull($token, 'Token is not null');
    }

    public function test_db_find_auth_code(): void
    {
        $token = db_find_auth_code('code1');
        $this->assertNotNull($token, "Auth Code is null");
        $this->assertInstanceOf('Token', $token, 'Result is not a Token');
        $this->assertInstanceOf('Account', $token->getAccount(), 'Token account is not Account');
        $this->assertEquals('alice', $token->getAccount()->getLogin(), "Account is not Alice");
    }

    public function test_db_find_refresh_token(): void
    {
        $token = db_find_refresh_token('refreshtoken3');
        $this->assertNotNull($token, "Refresh Token is null");
        $this->assertInstanceOf('Token', $token, 'Result is not a Token');
        $this->assertInstanceOf('Account', $token->getAccount(), 'Token account is not Account');
        $this->assertEquals('alice', $token->getAccount()->getLogin(), "Account is not Alice");
    }

    public function test_db_save_token(): void
    {
        $now = new DateTime();
        db_save_token('token4', 1, 'alice', 'client4', $now, $now->add(new DateInterval('P1D')), array('k1' => 'v1', 'k2' => 'v2'), 'details');
        $token = db_find_access_token('token4');
        $this->assertNotNull($token, 'Newly inserted token not found');
        $this->assertInstanceOf('Token', $token, 'Token is not Token class');
        $this->assertEquals('token4', $token->getToken());
    }

    public function test_db_get_user_tokens(): void
    {
        $tokens = db_get_user_tokens('alice');
        $this->assertNotNull($tokens, 'No user tokens returned');
        $this->assertEquals(3, count($tokens), 'Expected 3 tokens');
    }

    private function impl_test_db_get_user_token($user, $token_name): Token
    {
        $token = db_get_user_token($user, $token_name);
        $this->assertNotNull($token, 'No user token returned');
        $this->assertEquals($token_name, $token->getToken(), 'Unexpected token name');
        return $token;

    }

    public function test_db_get_user_token(): void
    {
        $this->impl_test_db_get_user_token('alice', 'accesstoken2');
    }

    public function test_db_delete_user_token(): void
    {

        $account = db_get_user('alice');
        $this->assertEquals(3, count($account->getTokens()), 'User should have 3 tokens');
        db_delete_user_token('alice', 'accesstoken2');
        $tokens = db_get_user_tokens('alice');
        $this->assertEquals(2, count($tokens), 'User token not deleted.');
        $this->assertEquals(2, count($account->getTokens()), 'User should have 2 tokens after deletion');
    }

    public function test_db_save_user_token(): void
    {
        $issue_at = new DateTime();
        $expiration_at = $issue_at->add(new DateInterval('PT300S'));
        $fields = array('client' => 'client1',
            'issued_at' => $issue_at,
            'expiration_at' => $expiration_at,
            'token' => 'acess_token_foo',  // use different name on purpose to make sure this is not set
            'details' => 'details for foo ',
            'token_type' => 1,
            'info' => json_encode(array('k' => 'foo'))
        );
        $status = db_save_user_token('alice', 'token_foo', $fields);
        $this->assertTrue($status, 'Failed inserted user token');
        $this->impl_test_db_get_user_token('alice', 'token_foo');
    }


    public function test_db_get_user_trusted_clients(): void
    {
        $trustedClients = db_get_user_trusted_clients('alice');
        $this->assertNotNull($trustedClients, 'No trusted clients found');
        $this->assertEquals(2, count($trustedClients), 'Unexpected number of trusted clients');
    }

    public function test_db_get_user_trusted_client(): void
    {
        $trustedClient = db_get_user_trusted_client('alice', 'client_id_1');
        $this->assertNotNull($trustedClient, 'Trusted client not found');
        $this->assertEquals('client_secret_1', $trustedClient->getClientSecret(), 'client secret mismatch');
    }

    public function test_db_get_user_trusted_client_not_found(): void
    {
        $trustedClient = db_get_user_trusted_client('alice', 'client1');
        $this->assertNull($trustedClient, 'Unexpected Trusted client not found');
    }

    public function test_db_delete_user_trusted_client(): void
    {
        $trustedClient = db_get_user_trusted_client('alice', 'client_id_1');
        $this->assertNotNull($trustedClient, 'Trusted client not found');
        $this->assertEquals('client_secret_1', $trustedClient->getClientSecret(), 'client secret mismatch');
        db_delete_user_trusted_client('alice', 'client_id_1');
        $trustedClient = db_get_user_trusted_client('alice', 'client_id_1');
        $this->assertNull($trustedClient, 'Unexpected Trusted client not found');

    }

    public function test_db_save_user_trusted_client(): void
    {
        $trustedClient = db_get_user_trusted_client('bob', 'client_id_1');
        $this->assertNull($trustedClient, 'Unexpected Trusted client not found');
        db_save_user_trusted_client('bob', 'client_id_1');
        $trustedClient = db_get_user_trusted_client('bob', 'client_id_1');
        $this->assertNotNull($trustedClient, 'Trusted client not found');
        $this->assertEquals('client_secret_1', $trustedClient->getClientSecret(), 'client secret mismatch');
    }

    public function test_db_get_accounts(): void
    {
        $accounts = db_get_accounts();
        $this->assertNotNull($accounts, 'Unexpected Trusted client not found');
        $this->assertEquals(3, count($accounts), 'account amount mismatch');
    }


    public function test_db_save_account(): void
    {
        db_save_account('john', array(
            'name' => 'Jane Doe',
            'given_name' => 'Jane',
            'family_name' => 'Doe',
            'nickname' => 'Standard Jane Doe',
            'email' => 'jane.doe@test.com',
            'birthdate' => '2010-01-01',
            'gender' => 'female',
            'zoneinfo' => 'Paris/France',
            'locale' => 'fr',
            'phone_number' => '123546789'
        ));
        $account = db_get_account('john');
        $this->assertNotNull($account, 'Unexpected Trusted client not found');
        $this->assertEquals('Jane Doe', $account->getName(), 'account name mismatch');
        $this->assertEquals('Jane', $account->getGivenName(), 'account given_name mismatch');
        $this->assertEquals('Doe', $account->getFamilyName(), 'account family_name mismatch');
        $this->assertEquals('Standard Jane Doe', $account->getNickname(), 'account nickname mismatch');
        $this->assertEquals('jane.doe@test.com', $account->getEmail(), 'account email mismatch');
        $this->assertEquals('2010-01-01', $account->getBirthdate(), 'account birthdate mismatch');
        $this->assertEquals('female', $account->getGender(), 'account gender mismatch');
        $this->assertEquals('Paris/France', $account->getZoneinfo(), 'account zoneinfo mismatch');
        $this->assertEquals('fr', $account->getLocale(), 'account locale mismatch');
        $this->assertEquals('123546789', $account->getPhoneNumber(), 'account phone_number mismatch');
    }

    public function test_db_get_providers(): void
    {
        $providers = db_get_providers();
        $this->assertNotNull($providers, 'No providers found');
        $this->assertEquals(2, count($providers), 'Provider amount mismatch');
    }

    public function test_db_get_provider(): void
    {
        $provider = db_get_provider('Provider 1');
        $this->assertNotNull($provider, 'Provider not found');
        $this->assertEquals('URL 1', $provider->getUrl(), 'Provider URL mismatch');
    }


    public function test_db_get_provider_by_url(): void
    {
        $provider = db_get_provider_by_url('URL 2');
        $this->assertNotNull($provider, 'Provider not found');
        $this->assertEquals('Provider 2', $provider->getName(), 'Provider name mismatch');
    }

    public function test_db_get_provider_by_issuer(): void
    {
        $provider = db_get_provider_by_issuer('Issuer 1');
        $this->assertNotNull($provider, 'Provider Issuer found');
        $this->assertEquals('Provider 1', $provider->getName(), 'Provider name mismatch');
    }

    public function test_db_get_provider_by_key_id(): void
    {
        $provider = db_get_provider_by_key_id('ID 2');
        $this->assertNotNull($provider, 'Provider Issuer found');
        $this->assertEquals('Provider 2', $provider->getName(), 'Provider name mismatch');
    }

    public function test_db_delete_provider(): void
    {
        $provider_name = 'Provider 2';
        $provider = db_get_provider($provider_name);
        $this->assertNotNull($provider, 'Provider Issuer found');
        $status = db_delete_provider($provider_name);
        $this->assertEquals(true, $status, 'Provider not delete');
        $provider = db_get_provider($provider_name);
        $this->assertNull($provider, 'Provider not deleted');
    }

    public function test_db_save_provider(): void
    {

        $provider_name = 'Provider 2';
        $now = time();
        $expires = $now + (60*60*12*7*52);
        db_save_provider($provider_name, array(
            'url' => 'new URL',
            'issuer' => 'new issuer',
            'client_id' => 'new client_id',
            'client_secret' => 'new client_secret',
            'client_id_issued_at' => $now,
            'client_secret_expires_at' => $expires,
            'x509_uri' => 'new x509_uri',
            'claims_supported' => 'new claims_supported',
            'request_uri_parameter_supported' => 'new request_uri_parameter_supported',
            'userinfo_encryption_alg_values_supported' => 'new userinfo_encryption_alg_values_supported',
            'token_endpoint' => 'new token_endpoint'
        ));
        $provider = db_get_provider($provider_name);
        $this->assertNotNull($provider, 'Provider not found');
        $this->assertEquals('new URL', $provider->getUrl(), 'Provider URL mismatch');
        $this->assertEquals('new issuer', $provider->getIssuer(), 'Provider issuer mismatch');
        $this->assertEquals('new client_id', $provider->getClientId(), 'Provider client_id mismatch');
        $this->assertEquals('new client_secret', $provider->getClientSecret(), 'Provider client_secret mismatch');
        $this->assertEquals($now, $provider->getClientIdIssuedAt(), 'Provider client_id_issued_at mismatch');
        $this->assertEquals($expires, $provider->getClientSecretExpiresAt(), 'Provider client_secret_expires_at mismatch');
        $this->assertEquals('new x509_uri', $provider->getX509Uri(), 'Provider x509_uri mismatch');
        $this->assertEquals('new claims_supported', $provider->getClaimsSupported(), 'Provider claims_supported mismatch');
        $this->assertEquals('new request_uri_parameter_supported', $provider->getRequestUriParameterSupported(), 'Provider request_uri_parameter_supported mismatch');
        $this->assertEquals('new userinfo_encryption_alg_values_supported', $provider->getUserinfoEncryptionAlgValuesSupported(), 'Provider userinfo_encryption_alg_values_supported mismatch');
        $this->assertEquals('new token_endpoint', $provider->getTokenEndpoint(), 'Provider token_endpoint mismatch');
    }


    public function test_db_get_clients(): void
    {
        $clients = db_get_clients();
        $this->assertNotNull($clients, 'No clients found');
        $this->assertEquals(2, count($clients), 'Unexpected number of clients');
    }


    public function test_db_get_client(): void
    {
        $client = db_get_client('client_id_1');
        $this->assertNotNull($client, 'Client not found');
        $this->assertEquals('client_id_1', $client->getClientId(), 'Client  client_id mismatch');
        $this->assertEquals('client_secret_1', $client->getClientSecret(), 'Client  client_secret mismatch');
        $this->assertEquals('client_name_1', $client->getClientName(), 'Client name  mismatch');
        $this->assertEquals('client_redirect_uris_1', $client->getRedirectUris(), 'Client redirect_uris  mismatch');
        $this->assertEquals('reg_token_1', $client->getRegistrationAccessToken(), 'Client reg access token  mismatch');
        $this->assertEquals('/reg_path_1', $client->getRegistrationClientUriPath(), 'Client reg path  mismatch');
    }

    public function test_db_get_client_by_registration_token(): void
    {
        $client = db_get_client_by_registration_token('reg_token_1');
        $this->assertNotNull($client, 'Client not found');
        $this->assertEquals('client_id_1', $client->getClientId(), 'Client  client_id mismatch');
        $this->assertEquals('client_secret_1', $client->getClientSecret(), 'Client  client_secret mismatch');
        $this->assertEquals('client_name_1', $client->getClientName(), 'Client name  mismatch');
        $this->assertEquals('client_redirect_uris_1', $client->getRedirectUris(), 'Client redirect_uris  mismatch');
        $this->assertEquals('reg_token_1', $client->getRegistrationAccessToken(), 'Client reg access token  mismatch');
        $this->assertEquals('/reg_path_1', $client->getRegistrationClientUriPath(), 'Client reg path  mismatch');
    }

    public function test_db_get_client_by_registration_uri_path(): void
    {
        $client = db_get_client_by_registration_uri_path('/reg_path_1');
        $this->assertNotNull($client, 'Client not found');
        $this->assertEquals('client_id_1', $client->getClientId(), 'Client  client_id mismatch');
        $this->assertEquals('client_secret_1', $client->getClientSecret(), 'Client  client_secret mismatch');
        $this->assertEquals('client_name_1', $client->getClientName(), 'Client name  mismatch');
        $this->assertEquals('client_redirect_uris_1', $client->getRedirectUris(), 'Client redirect_uris  mismatch');
        $this->assertEquals('reg_token_1', $client->getRegistrationAccessToken(), 'Client reg access token  mismatch');
        $this->assertEquals('/reg_path_1', $client->getRegistrationClientUriPath(), 'Client reg path  mismatch');
    }

    public function test_db_save_client(): void
    {

        $client_Id = 'client_id_1';
        $now = time();
        $expires = $now + (60*60*12*7*52);
        db_save_client($client_Id, array(
            'client_secret' => 'new client_secret',
            'client_id_issued_at' => $now,
            'client_secret_expires_at' => $expires,
            'client_secret' => 'new client_secret',
            'registration_access_token' => 'new registration_access_token',
            'registration_client_uri_path' => 'new registration_client_uri_path',
            'contacts' => 'new contacts',
            'post_logout_redirect_uris' => 'new post_logout_redirect_uris',
            'policy_uri' => 'new policy_uri',
            'x509_uri' => 'new x509_uri',
            'x509_encryption_uri' => 'new x509_encryption_uri',
            'userinfo_signed_response_alg' => 'new userinfo_signed_response_alg',
            'userinfo_encrypted_response_alg' => 'new userinfo_encrypted_response_alg'
        ));
        $client = db_get_client($client_Id);
        $this->assertNotNull($client, 'Client not found');
        $this->assertEquals('new client_secret', $client->getClientSecret(), 'Client client_secret mismatch');
        $this->assertEquals($now, $client->getClientIdIssuedAt(), 'Client client_id_issued_at mismatch');
        $this->assertEquals($expires, $client->getClientSecretExpiresAt(), 'Client client_secret_expires_at mismatch');
        $this->assertEquals('new registration_access_token', $client->getRegistrationAccessToken(), 'Client registration_access_token mismatch');
        $this->assertEquals('new registration_client_uri_path', $client->getRegistrationClientUriPath(), 'Client registration_client_uri_path mismatch');
        $this->assertEquals('new contacts', $client->getContacts(), 'Client contacts mismatch');
        $this->assertEquals('new post_logout_redirect_uris', $client->getPostLogoutRedirectUris(), 'Client post_logout_redirect_uris mismatch');
        $this->assertEquals('new policy_uri', $client->getPolicyUri(), 'Client policy_uri mismatch');
        $this->assertEquals('new x509_uri', $client->getX509Uri(), 'Client x509_uri mismatch');
        $this->assertEquals('new x509_encryption_uri', $client->getX509EncryptionUri(), 'Client x509_encryption_uri mismatch');
        $this->assertEquals('new userinfo_signed_response_alg', $client->getUserinfoSignedResponseAlg(), 'Client userinfo_signed_response_alg mismatch');
        $this->assertEquals('new userinfo_encrypted_response_alg', $client->getUserinfoEncryptedResponseAlg(), 'Client userinfo_encrypted_response_alg mismatch');
    }

    public function test_db_delete_client(): void
    {
        $client_id = 'client_id_2';
        $client = db_get_client($client_id);
        $this->assertNotNull($client, 'Client ID found');
        $status = db_delete_client($client_id);
        $this->assertEquals(true, $status, 'Client not deleted');
        $client = db_get_client($client_id);
        $this->assertNull($client, 'Client not deleted');
    }

    public function test_db_check_client_credential(): void
    {
        $status = db_check_client_credential('client_id_1', 'client_secret_1');
        $this->assertEquals(true, $status, 'Client credentials not authenticated');
    }

    public function test_db_check_client_credential_fail(): void
    {
        $status = db_check_client_credential('client_id_1', 'client_secret_2');
        $this->assertEquals(false, $status, 'Client credentials authenticated but should be false');
    }

    public function test_db_get_request_file(): void
    {
        $requestfile = db_get_request_file('file_id_001');
        $this->assertNotNull($requestfile, 'RequestFile not found');
        $this->assertEquals('jwt_001', $requestfile->getJwt(), 'RequestFile jwt  mismatch');
        $this->assertEquals('request_001', $requestfile->getRequest(), 'RequestFile request  mismatch');
        $this->assertEquals(0, $requestfile->getType(), 'RequestFile  type mismatch');
    }

    public function test_db_get_request_file_fail(): void
    {
        $requestfile = db_get_request_file('file_id_004');
        $this->assertNull($requestfile, 'Unexpected RequestFile found');
    }

    public function test_db_save_request_file_new(): void
    {
        $status = db_save_request_file('file_id_004', array(
            'request' => 'request_004',
            'jwt' => 'jwt_004',
            'type' => 1
        ));
        $this->assertTrue($status, 'RequestFile not saved');

        $requestfile = db_get_request_file('file_id_004');
        $this->assertNotNull($requestfile, 'RequestFile not found');
        $this->assertEquals('jwt_004', $requestfile->getJwt(), 'RequestFile jwt  mismatch');
        $this->assertEquals('request_004', $requestfile->getRequest(), 'RequestFile request  mismatch');
        $this->assertEquals(1, $requestfile->getType(), 'RequestFile  type mismatch');
    }

    public function test_db_save_request_file_old(): void
    {
        $status = db_save_request_file('file_id_002', array(
            'request' => 'request_004',
            'jwt' => 'jwt_004',
            'type' => 1
        ));
        $this->assertTrue($status, 'RequestFile not saved');
        $requestfile = db_get_request_file('file_id_002');
        $this->assertNotNull($requestfile, 'RequestFile not found');
        $this->assertEquals('jwt_004', $requestfile->getJwt(), 'RequestFile jwt  mismatch');
        $this->assertEquals('request_004', $requestfile->getRequest(), 'RequestFile request  mismatch');
        $this->assertEquals(1, $requestfile->getType(), 'RequestFile  type mismatch');

    }
}