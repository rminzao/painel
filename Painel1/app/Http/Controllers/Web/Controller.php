<?php

namespace App\Http\Controllers\Web;

use App\Models\Auth;
use App\Models\Server;
use App\Models\User;
use Core\Seo;
use Core\Session;
use Core\View\View;
use Core\Utils\Message;
use Core\Routing\Request;

class Controller
{
    public function __construct(
        protected View $view = new View(),
        protected Seo $seo = new Seo(),
        protected Message $message = new Message(),
        protected Request $request = new Request(),
        protected Session $session = new Session(),
        protected ?User $user = null
    ) {
        $this->init();
    }

    public function storage(): void
    {
        $file = dirname(__DIR__, 4) . '/storage/app/public/' . $this->request->get()['path'] ?? '';

        if (str_contains($file, '.php') || !file_exists($file)) {
            http_response_code(404);
            return;
        }

        $mime = mime_content_type($file);

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($file));
        readfile($file);
    }

    public function logout(): void
    {
        if ($this->session->has('uid')) {
            $this->message->primary("Você saiu com sucesso <b>" . $this->user->first_name . "</b>. Volte logo :)")->flash();
        }

        Auth::logout();
        redirect('/');
    }

    private function init()
    {
        $data = [];

        $data['head'] = $this->seo->render(
            $_ENV['APP_NAME'] . " - DDTank Pirata",
            "DDTank Raiz de verdade é aqui!",
            url(),
            url($_ENV['APP_URL'] . "/assets/media/logos/logo.png")
        );

        $user = Auth::user();
        if ($user) {
            $this->user = $user;

            $data = [
                'user' => $this->user,
                'session' => $this->session,
                'servers' => Server::all(),
            ];

            //check if user is banned
            if (!$this->user->active) {
                view('others.banned');
                exit;
            }
        }

        $this->view->init($data);
    }
}
