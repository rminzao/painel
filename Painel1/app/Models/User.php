<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    public $timestamps = true;

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

    protected $hidden = [
        'password',
        'p_hash',
    ];

    protected $repeatPassword;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $password
     * @return User
     */
    public function bootstrap(
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        ?string $referenced = null
    ): User {
        $this->first_name = $firstName;
        $this->last_name = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->u_hash = str_hash(16);
        $this->p_hash = md5(str_hash(20));
        $this->reference = str_hash(25);
        $this->photo = 'avatars/default.png';
        $this->status = 'registered';
        $this->referenced = $referenced;
        return $this;
    }

    /**
     * @param string $email
     * @param string $columns
     * @return null|User
     */
    public function findByEmail(string $email, string $columns = "*"): ?User
    {
        $find = $this->select($columns)->where("email", $email);
        return $find->first();
    }

    public function findByRefer(string $refer, string $columns = "*"): ?User
    {
        $find = $this->select($columns)->where("reference", $refer);
        return $find->first();
    }

     /**
     * @return string
     */
    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * @return string
     */
    public function mail($obfuscated = false): string
    {
        return $obfuscated ? str_obfuscate_email($this->email) : $this->email;
    }

    /**
     * @return bool
     */
    public function new(&$message): bool
    {
        if (!$this->first_name || !$this->last_name || !$this->email || !$this->password) {
            $message->warning("Nome, sobrenome, email e senha são obrigatórios");
            return false;
        }

        if (!is_email($this->email)) {
            $message->warning("O e-mail informado não tem um formato válido");
            return false;
        }

        if (!is_passwd($this->password)) {
            $min = 8;
            $max = 40;
            $message->warning("A senha deve ter entre {$min} e {$max} caracteres");
            return false;
        } else {
            $this->password = passwd($this->password);
        }

        if ($this->referenced && !$this->findByRefer($this->referenced, "id")) {
            $message->warning("O código de referencia informado não é válido.");
            return false;
        }

        if ($this->findByEmail($this->email, "id")) {
            $message->warning("O e-mail informado já está cadastrado");
            return false;
        }

        if (!$this->save()) {
            $message->error("Erro ao cadastrar, verifique os dados");
            return false;
        }

        return true;
    }

    public function characters()
    {
        $characterList = null;

        foreach (Server::all() as $server) {
            $chars = (new Character($server->dbUser))->findByUser($this->u_hash);

            foreach ($chars as $char) {
                $char->_server = $server->toArray();
                $characterList[] = $char?->toArray();
            }
        }

        return $characterList;
    }

    public function borders()
    {
        return (new UserBorders())->getByUser($this->id);
    }

    public function set_session_log($user)
    {
        if (!UserSessions::where('uid', $user->id)->where('ip', get_client_ip())->get()->first()) {
            (new UserSessions())->create([
                'uid' => $user->id,
                'ip' =>  get_client_ip()
            ]);
        }
    }
}
