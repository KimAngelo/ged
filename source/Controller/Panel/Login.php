<?php


namespace Source\Controller\Panel;


use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\Admin;

class Login extends Controller
{
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/");
    }

    /**
     * @param array|null $data
     */
    public function login(?array $data): void
    {

        if (isset($data['csrf'])) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            /*if (request_limit("loginLogin", 5, 2 * 60)) {
                $json['message'] = $this->message->error(" Aguarde 2 minutos e tente novamente.")->render();
                echo json_encode($json);
                return;
            }*/

            if (empty($data['email']) || empty($data['password'])) {
                $json['message'] = $this->message->warning(" Preecha todos os campos")->render();
                echo json_encode($json);
                return;
            }

            if (!is_email($data['email'])) {
                $json['message'] = $this->message->warning(" O e-mail informado não é válido")->render();
                echo json_encode($json);
                return;
            }

            if (!is_passwd($data['password'])) {
                $json['message'] = $this->message->warning(" A senha informada não é válida")->render();
                echo json_encode($json);
                return;
            }

            $admin = (new Admin())->find("email = :e", "e={$data['email']}")->fetch();
            if (!$admin) {
                $json['message'] = $this->message->warning(" E-mail inválido")->render();
                echo json_encode($json);
                return;
            }

            if (!passwd_verify($data['password'], $admin->password)) {
                $json['message'] = $this->message->warning(" A senha informada não é válida")->render();
                echo json_encode($json);
                return;
            }

            if (passwd_rehash($admin->password)) {
                $admin->password = passwd($data['password']);
                $admin->save();
            }
            (new Session())->set("adminUser", $admin->id);
            $this->message->success("Bem vindo novamente {$admin->first_name}")->flash();
            $json['redirect'] = url("/panel");
            echo json_encode($json);
            return;
        }

        $admin = Admin::userAdmin();

        if ($admin) {
            redirect("/panel");
        }

        $head = $this->seo->render(
            CONF_SITE_NAME . " | Admin",
            CONF_SITE_DESC,
            url("/panel/login"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("views/login", [
            "head" => $head,
        ]);
    }
}