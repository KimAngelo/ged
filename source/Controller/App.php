<?php


namespace Source\Controller;


use Source\Core\Controller;

/**
 * Class App
 * @package Source\Controller
 */
class App extends Controller
{
    /**
     * App constructor.
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router, __DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");
    }

    /**
     * @param array|null $data
     */
    public function search(?array $data): void
    {
        $head = $this->seo->render(
            "Procurar | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.search"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("search", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function monitoring(? array $data): void
    {
        $head = $this->seo->render(
            "Monitoramento | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.monitoring"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("monitoring", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     * Controlador para alterar senha do usuário
     */
    public function updatePassword(?array $data): void
    {
        $head = $this->seo->render(
            "Alterar minha senha | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.update_password"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("updatePassword", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function expenses(? array $data): void
    {
        $head = $this->seo->render(
            "Despesas | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.expenses"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("expenses", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function bidding(? array $data): void
    {
        $head = $this->seo->render(
            "Licitação | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.bidding"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("bidding", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function contract(? array $data): void
    {
        $head = $this->seo->render(
            "Contrato | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.contract"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("contract", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function legislation(? array $data): void
    {
        $head = $this->seo->render(
            "Legislação | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.legislation"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("legislation", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function report(? array $data): void
    {
        $head = $this->seo->render(
            "Relatórios | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.report"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("report", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function convention(? array $data): void
    {
        $head = $this->seo->render(
            "Convênio | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.convention"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("convention", [
            "head" => $head,
        ]);
    }

    /**
     * @param array|null $data
     * Controlador de erro
     */
    public function error(?array $data): void
    {
        $head = $this->seo->render(
            "Oops {$data['errcode']} | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.error", ["errcode" => $data['errcode']]),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("error", [
            "head" => $head,
            "errcode" => $data['errcode']
        ]);
    }
}