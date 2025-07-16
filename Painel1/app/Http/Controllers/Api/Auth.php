<?php

namespace App\Http\Controllers\Api;

use App\Models\Auth as ModelsAuth;
use App\Models\User;
use Core\Routing\Request;
use Core\Utils\Email;
use Core\Utils\OAuth\Discord;
use Firebase\JWT\JWT;
use Google\Client as GoogleClient;
use GuzzleHttp\Client;

class Auth extends Api
{
    public function login()
{
    try {
        $post = $this->request->post();
        $email = $post['email'] ?? '';
        $password = $post['password'] ?? '';

        if (empty($email) || empty($password)) {
            return [
                'state' => false,
                'message' => "Informe seu email e senha para entrar"
            ];
        }

        if (request_limit("appApiAuth", 10, 60 * 2)) {
            return [
                'state' => false,
                'message' => "Você atingiu o limite de requisições. Por favor, aguarde 2 minutos para tentar novamente!"
            ];
        }

        $save = (!empty($post['save']) ? true : false);
        $auth = new ModelsAuth();
        $login = $auth->login($email, $password, $save, $this->message);

        if (!$login) {
            return [
                'state' => false,
                'message' => $this->message->getText()
            ];
        }

        $token = $this->generateToken($email);
        $this->session->set('token', $token);

        if ($this->session->has('clientUser', $token)) {
            redirect('/app/lobby');
            exit;
        }

        return [
            'state' => true,
            'message' => 'Login efetuado com sucesso',
            'token' => $token,
        ];
    } catch (\Throwable $e) {
        return [
            'state' => false,
            'message' => 'Erro interno: ' . $e->getMessage(),
            'debug' => true,
        ];
    }
}


    public function register(): array
    {
        $post = $this->request->post();

        if ($_ENV['APP_REGISTER'] == 'false') {
            return [
                'state' => false,
                'message' => 'Registro desabilitado, tente novamente em alguns instantes.'
            ];
        }

        $check = $post;
        unset($check['referenced']);
        if (in_array('', $check)) {
            return [
                'state' => false,
                'message' => 'Informe seus dados para criar sua conta.'
            ];
        }

        if (request_limit("appApiAuth", 3, 60 * 5)) {
            return [
                'state' => false,
                'message' => "Você já efetuou 3 tentativas, esse é o limite. Por favor, aguarde 5 minutos para tentar novamente!"
            ];
        }

        if ($_ENV['APP_CAPTCHA'] == 'true' && !$this->verifyCaptcha($post['g-recaptcha-response'] ?? '')) {
            return [
                'state' => false,
                'message' => 'Captcha inválido, verifique as informações e tente novamente'
            ];
        }

        $auth = new ModelsAuth();
        $user = new User();
        $user->bootstrap(
            $post['fname'],
            $post['lname'],
            $post['email'],
            $post['password'],
            $post['referenced'] ?? '',
        );

        if (!$auth->register($user, $this->message)) {
            return [
                'state' => false,
                'message' => $this->message->before("Ooops! ")->getText()
            ];
        }

        $token = $this->generateToken($user->email);
        $this->session->set('token', $token);

		if($this->session->has('clientUser', $token)) {
            redirect('/app/lobby');
            exit;
        }
		
        return [
            'state' => true,
            'message' => 'Registro efetuado com sucesso',
            'token' => $token
        ];
    }

    public function setAuthGoogle(Request $request)
    {
        //post vars
        $postVars = $request->post();
        $credential = $postVars['credential'] ?? '';
        $csrf = $postVars['g_csrf_token'] ?? '';

        if (!isset($credential) or !isset($csrf)) {
            $this->message->warning("Ocorreu um erro na solicitação de login do google, tente novamente.")->flash();
            redirect('/');
        }

        $cookie = $_COOKIE['g_csrf_token'] ?? '';
        if ($csrf !== $cookie) {
            $this->message->warning("Ocorreu um erro na solicitação de login do google, tente novamente.")->flash();
            redirect('/');
        }

        $client = new GoogleClient([
            'client_id' => $_ENV['GOOGLE_CLIENT_ID']
        ]);

        do {
            $attempt = 0;
            try {
                $obGoogle =  $client->verifyIdToken($credential);
                $retry = false;
            } catch (\Firebase\JWT\BeforeValidException $e) {
                $attempt++;
                $retry = $attempt < 2;
            }
        } while ($retry);

        if (!isset($obGoogle['email'])) {
            $this->message->warning("Ocorreu um erro na solicitação de login do google, tente novamente.")->flash();
            redirect('/');
        }

        $obUser = User::where('email', '=', $obGoogle['email'])->first();
        if (!$obUser) {
            if ($_ENV['APP_REGISTER'] == 'false') {
                $this->message->warning("Registro desabilitado, tente novamente em alguns instantes.")->flash();
                redirect('/');
            }

            $user = User::create([
                'first_name' => $obGoogle['given_name'],
                'last_name' => $obGoogle['family_name'],
                'email' => $obGoogle['email'],
                'password' => password_hash(str_hash(16), PASSWORD_DEFAULT),
                'google_id' => $obGoogle['nbf'],
                'u_hash' => str_hash(16),
                'p_hash' => md5(str_hash(20)),
                'reference' => str_hash(25),
                'photo' => 'avatars/default.png',
                'status' => 'confirmed'
            ]);

            if (!$user) {
                $this->message->warning("Ocorreu um erro de comunicação com o google, tente novamente ou chame um administrador.")->flash();
                redirect('/');
            }

            (new User())->set_session_log($user);

            //generate token
            $token = $this->generateToken($user->email);

            $this->session->set('token', $token);
            $this->session->set('uid', $user->id);
            $this->message->primary("Olá <b>{$user->first_name}</b>, seja bem-vindo(a) a {$_ENV['APP_NAME']}.")->flash();
            redirect('/');
        }

        if (!$obUser->active) {
            $this->message->warning(__('auth.banned'))->flash();
            redirect('/');
        }

        (new User())->set_session_log($obUser);

        $token = $this->generateToken($obUser->email);

        $this->session->set('uid', $obUser->id);
        $this->message->primary("Olá {$obUser->first_name}, seja bem-vindo(a) de volta.")->flash();
        $this->session->set('token', $token);

        redirect('/');
    }

    public function setAuthDiscord(Request $request): void
    {
        $queryParams = $request->get();
        $credential = $queryParams['code'] ?? '';

        $client = new Discord($_ENV['DISCORD_CLIENT_ID'], $_ENV['DISCORD_CLIENT_SECRET']);

        if (!$credential) {
            $client->login();
        }

        $obDiscord = $client->verifyIdToken($credential);
        if (!$obDiscord) {
            $this->message->warning("Ocorreu um erro na solicitação de login do discord, tente novamente.")->flash();
            redirect('/');
        }

        $obUser = User::where('discord_id', '=', $obDiscord->id)->first();
        if (!$obUser) {
            if ($_ENV['APP_REGISTER'] == 'false') {
                $this->message->warning("Registro desabilitado, tente novamente em alguns instantes.")->flash();
                redirect('/');
            }
            $user = User::create([
                'first_name' => 'User',
                'last_name' => $obDiscord->discriminator,
                'email' => str_hash(11) . '@discord.com',
                'password' => password_hash(str_hash(16), PASSWORD_DEFAULT),
                'discord_id' => $obDiscord->id,
                'u_hash' => str_hash(16),
                'p_hash' => md5(str_hash(20)),
                'reference' => str_hash(25),
                'photo' => 'avatars/default.png',
                'status' => 'confirmed'
            ]);

            if (!$user) {
                $this->message->warning("Ocorreu um erro de comunicação com o discord, tente novamente ou chame um administrador.")->flash();
                redirect('/');
            }

            (new User())->set_session_log($user);

            $token = $this->generateToken($user->email);

            $this->session->set('uid', $user->id);
            $this->message->primary("Olá <b>{$user->first_name}</b>, seja bem-vindo(a) a {$_ENV['APP_NAME']}.")->flash();
            $this->session->set('token', $token);
            redirect('/');
        }

        if (!$obUser->active) {
            $this->message->warning(__('auth.banned'))->flash();
            redirect('/');
        }

        (new User())->set_session_log($obUser);

        $token = $this->generateToken($obUser->email);

        $this->session->set('uid', $obUser->id);
        $this->message->primary("Olá {$obUser->first_name}, seja bem-vindo(a) de volta.")->flash();
        $this->session->set('token', $token);

        redirect('/');
    }

    public function setMailConfirm(Request $request, string $hash)
    {
        $hash = trim(strip_tags($hash));

        if ($hash == '') {
            http_response_code(403);
            exit;
        }

        if (request_limit("mailConfirm", 5, 60 * 2)) {
            return [
                'state' => false,
                'message' => __('auth.throttle', ['seconds' => ($this->session->mailConfirm->time - time())])
            ];
        }

        $email = base64_decode($hash);
        $user = User::where('email', $email)->first();
        $user->status = 'confirmed';
        if (!$user->save()) {
            return [
                'state' => false,
                'message' => 'fail!'
            ];
        }

        $this->session->set('uid', $user->id);
        $this->session->unset('mailConfirm');
        redirect('/');
    }

    public function forget(Request $request)
    {
        $post = $request->post();

        if (!$post['email'] || ($_ENV['APP_CAPTCHA'] == 'true' && $post['g-recaptcha-response'] == '')) {
            return [
                'state' => false,
                'message' => __('auth.all_fields_required')
            ];
        }

        if (request_limit("appApiAuth", 3, 60 * 5)) {
            return [
                'state' => false,
                'message' => "Você já efetuou 3 tentativas, esse é o limite. Por favor, aguarde 5 minutos para tentar novamente!"
            ];
        }

        if ($_ENV['APP_CAPTCHA'] == 'true') {
            if (!$this->verifyCaptcha($post['g-recaptcha-response'])) {
                return [
                    'state' => false,
                    'message' => __('auth.invalid_captcha')
                ];
            }
        }

        $user = User::where('email', $post['email'])->first();
        if (!$user) {
            return [
                'state' => false,
                'message' => 'Não encontramos nenhum usuário com este e-mail.'
            ];
        }

        if (request_limit("forget", 1, 60 * 2)) {
            return [
                'state' => false,
                'message' => 'Você já solicitou uma nova senha. Por favor, aguarde alguns instantes.'
            ];
        }

        $user->forget = str_hash(16);
        $user->save();

        //send email
        $this->sendForgetEmail($user);

        return [
            'state' => true,
            'message' => __('auth.forget_email_sent', ['email' => $user->email])
        ];
    }

    public function forgetConfirm(Request $request)
    {
        $post = $request->post();

        //check is fields are empty
        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => __('auth.all_fields_required')
            ];
        }

        //check captcha
        if ($_ENV['APP_CAPTCHA'] == 'true') {
            if (!$this->verifyCaptcha($post['g-recaptcha-response'])) {
                return [
                    'state' => false,
                    'message' => __('auth.invalid_captcha')
                ];
            }
        }

        //check if user exists
        $user = User::where('forget', $post['hash'])->first();
        if (!$user) {
            return [
                'state' => false,
                'message' => 'Dados inválidos, atualize a página e tente novamente.'
            ];
        }

        //check if password is identical
        if ($post['password'] != $post['confirm-password']) {
            return [
                'state' => false,
                'message' => 'As senhas não conferem.'
            ];
        }

        $user->password = passwd($post['password']);
        $user->forget =  '';
        if (!$user->save()) {
            return [
                'state' => false,
                'message' => 'Não foi possível alterar a senha.'
            ];
        }

        //set flash message
        $this->message->primary('Pronto <b>' . $user->first_name . '</b> sua senha nova foi alterada com sucesso, você ja pode fazer login.')->flash();

        return [
            'state' => true,
            'message' => 'Senha alterada com sucesso!'
        ];
    }

    protected function verifyCaptcha($token)
    {
        $client = new Client();
        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => $_ENV['CAPTCHA_SECRET_KEY'],
                'response' => $token ?? '',
                'remoteip' => Request::ip()
            ]
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $data->success == 1;
    }

    protected function generateToken(string $email): string
    {
        return JWT::encode(
            ['email' => $email],
            $_ENV['JWT_KEY'] ?? '',
            'HS256'
        );
    }

    protected function sendForgetEmail(User $user)
    {
        $message = view("app.email.forget", [
            "first_name" => $user->first_name,
            "confirm_link" => url("auth/recuperar-senha/" . base64_encode($user->forget))
        ]);

        $mail = new Email();
        $mail->bootstrap(
            "Recuperar conta " . $_ENV['APP_NAME'],
            $message,
            $user->email,
            "{$user->first_name} {$user->last_name}"
        )->send();
    }

    public function flashLogin()
    {
        $post = $this->request->post();
        $email = $post['email'] ?? '';
        $password = $post['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->message->warning("Informe seu email e senha para entrar")->flash();
            redirect('/');
            return;
        }

        if (request_limit("appApiAuth", 5, 60 * 3)) {
            $this->message->warning("Você atingiu o limite de requisições. Por favor, aguarde 3 minutos para tentar novamente!")->flash();
            redirect('/');
            return;
        }

        if ($_ENV['APP_CAPTCHA'] == 'true' && !$this->verifyCaptcha($post['g-recaptcha-response'] ?? '')) {
            $this->message->warning("Captcha inválido, verifique as informações e tente novamente")->flash();
            redirect('/');
            return;
        }

        $save = (!empty($post['save']) ? true : false);
        $auth = new ModelsAuth();
        $login = $auth->login($email, $password, $save, $this->message);
        if (!$login) {
            $this->message->warning($this->message->getText())->flash();
            redirect('/');
            return;
        }

        $token = $this->generateToken($email);

        $this->session->set('token', $token);
        $this->message->warning('Login efetuado com sucesso')->flash();
        redirect('/app/lobby');
        //$token
    }

    public function flashRegister()
    {
        $post = $this->request->post();

        if ($_ENV['APP_REGISTER'] == 'false') {
            $this->message->warning("Registro desabilitado, tente novamente em alguns instantes.")->flash();
            redirect('/auth/cadastro');
            return;
        }

        $check = $post;
        unset($check['referenced']);
        if (in_array('', $check)) {
            $this->message->warning("Informe seus dados para criar sua conta.")->flash();
            redirect('/auth/cadastro');
            return;
        }

        if (request_limit("appApiAuth", 3, 60 * 3)) {
            $this->message->warning("Você atingiu o limite de requisições. Por favor, aguarde 3 minutos para tentar novamente!")->flash();
            redirect('/auth/cadastro');
            return;
        }

        if ($_ENV['APP_CAPTCHA'] == 'true' && !$this->verifyCaptcha($post['g-recaptcha-response'] ?? '')) {
            $this->message->warning("Captcha inválido, verifique as informações e tente novamente.")->flash();
            redirect('/auth/cadastro');
            return;
        }

        $auth = new ModelsAuth();
        $user = new User();
        $user->bootstrap(
            $post['fname'],
            $post['lname'],
            $post['email'],
            $post['password'],
            $post['referenced'] ?? '',
        );

        if (!$auth->register($user, $this->message)) {
            $this->message->warning($this->message->before("Ooops! ")->getText())->flash();
            redirect('/auth/cadastro');
            return;
        }

        $token = $this->generateToken($user->email);
        $this->session->set('token', $token);

        $this->message->success("Registro efetuado com sucesso")->flash();
        redirect('/app/lobby');
        return;
    }
}
