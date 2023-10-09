<?php
include 'vendor/autoload.php';
$_ENV["GOOGLE_CLIENT_ID"] = "344988475329-msrld6b3e3q90u3ek9pggsphoa7bjhct.apps.googleusercontent.com";
$_ENV["GOOGLE_CLIENT_SECRET"] = "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";
$_ENV["GOOGLE_REDIRECT_URI"] = "http://localhost/xtube/external_login.php?login_provider=google";

$_ENV["FACEBOOK_APP_ID"] = "1035521354255621";
$_ENV["FACEBOOK_APP_SECRET"] = "e238a2b9ab907f8c061cde791b5ab231";
$_ENV["FACEBOOK_REDIRECT_URI"] = "http://localhost/xtube/external_login.php?login_provider=facebook";
class Auth
{


    // Google auth
    private static $google_client = null;

    // Facebook auth
    private static $facebook_client = null;


    /**
     * @return Google_Client
     */
    public static function get_google_client()
    {
        if (self::$google_client != null) {
            return self::$google_client;
        }

        // create Client Request to access Google API
        $client = new Google\Client();
        $client->setClientId($_ENV["GOOGLE_CLIENT_ID"]);
        $client->setClientSecret($_ENV["GOOGLE_CLIENT_SECRET"]);
        $client->setRedirectUri($_ENV["GOOGLE_REDIRECT_URI"]);
        $client->addScope("email");
        $client->addScope("profile");

        self::$google_client = $client;

        return $client;
    }

    public static function get_google_login_url()
    {
        $client = Auth::get_google_client();
        return $client->createAuthUrl();
    }

    public static function handle_google_login($code)
    {
        $client = Auth::get_google_client();
        $token = $client->fetchAccessTokenWithAuthCode($code);
        $client->setAccessToken($token['access_token']);

        // get profile info
        $google_oauth = new Google\Service\Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();

        return [
            "google_id" => $google_account_info->id,
            "name" => $google_account_info->name,

            "email" => $google_account_info->email,
        ];
    }

    /**
     * @return Facebook\Facebook
     */
    public static function get_facebook_client()
    {
        if (self::$facebook_client != null) {
            return self::$facebook_client;
        }

        $fb = new Facebook\Facebook([
            'app_id' => $_ENV["FACEBOOK_APP_ID"],
            'app_secret' => $_ENV["FACEBOOK_APP_SECRET"],
            'default_graph_version' => 'v17.0',
        ]);

        self::$facebook_client = $fb;

        return $fb;
    }

    public static function get_facebook_login_url()
    {
        $fb = self::get_facebook_client();

        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl($_ENV["FACEBOOK_REDIRECT_URI"], $permissions);

        return $loginUrl;
    }

    public static function handle_facebook_login($code)
    {
        $fb = self::get_facebook_client();

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (!isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Get profile

        $fb->setDefaultAccessToken($accessToken);

        try {
            $profile_request = $fb->get('/me?fields=name,first_name,last_name,email');
            $profile = $profile_request->getGraphUser();
            $fbid = $profile->getProperty('id');           // To Get Facebook ID
            $fbfullname = $profile->getProperty('name');   // To Get Facebook full name
            $fbemail = $profile->getProperty('email');    //  To Get Facebook email
            //$fbpic = "<img src='" . $picture['url'] . "' class='img-rounded'/>";
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return [
            "fb_id" => $fbid,
            "name" => $fbfullname,
            "email" => $fbemail,
        ];
    }

}
