<?php

namespace App\Http\Controllers\Web;

use App\Models\User;

class Auth extends Controller
{
    public function signin()
    {
        return $this->view->render('auth.signin', [
            "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }

    public function signup()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $data = $this->request->post();

            // Validação básica
            if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || empty($data['password'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Todos os campos são obrigatórios.'
                ]);
                exit;
            }

            // Verifica se o e-mail já está em uso
            if (User::where('email', $data['email'])->exists()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Este e-mail já está cadastrado.'
                ]);
                exit;
            }

            // Cria o usuário
            User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_BCRYPT),
                'active' => 1
            ]);

            echo json_encode([
                'success' => true,
                'message' => 'Conta criada com sucesso!'
            ]);
            exit;
        }

        // Se for GET, exibe o formulário
        return $this->view->render('auth.signup');
    }

    public function forget()
    {
        return $this->view->render('auth.forget');
    }

    public function forgetConfirm($hash)
    {
        $hash = base64_decode($hash);

        $user = \App\Models\User::where('forget', $hash)->first();
        if (!$user) {
            redirect('auth/recuperar-senha');
        }

        return $this->view->render('auth.forget-confirm', [
            'user' => $user
        ]);
    }
}
