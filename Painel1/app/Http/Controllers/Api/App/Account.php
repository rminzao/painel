<?php

namespace App\Http\Controllers\Api\App;

use App\Http\Controllers\Api\Api;
use App\Models\User;
use App\Models\UserReferrals;
use Core\Routing\Request;
use Core\Utils\Email;
use Core\Utils\Upload;

class Account extends Api
{
    /**
     * @param Request $request
     * @return array
     */
    public function setPassChange(Request $request): array
    {
        $post = $request->post();
        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => "Senha, nova senha e confirmação de nova senha são obrigatórios"
            ];
        }

        if ($post['newpassword'] != $post['confirmpassword']) {
            return [
                'state' => false,
                'message' => 'A senha e sua confirmação não são iguais.'
            ];
        }

        if (!is_passwd($post['newpassword'])) {
            return [
                'state' => false,
                'message' => 'A senha deve ter entre 8 e 40 caracteres.'
            ];
        }

        if (!passwd_verify($post['currentpassword'], $this->user->password)) {
            return [
                'state' => false,
                'message' => 'A senha atual informada não é valida, insira sua senha atual para continuar.'
            ];
        }

        if ($post['currentpassword'] == $post['newpassword']) {
            return [
                'state' => false,
                'message' => 'A nova senha informada é a mesma atual.'
            ];
        }

        if (request_limit("appApiAccountChangePass", 5, 60 * 3)) {
            return [
                'state' => false,
                'message' => "Você já efetuou 5 tentativas, esse é o limite. Por favor, aguarde 3 minutos para tentar novamente!"
            ];
        }

        $user = User::find($this->user->id);
        $user->password = passwd($post['newpassword']);
        if (!$user->save()) {
            return [
                'state' => false,
                'message' => "Erro ao atualizar, verifique os dados"
            ];
        }

        return [
            'state' => true,
            'message' => "Pronto <b>{$this->user->first_name}</b>. Seus dados foram atualizados com sucesso!"
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function setMailChange(Request $request): array
    {
        $post = array_map(
            'strip_tags',
            $request->post()
        );

        if (in_array('', $post)) {
            return [
                'state' => false,
                'message' => "Email e senha são obrigatórios"
            ];
        }

        if (!is_email($post['emailaddress'])) {
            return [
                'state' => false,
                'message' => 'O e-mail informado não tem um formato válido'
            ];
        }

        if (passwd_verify($post['confirmemailpassword'], $this->user->password)) {
            return [
                'state' => false,
                'message' => 'A senha atual informada não é valida, insira sua senha atual para continuar.'
            ];
        }

        if ($post['emailaddress'] == $this->user->email) {
            return [
                'state' => false,
                'message' => 'O novo email informado é o mesmo atual.'
            ];
        }

        if (request_limit("appApiAccountMailChange", 5, 60 * 3)) {
            return [
                'state' => false,
                'message' => "Você já efetuou 5 tentativas, esse é o limite. Por favor, aguarde 3 minutos para tentar novamente!"
            ];
        }

        //find user by id
        $user = User::find($this->user->id);

        //checks if the current email is confirmed, if not, updates and returns success
        if ($user->status == 'registered') {
            $user->email = $post['emailaddress'];
            if (!$user->save()) {
                return [
                    'state' => false,
                    'message' => "Erro ao atualizar, verifique os dados"
                ];
            }

            //send email to new email
            $this->sendNewMailConfirm();

            return [
                'state' => true,
                'message' => "Pronto <b>{$this->user->first_name}</b>. Seus dados foram atualizados com sucesso, para mais segurança em sua conta, valide sua conta clicando no link enviado ao seu novo e-mail!"
            ];
        }


        $user->email_id = str_hash(25);
        if (!$user->save()) {
            return [
                'state' => false,
                'message' => "Erro ao atualizar, verifique os dados"
            ];
        }

        //build hash url
        $urlHash = base64_encode($user->email_id . '/' . $post['emailaddress']);

        //send email confirmation
        $message = view("app.email.change_mail", [
            "user" => $this->user,
            "confirm_link" => url("api/account/mail/confirm/{$urlHash}")
        ]);

        $mail = new Email();
        $mail->bootstrap(
            "Troca de email no " . $_ENV['APP_NAME'],
            $message,
            $user->email,
            "{$user->first_name} {$user->last_name}"
        )->send();

        return [
            'state' => true,
            'message' => "Pronto {$this->user->first_name}. A solicitação para troca de email foi enviada com sucesso,
          para confirmar a alteração clique no link enviado ao seu email <b>{$this->user->email}<b>!"
        ];
    }

    /**
     * @param string $hash
     * @return void
     */
    public function setMailChangeConfirm(string $hash)
    {
        $hash = explode('/', base64_decode($hash));

        $user = User::where('email_id', $hash[0])->first();
        if (!$user or !is_email($hash[1])) {
            redirect('app/me/account/overview');
        }

        $user->email = $hash[1];
        $user->email_id = '';
        $user->status = 'registered';
        $user->save();

        //send email confirmation
        $message = view("app.email.confirm", [
            "first_name" => $user->first_name,
            "confirm_link" => url("api/auth/confirm/" . base64_encode($user->email))
        ]);

        $mail = new Email();
        $mail->bootstrap(
            "Ative sua conta no " . $_ENV['APP_NAME'],
            $message,
            $user->email,
            "{$user->first_name} {$user->last_name}"
        )->send();

        redirect('app/me/account/overview');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function setProfileChange(Request $request): array
    {
        $post = array_map(
            'strip_tags',
            $request->post()
        );

        if (!$post['lname'] or !$post['fname']) {
            return [
                'state' => false,
                'message' => "Nome e sobrenome são obrigatórios"
            ];
        }

        $removeAvatar = $post['avatar_remove'] ?? '';

        if (
            (strlen($post['lname']) < 2 or strlen($post['lname']) > 14) or
            (strlen($post['fname']) < 2 or strlen($post['fname']) > 14)
        ) {
            return [
                'state' => false,
                'message' => "O nome e sobrenome devem conter de 2-14 caracteres."
            ];
        }

        if (request_limit("appApiProfileUpdate", 10, 60 * 3) && $this->user->role != 3) {
            return [
                'state' => false,
                'message' => 'Você já efetuou 10 tentativas, esse é o limite. Por favor,
              aguarde 3 minutos para tentar novamente!'
            ];
        }

        $photo = '';

        $user = User::find($this->user->id);
        $user->first_name = $post['fname'];
        $user->last_name = $post['lname'];

        if ($removeAvatar == '1') {
            $user->photo = "avatars/blank.png";
        }

        if (!empty($_FILES['avatar'])) {
            $files = $_FILES['avatar'];

            for ($i = 0; $i < count($files['type']); $i++) {
                foreach (array_keys($files) as $keys) {
                    $images[$i][$keys] = $files[$keys][$i];
                }
            }

            $upload = new Upload();
            foreach ($images as $item) {
                if ($item['size'] > 8000000) {
                    return [
                        'state' => false,
                        'message' => "A foto de perfil é muito pesada, o tamanho máximo é 8MB"
                    ];
                }

                $image = $upload->image($item, "avatar-" . '-' . uuid(), 'images/avatar');
                if (!$image) {
                    return [
                        'state' => false,
                        'message' => $upload->message()->render()
                    ];
                }

                $name = (explode('public/images/avatar/', $image))[1];
                $originalPhoto = $user->photo;
                $user->photo = "storage/avatar/{$name}";

                //remove original image
                if (!in_array('avatars/default.png', [$originalPhoto, $user->photo])) {
                    $upload->remove(
                        dirname(__DIR__, 4) .
                            '/storage/app/public/images/avatar/' .
                            (explode('storage/avatar/', $this->user->photo))[1]
                    );
                }

                $photo = image_avatar($user->photo, 160, 160);
            }
        }

        if (!$user->save()) {
            return [
                'state' => false,
                'message' => "Erro ao atualizar seus dados, verifique as informações e tente novamente"
            ];
        }

        return [
            'state' => true,
            'message' => "Pronto <b>{$this->user->first_name}</b>. Seus dados foram atualizados com sucesso!",
            'photo' => $photo
        ];
    }

    public function getReferenced()
    {
        $referrals = UserReferrals::where('uid', $this->user->id)->get();
        if ($referrals->IsEmpty()) {
            return [
                'state' => false,
                'message' => "Você não possui referencias."
            ];
        }

        $referrals = $referrals->toArray();
        foreach ($referrals as &$referral) {
            if (!$user = User::find($referral['rid'])) {
                continue;
            }

            $referral['created_at'] = date('Y-m-d H:i:s', strtotime($referral['created_at']));
            $referral['last_update'] = date('M d, Y', strtotime($referral['updated_at']));
            $referral['referenced'] = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'avatar' => image_avatar($user->photo, 50, 50)
            ];
        }

        return [
            'state' => true,
            'referenced' => $referrals,
            'results' => count($referrals)
        ];
    }

    protected function sendNewMailConfirm(): void
    {
        $user = User::find($this->user->id);

        //send email confirmation
        $message = view("app.email.confirm", [
            "first_name" => $user->first_name,
            "confirm_link" => url("api/auth/confirm/" . base64_encode($user->email))
        ]);

        $mail = new Email();
        $mail->bootstrap(
            "Ative sua conta no " . $_ENV['APP_NAME'],
            $message,
            $user->email,
            "{$user->first_name} {$user->last_name}"
        )->send();
    }
}
