<?php


namespace Source\Controller;


use Source\Core\Controller;

/**
 * Class Admin
 * @package Source\Controller
 */
class Admin extends Controller
{
    /**
     * Admin constructor.
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router, __DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");
    }

    /**
     * @param array|null $data
     */
    public function indexar(?array $data): void
    {

        $head = $this->seo->render(
            "Indexar | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.indexar"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/indexar", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function companies(?array $data): void
    {

        $head = $this->seo->render(
            "Empresas | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.companies"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/companies", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function createCompany(?array $data): void
    {
        $head = $this->seo->render(
            "Cadastrar empresa | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.createCompany"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/createCompany", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function updateCompany(?array $data): void
    {
        //Variável para selecionar empresas no menu header
        $_ENV['id_company'] = $data['id'];

        $head = $this->seo->render(
            "Editar empresa XXXXX | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.updateCompany"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/updateCompany", [
            "head" => $head,
        ]);
    }

    /**
     * @param array $data
     */
    public function deleteCompany(array $data): void
    {

    }

    /**
     * @param array|null $data
     */
    public function users(?array $data): void
    {
        $head = $this->seo->render(
            "Usuários | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.users"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/users", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function createUser(?array $data): void
    {
        $head = $this->seo->render(
            "Cadastrar usuário | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.createUser"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/createUser", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function updateUser(?array $data): void
    {
        //Variável para selecionar usuários no menu header
        $_ENV['id_user'] = $data['id'];

        $head = $this->seo->render(
            "Editar usuário XXXX | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.updateUser"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/updateUser", [
            "head" => $head,
        ]);
    }
}