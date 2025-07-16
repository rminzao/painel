<?php

namespace Core\Utils\OAuth;

class Discord
{
  /** @var */
    protected $cliendId;

  /** @var */
    protected $cliendSecret;

    public function __construct(string $cliendId, string $cliendSecret)
    {
        $this->cliendId = $cliendId;
        $this->cliendSecret = $cliendSecret;
    }

    public function login()
    {
        $data = array(
        'client_id' => $this->cliendId,
        'redirect_uri' => url('api/auth/discord'),
        'response_type' => 'code',
        'scope' => 'identify guilds'
        );

        // Redirect the user to Discord's authorization page
        header('Location: https://discord.com/api/oauth2/authorize' . '?' . http_build_query($data));
        exit;
    }

    public function verifyIdToken(string $tokenId)
    {
        // Exchange the auth code for a token
        $verify = $this->request(
            'https://discord.com/api/oauth2/token',
            [
              'grant_type' => "authorization_code",
              'client_id' => $this->cliendId,
              'client_secret' => $this->cliendSecret,
              'redirect_uri' => url('api/auth/discord'),
              'code' => $tokenId
            ]
        );

        if (isset($verify->error)) {
            return false;
        }

        $token = $this->request('https://discord.com/api/users/@me', [], $verify->access_token);

        return $token;
    }

    public function logout(string $token)
    {
        $data = [
        'token' => $token,
        'token_type_hint' => 'access_token',
        'client_id' => $this->cliendId,
        'client_secret' => $this->cliendSecret,
        ];

        $ch = curl_init('https://discord.com/api/oauth2/token/revoke');
        curl_setopt_array($ch, array(
          CURLOPT_POST => true,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
          CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),
          CURLOPT_POSTFIELDS => http_build_query($data),
        ));
        $response = curl_exec($ch);
        return json_decode($response);
    }

    protected function request($url, $post = false, $token = '')
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query((array)$post));
        }

        $headers[] = 'Accept: application/json';

        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        return json_decode($response);
    }
}
