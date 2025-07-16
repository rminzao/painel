<?php

namespace App\Models;

use Core\Session;
use Core\Utils\Email;
use Core\Utils\Message;
use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'discord_id',
        'u_hash',
        'p_hash',
        'reference',
        'status',
    ];

    /**
     * @return null|User
     */
    public static function user(): ?User
    {
        $session = new Session();
        if (!$session->has("uid")) {
            return null;
        }

        return User::find($session->uid);
    }

    /**
     * log-out
     */
    public static function logout(): void
    {
        $session = new Session();
        $session->unset("uid");
    }

    /**
     * @param string $email
     * @param string $password
     * @param $message
     * @return User|null
     */
    public function attempt(string $email, string $password, &$message): ?User
    {
        if (!is_email($email)) {
            $message->warning("O e-mail informado não é válido");
            return null;
        }

        if (!is_passwd($password)) {
            $message->warning("A senha informada não é válida");
            return null;
        }

        $user = (new User())->findByEmail($email);
        if (!$user) {
            $message->error("O e-mail informado não está cadastrado");
            return null;
        }

        if (!passwd_verify($password, $user->password)) {
            $message->error("A senha informada não confere");
            return null;
        }

        if (!$user->active) {
            $message->error("Sua conta foi desativada");
            return null;
        }

        if (passwd_rehash($user->password)) {
            $user->password = $password;
            $user->save();
        }

        return $user;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $save
     * @param $message
     * @return bool
     */
    public function login(
        string $email,
        string $password,
        bool $save = false,
        &$message = null
    ): bool {
        $user = $this->attempt($email, $password, $message);
        if (!$user) {
            return false;
        }

        if ($save) {
            setcookie("authEmail", $email, time() + 604800, "/");
        } else {
            setcookie("authEmail", null, time() - 3600, "/");
        }

        //update user session ip
        $user->set_session_log($user);

        //LOGIN
        (new Session())->set("uid", $user->id);
        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function register(User $user, Message $message): bool
    {
        if (!$user->new($message)) {
            return false;
        }

        if ($_ENV['APP_SEND_MAIL'] == 'true') {
            $content = view("app.email.confirm", [
                "first_name" => $user->first_name,
                "confirm_link" => url("api/auth/confirm/" . base64_encode($user->email))
            ]);

            $mail = new Email();
            $mail->bootstrap(
                "Ative sua conta no " . $_ENV['APP_NAME'],
                $content,
                $user->email,
                "{$user->first_name} {$user->last_name}"
            )->send();
        }

        $user->set_session_log($user);

        $session = new Session();
        $session->set('uid', $user->id);

        $message->primary("Olá <b>{$user->first_name}</b>, seja bem-vindo(a) ao {$_ENV['APP_NAME']}.")->flash();

        return true;
    }
}
