<?php


namespace Source\Controller;


use Source\Core\Controller;
use Source\Core\Session;
use Source\Core\View;
use Source\Models\User;
use Source\Models\UserCompany;
use Source\Support\Email;
use Source\Support\GoogleStorage;

/**
 * Class Auth
 * @package Source\Controller
 */
class Auth extends Controller
{
    /**
     * Auth constructor.
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router, __DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");
    }

    /**
     * @param array|null $data
     * Controlador de login
     */
    public function login(?array $data): void
    {
        if (\user()) {
            $this->router->redirect('app.search');
        }

        if (isset($data['csrf']) && !empty($data['csrf'])) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            /*if (!csrf_verify($data)) {
                $json['message'] = $this->message->error('Ooops! Ocorreu um erro, entre em contato com o suporte')->render();
                echo json_encode($json);
                return;
            }*/
            if (in_array("", $data)) {
                $json['message'] = $this->message->warning('Informe seu e-mail e senha para continuar')->render();
                echo json_encode($json);
                return;
            }
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $json['message'] = $this->message->warning('Informe seu e-mail correto para continuar')->render();
                echo json_encode($json);
                return;
            }
            if (!is_passwd(trim($data['password']))) {
                $json['message'] = $this->message->warning('Senha incorreta, tente outra vez')->render();
                echo json_encode($json);
                return;
            }
            var_dump($data);
            var_dump((new User())->find('email = :e AND status = :s', "e={$data['email']}&s=1"));
            $user = (new User())->find('email = :e AND status = :s', "e={$data['email']}&s=1")->fetch();
            if (!$user || !passwd_verify($data['password'], $user->password)) {
                $json['message'] = $this->message->warning('E-mail e/ou senha incorreto, tente novamente')->render();
                echo json_encode($json);
                return;
            }

            (new Session())->set('authUser', $user->id);
            $json['redirect'] = $this->router->route('auth.company');
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Acessar " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("auth.login"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("auth/login", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     * Controlador de esqueceu a senha
     */
    public function forget(?array $data): void
    {
        if (\user()) {
            $this->router->redirect('app.search');
        }

        if (isset($data['csrf'])) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            if (!csrf_verify($data)) {
                $json['message'] = $this->message->error("Ooops! Tente novamente mais tarde!")->render();
                echo json_encode($json);
                return;
            }
            if (request_reapeat("authforget", $data['email'])) {
                $json['message'] = $this->message->error("Ooops! Você já tentou este e-mail antes")->render();
                echo json_encode($json);
                return;
            }
            if (empty($data["email"])) {
                $json['message'] = $this->message->warning("Informe seu e-mail para continuar")->render();
                echo json_encode($json);
                return;
            }

            if (!is_email($data['email'])) {
                $json['message'] = $this->message->warning("Forneça um e-mail válido")->render();
                echo json_encode($json);
                return;
            }

            $user = (new User())->find("email = :email AND status = :s", "email={$data['email']}&s=1")->fetch();
            if (!$user) {
                $json['message'] = $this->message->warning(" O e-mail informado não está registrado.")->render();
                echo json_encode($json);
                return;
            }

            $user->forget = md5(uniqid(rand(), true));
            $user->save();

            $view = new View($this->router, __DIR__ . "/../../shared/views/email");
            $message = $view->render("forget", [
                "first_name" => $user->first_name,
                "forget_link" => $this->router->route('auth.recover', ['code' => "{$user->email}|{$user->forget}"])
            ]);

            (new Email())->bootstrap(
                "Recupere o seu acesso | " . CONF_SITE_NAME,
                $message,
                $user->email,
                "{$user->first_name} {$user->last_name}"
            )->send();

            $json['message'] = $this->message->success("Acesse seu e-mail para recuperar a senha")->render();

            echo json_encode($json);
            return;

        }

        $head = $this->seo->render(
            "Esqueci minha senha | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("auth.forget"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("auth/forget", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     * Restaurar senha
     */
    public function recover(?array $data): void
    {
        if (\user()) {
            $this->router->redirect('app.search');
        }

        if (isset($data['csrf'])) {
            /*if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Ooops! Tente novamente mais tarde!")->render();
                echo json_encode($json);
                return;
            }*/

            if (empty($data["password"]) || empty($data["password_re"])) {
                $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
                echo json_encode($json);
                return;
            }

            list($email, $code) = explode("|", $data["code"]);
            $user = (new User())->find("email = :email", "email={$email}")->fetch();


            if (!$user) {
                $json["message"] = $this->message->error("A conta para recuperação não foi encontrada.")->render();
                echo json_encode($json);
                return;
            }

            if ($user->forget != $code) {
                $json["message"] = $this->message->error("Desculpe, mas o código de verificação não é válido.")->render();
                echo json_encode($json);
                return;
            }

            if (!is_passwd($data['password'])) {
                $min = CONF_PASSWD_MIN_LEN;
                $max = CONF_PASSWD_MAX_LEN;
                $json["message"] = $this->message->info("Sua senha deve ter entre {$min} e {$max} caracteres.")->render();
                echo json_encode($json);
                return;
            }

            if ($data['password'] != $data['password_re']) {
                $json["message"] = $this->message->warning("Você informou duas senhas diferentes.")->render();
                echo json_encode($json);
                return;
            }

            $user->password = passwd($data['password']);
            $user->forget = null;
            $user->save();

            (new Session())->unset('authforget');

            $this->message->success("Senha alterada com sucesso! Acesse com a sua nova senha")->flash();
            echo json_encode(["redirect" => $this->router->route('auth.login')]);
            return;
        }

        $head = $this->seo->render(
            "Restaurar senha | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("auth.recover"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("auth/recover", [
            "head" => $head,
            "code" => $data["code"]
        ]);
    }

    /**
     * Controlador para sair do sistema
     */
    public function logout(): void
    {
        (new Session())->destroy();
        $this->router->redirect('auth.login');
    }

    /**
     * @param array|null $data
     * Controlador para selecionar empresa
     */
    public function company(?array $data): void
    {
        $session = new Session();
        if (!\user()) {
            $this->message->info("Faça login para poder acessar o painel")->flash();
            $this->router->redirect('auth.login');
        }

        $user_company = new UserCompany();

        //Altera a empresa por ajax
        if (isset($data['company']) && filter_var($data['company'], FILTER_VALIDATE_INT)) {
            if (!$user_company->find("id_user = :id_user AND id_company = :id_company", "id_user={$session->authUser}&id_company={$data['company']}")->count()) {
                $json['message'] = $this->message->error("Você não tem acesso a essa empresa")->render();
                echo json_encode($json);
                return;
            }
            $session->set('company', $data['company']);
            $json['redirect'] = $this->router->route('app.search');
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Selecionar empresa | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("auth.company"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("auth/company", [
            "head" => $head,
            "companies" => $user_company->find('id_user = :id', "id=" . \user()->id)->fetch(true)
        ]);
    }

    public function test()
    {
        $upload = new GoogleStorage();
        var_dump($upload->write('teste.txt', 'teste.txt'));
        $url = $upload->url('/teste.txt');
        echo "<a targert='_blank' href='" . $url . "'>Teste</a>";
    }
}