<?php


namespace Source\Controller;


use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\User;
use Source\Models\UserCompany;

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
        $head = $this->seo->render(
            "Restaurar senha | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("auth.recover"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("auth/recover", [
            "head" => $head,
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
}