<?php


namespace Source\Controller;


use Source\Core\Controller;

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

    }

    /**
     * @param array|null $data
     * Controlador para selecionar empresa
     */
    public function company(?array $data): void
    {
        $head = $this->seo->render(
            "Selecionar empresa | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("auth.company"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("auth/company", [
            "head" => $head,
        ]);
    }
}