<?php


namespace Source\Controller;


use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\Company;
use Source\Models\Modules\Index;
use Source\Models\User;
use Source\Models\UserCompany;
use Source\Support\Pager;
use Source\Support\Roles;

/**
 * Class Admin
 * @package Source\Controller
 */
class Admin extends Controller
{
    private $session;

    private $user;

    private $company;

    /**
     * Admin constructor.
     * @param $router
     */
    public function __construct($router)
    {
        parent::__construct($router, __DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");
        $this->session = new Session();
        if (!$this->user = User::user()) {
            $this->router->redirect('auth.login');
        }
        if (!$this->company = Company::company()) {
            $this->message->info("Selecione uma empresa para acessar o painel")->flash();
            $this->router->redirect('auth.company');
        }
        if ($this->user->admin !== "true") {
            $this->message->warning("Você não tem acesso a essa área")->flash();
            $this->router->redirect('app.search');
        }
    }

    /**
     * @param array|null $data
     */
    public function indexar(?array $data): void
    {
        $index = new Index();

        if (isset($data['action']) && $data['action'] == "indexar") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $index->index($this->company->id, $data['file_name'], $data['module']);
            exit();
        }

        $head = $this->seo->render(
            "Indexar | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.indexar"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/indexar", [
            "head" => $head,
            "list" => $index->listXML($this->company->id)
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
            "companies" => (new Company())->find('', '', 'id, name, type')->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function createCompany(?array $data): void
    {
        if (isset($data['action']) && $data['action'] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (empty(trim($data['name']))) {
                $json['message'] = $this->message->warning('Preencha o nome da empresa')->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['type']))) {
                $json['message'] = $this->message->warning('Selecione o tipo de empresa')->render();
                echo json_encode($json);
                return;
            }
            $company = new Company();
            $company->name = $data['name'];
            $company->document = $data['document'];
            $company->manager = $data['manager'];
            $company->type = $data['type'];
            $company->address = $data['address'];
            if ($company->save()) {
                $this->message->success("Empresa cadastrada com sucesso!")->flash();
                echo json_encode(['redirect' => $this->router->route('admin.companies')]);
                return;
            }
            if ($company->fail()) {
                $json['message'] = $this->message->error('Erro ao criar a empresa, entre em contato com o suporte')->render();
                echo json_encode($json);
                return;
            }
        }
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

        if (!$company = (new Company())->findById($data['id'])) {
            $this->message->info("Essa empresa não existe ou não foi encontrada")->flash();
            $this->router->redirect('admin.companies');
        }
        if (isset($data['action']) && $data['action'] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (empty(trim($data['name']))) {
                $json['message'] = $this->message->warning('Preencha o nome da empresa')->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['type']))) {
                $json['message'] = $this->message->warning('Selecione o tipo de empresa')->render();
                echo json_encode($json);
                return;
            }
            $company->name = $data['name'];
            $company->document = $data['document'];
            $company->manager = $data['manager'];
            $company->type = $data['type'];
            $company->address = $data['address'];
            if ($company->save()) {
                $this->message->success("Empresa atualizada com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            if ($company->fail()) {
                $json['message'] = $this->message->error('Erro ao atualizar a empresa, entre em contato com o suporte')->render();
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Editar empresa {$company->name} | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.updateCompany"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/updateCompany", [
            "head" => $head,
            "company" => $company
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
        $user = new User();
        $page = isset($_GET['page']) ? filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT) : 1;
        $where = "";
        $params = "";
        if (isset($_GET['filter']) && $_GET['filter'] == 's') {
            $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRIPPED);
            $email = filter_input(INPUT_GET, 'email', FILTER_VALIDATE_EMAIL);
            $occupation = filter_input(INPUT_GET, 'occupation', FILTER_SANITIZE_STRIPPED);
            $company = filter_input(INPUT_GET, 'company', FILTER_VALIDATE_INT);
            $status = filter_input(INPUT_GET, 'status', FILTER_VALIDATE_INT);
            $admin = filter_input(INPUT_GET, 'admin', FILTER_SANITIZE_STRIPPED);

            if (!empty(trim($name))) {
                $where .= " AND (first_name LIKE :n or last_name LIKE :n)";
                $params .= "&n=%{$name}%";
            }
            if (!empty(trim($email))) {
                $where .= " AND email = :e";
                $params .= "&e={$email}";
            }
            if (!empty(trim($occupation))) {
                $where .= " AND occupation LIKE :o";
                $params .= "&o=%{$occupation}%";
            }
            if (!empty(trim($company))) {
                $where .= " AND companies LIKE :c";
                $params .= "&c=%{$company}%";
            }
            if (!empty(trim($status))) {
                $where .= " AND status  = :s";
                $params .= "&s={$status}";
            }
            if (!empty(trim($admin)) && ($admin == "true" || $admin == "false")) {
                $where .= " AND admin  = :a";
                $params .= "&a={$admin}";
            }

            $users = $user->find("created_at IS NOT NULL{$where}", "{$params}");
            $pager = new Pager($this->router->route('admin.users', [
                'filter' => 's',
                'name' => $name,
                'email' => $email,
                'occupation' => $occupation,
                'company' => $company,
                'status' => $status,
                'admin' => $admin,
                'page' => ""
            ]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($users->count(), 30, $page);
        } else {
            $users = $user->find();

            $pager = new Pager($this->router->route('admin.users',
                ['page' => ""]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($users->count(), 30, $page);

        }

        $users = $users->offset($pager->offset())->limit($pager->limit())->fetch(true);

        $head = $this->seo->render(
            "Usuários | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.users"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/users", [
            "head" => $head,
            "users" => $users,
            "render" => $pager->render(),
            "companies" => (new Company())->find("", "", "id, name")->fetch(true),
        ]);
    }

    /**
     * @param array|null $data
     */
    public function createUser(?array $data): void
    {
        $user = new User();
        if (isset($data['action']) && $data['action'] == "create") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (empty(trim($data['first_name'])) || empty(trim($data['last_name']))) {
                $json['message'] = $this->message->warning("Preencha o campo nome e sobrenome do usuário")->render();
                echo json_encode($json);
                return;
            }
            if (!empty(trim($data['email'])) && !is_email($data['email'])) {
                $json['message'] = $this->message->warning("Preencha com um e-mail válido do usuário")->render();
                echo json_encode($json);
                return;
            }
            if ($count_email = $user->find('email = :e', "e={$data['email']}")->count()) {
                $json['message'] = $this->message->warning("Já existe um usuário registrado com esse e-mail")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['password'])) || !is_passwd($data['password'])) {
                $json['message'] = $this->message->warning("Forneça uma senha para cadastrar o usuário")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['occupation']))) {
                $json['message'] = $this->message->warning("Preencha a função do usuário")->render();
                echo json_encode($json);
                return;
            }
            if ($data['admin'] !== "true" && $data['admin'] !== "false") {
                $json['message'] = $this->message->warning("Opção selecionada inválido [Admin]")->render();
                echo json_encode($json);
                return;
            }
            $roles = (new Roles())->arrayRoles();
            if (!isset($data['roles'])) {
                $json['message'] = $this->message->warning("Selecione os módulos de acesso do usuário")->render();
                echo json_encode($json);
                return;
            }
            if (!in_array_r($data['roles'], $roles)) {
                $json['message'] = $this->message->warning("Função do usuário inválida")->render();
                echo json_encode($json);
                return;
            }
            if (!isset($data['companies'])) {
                $json['message'] = $this->message->warning("Selecione as empresas vinculadas ao usuário")->render();
                echo json_encode($json);
                return;
            }
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            $user->password = passwd($data['password']);
            $user->phone = $data['phone'];
            $user->occupation = $data['occupation'];
            $user->status = $data['status'];
            $user->admin = $data['admin'];
            $user->roles = implode(';', $data['roles']);
            $user->companies = implode(';', $data['companies']);
            if ($user->save()) {
                $id_user = $user->id;
                foreach ($data['companies'] as $company) {
                    $user_company = new UserCompany();
                    $user_company->id_user = $id_user;
                    $user_company->id_company = $company;
                    $user_company->save();
                }
                $this->message->success("Usuário registrado com sucesso!")->flash();
                echo json_encode(['redirect' => $this->router->route('admin.users')]);
                return;
            }
            if ($user->fail()) {
                $json['message'] = $this->message->error("Erro ao cadastrar o usuário, entre em contato com o suporte!")->render();
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Cadastrar usuário | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.createUser"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/createUser", [
            "head" => $head,
            "companies" => (new Company())->find()->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function updateUser(?array $data): void
    {
        //Variável para selecionar usuários no menu header
        $_ENV['id_user'] = $data['id'];

        if (!$user = (new User())->findById($data['id'])) {
            $this->message->info("O usuário selecionado não existe")->flash();
            $this->router->redirect('admin.users');
        }

        if (isset($data['action']) && $data['action'] == "update") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (empty(trim($data['first_name'])) || empty(trim($data['last_name']))) {
                $json['message'] = $this->message->warning("Preencha o campo nome e sobrenome do usuário")->render();
                echo json_encode($json);
                return;
            }
            if (!empty(trim($data['email'])) && !is_email($data['email'])) {
                $json['message'] = $this->message->warning("Preencha com um e-mail válido do usuário")->render();
                echo json_encode($json);
                return;
            }
            if ($count_email = $user->find('email = :e AND id != :id', "e={$data['email']}&id={$user->id}")->count()) {
                $json['message'] = $this->message->warning("Já existe um usuário registrado com esse e-mail")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['occupation']))) {
                $json['message'] = $this->message->warning("Preencha a função do usuário")->render();
                echo json_encode($json);
                return;
            }
            if ($data['admin'] !== "true" && $data['admin'] !== "false") {
                $json['message'] = $this->message->warning("Opção selecionada inválido [Admin]")->render();
                echo json_encode($json);
                return;
            }
            $roles = (new Roles())->arrayRoles();
            if (!isset($data['roles'])) {
                $json['message'] = $this->message->warning("Selecione os módulos de acesso do usuário")->render();
                echo json_encode($json);
                return;
            }
            if (!in_array_r($data['roles'], $roles)) {
                $json['message'] = $this->message->warning("Função do usuário inválida")->render();
                echo json_encode($json);
                return;
            }
            if (!isset($data['companies'])) {
                $json['message'] = $this->message->warning("Selecione as empresas vinculadas ao usuário")->render();
                echo json_encode($json);
                return;
            }

            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'];
            $user->occupation = $data['occupation'];
            $user->status = $data['status'];
            $user->admin = $data['admin'];
            $user->roles = implode(';', $data['roles']);
            $user->companies = implode(';', $data['companies']);
            if ($user->save()) {
                $user_company = (new UserCompany())->find('id_user = :id', "id={$user->id}")->fetch(true);
                //Busca relações anteriores e apaga
                foreach ($user_company as $item) {
                    $relationship = (new UserCompany())->findById($item->id);
                    $relationship->destroy();
                }
                //Faz as novas relações
                foreach ($data['companies'] as $company) {
                    $user_company = new UserCompany();
                    $user_company->id_user = $user->id;
                    $user_company->id_company = $company;
                    $user_company->save();
                }
                $this->message->success("Usuário atualizado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            if ($user->fail()) {
                $json['message'] = $this->message->error("Erro ao atualizar o usuário, entre em contato com o suporte!")->render();
                echo json_encode($json);
                return;
            }
        }

        if (isset($data['action']) && $data['action'] == "update_password") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (in_array("", $data)) {
                $json['message_warning'] = "Digite a nova senha e confirme para continuar";
                echo json_encode($json);
                return;
            }
            if (!is_passwd($data['password'])) {
                $json['message_warning'] = "A senha deve ter no mínimo 8 caracteres";
                echo json_encode($json);
                return;
            }
            if ($data['password'] !== $data['password_re']) {
                $json['message_warning'] = "As senhas não conferem";
                echo json_encode($json);
                return;
            }
            $user->password = passwd($data['password']);
            if ($user->save()) {
                $this->message->success("Senha do usuário atualizada com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            if ($user->fail()) {
                $json['message_error'] = "Erro ao atualizar a senha do usuário, entre em contato com o suporte!";
                echo json_encode($json);
                return;
            }
        }


        $head = $this->seo->render(
            "Editar usuário {$user->first_name} | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("admin.updateUser"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("admin/updateUser", [
            "head" => $head,
            "companies" => (new Company())->find()->fetch(true),
            "user" => $user,
            "roles" => explode(';', $user->roles),
            "companies_user" => explode(';', $user->companies)
        ]);
    }
}