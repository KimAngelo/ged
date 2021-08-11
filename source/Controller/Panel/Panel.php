<?php


namespace Source\Controller\Panel;


use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\Address\Address;
use Source\Models\Advertisement\Advert;
use Source\Models\Advertisement\Image;
use Source\Models\Advertisement\Plan;
use Source\Models\Blog\Category;
use Source\Models\Blog\Post;
use Source\Models\Faq\Faq;
use Source\Models\Option;
use Source\Models\Rent\Subscription;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\Upload;
use Source\Models\User;
use Source\Support\Message;
use Source\Support\Pager;
use Source\Support\Thumb;

/**
 * Class Panel
 * @package Source\Controller
 */
class Panel extends Controller
{

    /** @var \Source\Models\User|null */
    protected $user;

    /**
     * Panel constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../../themes/" . CONF_VIEW_PANEL . "/");

        $this->user = User::userAdmin();
        if (!$this->user || $this->user->level < 5) {
            $this->message->error("Você não tem permissão para acessar este painel")->flash();
            redirect("/panel/login");
        }
    }

    /**
     *
     */
    public function home(?array $data): void
    {
        //Real Time Access
        if (!empty($data['refresh'])) {
            $list = null;
            $items = (new Online())->findByActive();
            if ($items) {
                /** @var  $item Online */
                foreach ($items as $item) {
                    $list[] = [
                        "dates" => date_fmt($item->created_at, "H\hi") . " - " . date_fmt($item->updated_at, "H\hi"),
                        "user" => !empty($item->user) ? $item->user()->name : "Guest",
                        "pages" => $item->pages,
                        "url" => $item->url
                    ];
                }
            }

            echo json_encode([
                "count" => (new Online())->findByActive(true),
                "list" => $list
            ]);

            return;
        }

        $views = (new Access())->find("DATE(created_at) = DATE(now())")->fetch();

        $head = $this->seo->render(
            "Bem vindo " . $this->user->first_name,
            CONF_SITE_DESC,
            url("/panel"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("dash", [
            "head" => $head,
            "pages" => ($views->pages ?? "0"),
            "views" => ($views->views ?? "0"),
            "users" => (new User())->find()->count(),
            "online" => (new Online())->findByActive(),
            "onlineCount" => (new Online())->findByActive(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function plans(?array $data): void
    {
        //create
        if (isset($data['action']) && $data['action'] == "create") {
            if (!csrf_verify($data)) {
                $json['message_error'] = "Ooops! Ocorreu um erro interno, tente mais tarde ou entre em contato com o suporte";
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['title'])) || empty(trim($data['price'])) || empty(trim($data['max_installments'])) || empty(trim($data['free_installments'])) || empty(trim($data['interest_rate'])) || empty(trim($data['description'])) || empty(trim($data['status'])) || empty(trim($data['category'])) || empty(trim($data['months']))) {
                $json['message_warning'] = "Favor preencher todos os campos";
                echo json_encode($json);
                return;
            }
            $plan = new Plan();
            $plan->title = $data['title'];
            $plan->price = $data['price'];
            $plan->max_installments = $data['max_installments'];
            $plan->free_installments = $data['free_installments'];
            $plan->interest_rate = $data['interest_rate'];
            $plan->description = $data['description'];
            $plan->status = $data['status'];
            $plan->category = $data['category'];
            $plan->months = $data['months'];
            if ($plan->save()) {
                $this->message->success("Plano criado com sucesso!")->flash();
                $json['redirect'] = url("/panel/planos");
                echo json_encode($json);
                return;
            } else {
                $json['message_error'] = "Ooops! Tente novamente mais tarde";
                echo json_encode($json);
                return;
            }
        }
        //update
        if (isset($data['action']) && $data['action'] == "update") {
            $plan = (new Plan())->findById($data['id_plan']);
            if ($plan) {
                if (empty(trim($data['title'])) || empty(trim($data['max_installments'])) || empty(trim($data['free_installments'])) || empty(trim($data['description'])) || empty(trim($data['status'])) || empty(trim($data['category'])) || empty(trim($data['months']))) {
                    $json['message_warning'] = "Favor preencher todos os campos";
                    echo json_encode($json);
                    return;
                }
                $plan->title = $data['title'];
                $plan->price = $data['price'];
                $plan->max_installments = $data['max_installments'];
                $plan->free_installments = $data['free_installments'];
                $plan->interest_rate = $data['interest_rate'];
                $plan->description = $data['description'];
                $plan->status = $data['status'];
                $plan->category = $data['category'];
                $plan->months = $data['months'];

                if ($plan->save()) {
                    $this->message->success(" Plano alterado com sucesso!")->flash();
                    $json['redirect'] = url("/panel/planos");
                    echo json_encode($json);
                    return;
                }
                if ($plan->fail()) {
                    $this->message->error(" {$plan->fail()->getMessage()}")->flash();
                    $json['redirect'] = url("/panel/planos");
                    echo json_encode($json);
                    return;
                }
            }
        }
        //delete
        if (isset($data['action']) && $data['action'] == "delete") {
            $plan = (new Plan())->findById($data['id_plan']);
            if ($plan->destroy()) {
                $this->message->success(" Plano apagado com sucesso!")->flash();
                redirect("/panel/planos");
            }
        }

        $plans = (new Plan())->find()->order('category')->fetch(true);
        $head = $this->seo->render(
            "Planos - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("plans", [
            "head" => $head,
            "plans" => $plans
        ]);
    }

    /**
     * @param array|null $data
     */
    public function users(?array $data): void
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            $user = (new User())->findById($data['id']);
            $upload = new Upload();
            if (!empty($user->profile_picture)) {
                (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_PROFILE . "/" . $user->profile_picture);
                $upload->remove($user->profile_picture, __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_PROFILE . "/");
            }

            $adverts = (new Advert())->find("id_user = :id_user", "id_user={$data['id']}")->fetch(true);

            if ($adverts) {
                /** @var  $advert Advert */
                foreach ($adverts as $advert) {
                    /** @var  $image Image */
                    $image = (new Image())->find("id_property = :id_property", "id_property={$advert->id_property}")->fetch(true);
                    if ($image) {
                        $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_ADVERT . "/";
                        foreach ($image as $item) {
                            $thumb = (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_ADVERT . "/" . $item->image_name);
                            if (file_exists($path . $item->image_name)) {
                                unlink($path . $item->image_name);
                            }
                        }
                    }
                    $advert->destroy();
                }
            }
            if ($user->destroy()) {
                $this->message->success(" Usuário apagado com sucesso!")->flash();
                redirect("/panel/usuarios");
            }
            if ($user->fail()) {
                $this->message->error(" {$user->fail()->getMessage()}")->flash();
                redirect("/panel/usuarios");
            }
        }

        $users = new User();

        $head = $this->seo->render(
            "Usuários - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("users", [
            "head" => $head,
            "users" => $users->find('', '', 'id_user, name, lastname, email, created_at')->order("id_user DESC")->fetch(true)
        ]);

    }

    /**
     * @param array|null $data
     * @throws \Exception
     */
    public function user(?array $data)
    {
        if (isset($data['id'])) {
            $user = (new User())->findById($data['id']);
        }
        if (!$user) {
            redirect("/panel");
        }

        $subscription = new Subscription();

        //Alterar dados do usuário
        if (!empty($data['csrf'])) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Erro! Tente novamente mais tarde!")->render();
                echo json_encode($json);
                return;
            }


            if (empty(trim($data['name'])) || empty(trim($data['lastname']))) {
                $json["message_warning"] = "Favor preecher nome e sobrenome";
                echo json_encode($json);
                return;
            }

            if ($user->email != $data['email']) {
                $emailVerify = (new User())->find("email = :e", "e={$data['email']}")->fetch();
                if ($emailVerify) {
                    $json["message_warning"] = "O e-mail informado já está cadastrado";
                    echo json_encode($json);
                    return;
                }
            }

            $user->name = $data['name'];
            $user->lastname = $data['lastname'];
            $user->zip_code = $data['zip_code'];
            $user->state = $data['state'];
            $user->city = $data['city'];
            $user->address = $data['address'];
            $user->address_number = $data['address_number'];
            $user->address_complement = $data['address_complement'];
            $user->neighborhood = $data['neighborhood'];
            $user->cell_number = $data['cell_number'];
            $user->document = $data['document'];
            $user->document_number = $data['document_number'];
            $user->email = $data['email'];
            $user->status = "complete";
            $user->level = $data['level'];
            $user->is_free = $data['is_free'];
            $user->save();

            if ($user->fail()) {
                $json["message_error"] = 'Oops, ocorreu um erro, tente mais tarde!';
                echo json_encode($json);
                return;
            } else {
                $json["message_success"] = "Os dados foram alterados com sucesso!";
                echo json_encode($json);
                return;
            }
        }

        //Alterar senha do usuário
        if (isset($data['password'])) {
            if (empty($data["password"]) || empty($data["password_re"])) {
                $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
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
            $password = passwd($data['password']);
            $user->pass = $password;
            $user->save();
            if ($user->fail()) {
                $json["message"] = $this->message->error("Oops, ocorreu um erro, tente mais tarde!")->render();
                echo json_encode($json);
                return;
            } else {
                $json["message"] = $this->message->success("Senha foi alterada com sucesso!")->render();
                echo json_encode($json);
                return;
            }

        }

        //Alterar status do anúncio
        if (isset($data['id_property'])) {
            $advert = (new Advert())->findById($data['id_property']);

            $advert->active = $data['active'];
            $advert->final_date = $data['final_date'];

            if ($advert->save()) {
                $this->message->success("Anúncio alterado com sucesso!")->flash();
                $json['refresh'] = true;
                echo json_encode($json);
            }
            if ($advert->fail()) {
                $this->message->error("{$advert->fail()->getMessage()}")->flash();
                $json['refresh'] = true;
                echo json_encode($json);
            }
            return;
        }

        //Liberar assinatura Zero Aluguel
        if (isset($data['action']) && $data['action'] == "subscription") {
            if (empty(trim($data['id_plan'])) || !is_numeric($data['id_plan'])) {
                $json['message_warning'] = "Selecione um plano para continuar";
                echo json_encode($json);
                return;
            }
            if (!is_date($data['next_due']) || $data['next_due'] <= date('Y-m-d')) {
                $json['message_warning'] = "Selecione uma data maior que a data atual para continuar";
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['property_limit'])) || !is_numeric($data['property_limit']) || $data['property_limit'] < 1) {
                $json['message_warning'] = "Digite o limite de imóveis para continuar";
                echo json_encode($json);
                return;
            }
            if ($subscription->find("id_user = :id", "id={$data['id']}")->count()) {
                $json['message_warning'] = "Este usuário já possui uma assinatura ativa";
                echo json_encode($json);
                return;
            }
            $subscription->id_user = $data['id'];
            $subscription->id_plan = $data['id_plan'];
            $subscription->next_due = $data['next_due'];
            $subscription->property_limit = $data['property_limit'];
            $subscription->status = 'paid';
            $subscription->last_due = date_fmt('now', 'Y-m-d');
            if ($subscription->save()) {
                $user->rent_subscription = "active";
                $user->save();

                $this->message->success("Assinatura liberada com sucesso")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            if ($subscription->fail()) {
                $json['message_error'] = "Erro ao liberar assinatura, entre em contato com o suporte!";
                echo json_encode($json);
                return;
            }
        }

        $adverts = (new Advert())->find("id_user = :id", "id={$data['id']}")->fetch(true);
        $subscription = $subscription->find('id_user = :id_user', "id_user={$data['id']}")->fetch();
        $plansRent = (new \Source\Models\Rent\Plan())->find('status = :s', 's=active')->fetch(true);

        $head = $this->seo->render(
            "Usuário {$user->name} - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/panel/usuario/{$data['id']}"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("user", [
            "head" => $head,
            "user" => $user,
            "adverts" => $adverts,
            "subscription" => $subscription,
            "plansRent" => $plansRent
        ]);
    }

    /**
     * @param array|null $data
     */
    public function blogCategories(?array $data): void
    {
        $category = new Category();
        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

        //Validação de Campos
        if (isset($data['action']) && ($data['action'] == "create" || $data['action'] == "update")) {
            if (empty(trim($data['title'])) || strlen(trim($data['title'])) < 2) {
                $json['message_warning'] = "Favor escrever o nome da categoria para continuar";
                echo json_encode($json);
                return;
            }
        }

        //Create
        if (isset($data['action']) && $data['action'] == "create") {
            $category_verify = $category->find("title = :t", "t={$data['title']}")->count();
            if ($category_verify) {
                $json['message_warning'] = "Já existe uma categoria com este nome, favor escolher outro para continuar";
                echo json_encode($json);
                return;
            }
            $category->title = $data['title'];
            $category->slug = str_slug($data['title']);
            $category->description = $data['description'];
            if ($category->save()) {
                $this->message->success("Categoria criada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            $json['message_error'] = "Ooops! entre em contato com o suporte";
            echo json_encode($json);
            return;
        }

        //Update
        if (isset($data['action']) && $data['action'] == "update") {
            $category_verify = $category->find("title = :t AND id != :id", "t={$data['title']}&id={$data['id']}")->count();
            if ($category_verify) {
                $json['message_warning'] = "Já existe uma categoria com este nome, favor escolher outro para continuar";
                echo json_encode($json);
                return;
            }
            $category = $category->findById($data['id']);
            $category->title = $data['title'];
            $category->slug = str_slug($data['title']);
            $category->description = $data['description'];
            if ($category->save()) {
                $this->message->success("Categoria atualizada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            $json['message_error'] = "Ooops! entre em contato com o suporte";
            echo json_encode($json);
            return;
        }

        //Delete
        if (isset($data['action']) && $data['action'] == "delete") {
            $category = $category->findById($data['id']);
            if (!$category) {
                $json['message_error'] = "Não foi encontrado a categoria selecionada";
                echo json_encode($json);
                return;
            }
            if ($category->destroy()) {
                $this->message->success("Categoria apagada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            $json['message_error'] = "Ooops! entre em contato com o suporte";
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Categorias - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("categories", [
            "head" => $head,
            "categories" => $category->find()->order("title DESC")->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function blogPosts(?array $data): void
    {
        $post = new Post();
        //Delete
        if (isset($data['action']) && $data['action'] == "delete") {
            $post = $post->findById($data['id']);
            if (!empty($post->cover)) {
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_BLOG . "/";
                $thumb = (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_BLOG . "/" . $post->cover);
                if (file_exists($path . $post->cover)) {
                    unlink($path . $post->cover);
                }
            }
            if ($post->destroy()) {
                $this->message->success("Post apagado com sucesso")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $this->message->error($post->fail()->getMessage())->flash();
            echo json_encode(['refresh' => true]);
            return;
        }


        $head = $this->seo->render(
            "Posts - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("posts", [
            "head" => $head,
            "posts" => $post->find("type = :t", "t=post")->order("id DESC")->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function createPost(?array $data): void
    {
        $post = new Post();

        //CREATE
        if (isset($data['csrf'])) {
            $title = filter_var($data['title'], FILTER_SANITIZE_STRIPPED);
            $description = filter_var($data['description'], FILTER_SANITIZE_STRIPPED);
            $category = filter_var($data['category'], FILTER_SANITIZE_NUMBER_INT);
            $status = filter_var($data['status'], FILTER_SANITIZE_STRIPPED);
            $content = $data['content'];

            $post_verify = $post->find("title = :t AND type = :type", "t={$title}&type=post")->count();
            if ($post_verify) {
                $json['message_warning'] = "Já existe um post com este título, digite outro para continuar";
                echo json_encode($json);
                return;
            }

            if (empty(trim($title)) || strlen(trim($title)) < 2) {
                $json['message_warning'] = "Digite um título válido para continuar";
                echo json_encode($json);
                return;
            }
            if ($status != "post" && $status != "draft") {
                $json['message_warning'] = "Status inválido";
                echo json_encode($json);
                return;
            }

            if (empty(trim($content))) {
                $json['message_warning'] = "Escreva algum conteúdo para continuar";
                echo json_encode($json);
                return;
            }

            if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'][0])) {
                $upload = new Upload();

                $ext = ($_FILES['cover']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                $file_name = md5(microtime() . $_FILES['cover']['name'][0]) . $ext;
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_BLOG . "/";
                if ($upload->send($_FILES['cover'], $file_name, $path)) {
                    $post->cover = $file_name;
                } else {
                    $json['message_warning'] = "Erro ao enviar imagem de destaque";
                    echo json_encode($json);
                    return;
                }
            }
            $post->author = $this->user->id_user;
            $post->category = !empty($category) ? $category : null;
            $post->title = $title;
            $post->slug = str_slug($title);
            $post->description = $description;
            $post->content = $content;
            $post->status = $status;
            $post->type = "post";

            if ($post->save()) {
                $this->message->success("Post criado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao criar o post";
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Novo post - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("createPost", [
            "head" => $head,
            "categories" => (new Category())->find()->order("title DESC")->fetch(true)
        ]);
    }

    /**
     * @param array $data
     */
    public function updatePost(array $data): void
    {
        $post = new Post();

        if (!$post = $post->find("id = :id AND type = :t", "id={$data['id']}&t=post")->fetch()) {
            $this->message->error("Post não encontrado")->flash();
            redirect("/panel/blog-posts");
            return;
        }

        //UPDATE
        if (isset($data['csrf'])) {
            $title = filter_var($data['title'], FILTER_SANITIZE_STRIPPED);
            $description = filter_var($data['description'], FILTER_SANITIZE_STRIPPED);
            $category = filter_var($data['category'], FILTER_SANITIZE_NUMBER_INT);
            $status = filter_var($data['status'], FILTER_SANITIZE_STRIPPED);
            $content = $data['content'];

            $post_verify = $post->find("title = :t AND type = :type AND id != :id", "t={$title}&type=post&id={$data['id']}")->count();
            if ($post_verify) {
                $json['message_warning'] = "Já existe um post com este título, digite outro para continuar";
                echo json_encode($json);
                return;
            }

            if (!csrf_verify($data)) {
                $json['message_error'] = "Ooops! Tente novamente mais tarde!";
                echo json_encode($json);
                return;
            }
            if (empty(trim($title)) || strlen(trim($title)) < 2) {
                $json['message_warning'] = "Digite um título válido para continuar";
                echo json_encode($json);
                return;
            }
            if ($status != "post" && $status != "draft") {
                $json['message_warning'] = "Status inválido";
                echo json_encode($json);
                return;
            }

            if (empty(trim($content))) {
                $json['message_warning'] = "Escreva algum conteúdo para continuar";
                echo json_encode($json);
                return;
            }

            if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'][0])) {
                $upload = new Upload();


                $ext = ($_FILES['cover']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                $file_name = md5(microtime() . $_FILES['cover']['name'][0]) . $ext;
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_BLOG . "/";
                if ($upload->send($_FILES['cover'], $file_name, $path)) {
                    $thumb = (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_BLOG . "/" . $post->cover);
                    if (file_exists($path . $post->cover)) {
                        unlink($path . $post->cover);
                    }
                    $post->cover = $file_name;

                } else {
                    $json['message_warning'] = "Erro ao enviar imagem de destaque";
                    echo json_encode($json);
                    return;
                }
            }
            /*$post->author = $this->user->id;*/
            $post->category = !empty($category) ? $category : null;
            $post->title = $title;
            $post->slug = str_slug($title);
            $post->description = $description;
            $post->content = $content;
            $post->status = $status;

            if ($post->save()) {
                $this->message->success("Post atualizado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao criar o post";
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Editar post - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("updatePost", [
            "head" => $head,
            "categories" => (new Category())->find()->order("title DESC")->fetch(true),
            "post" => $post
        ]);
    }

    /**
     * @param array|null $data
     */
    public function pages(?array $data): void
    {
        $pages = new Post();

        //Delete
        if (isset($data['action']) && $data['action'] == "delete") {
            $pages = $pages->findById($data['id']);
            if (!empty($pages->cover)) {
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_BLOG . "/";
                $thumb = (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_BLOG . "/" . $pages->cover);
                if (file_exists($path . $pages->cover)) {
                    unlink($path . $pages->cover);
                }
            }
            if ($pages->destroy()) {
                $this->message->success("Página apagada com sucesso")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $this->message->error($pages->fail()->getMessage())->flash();
            echo json_encode(['refresh' => true]);
            return;
        }


        $head = $this->seo->render(
            "Páginas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("pages", [
            "head" => $head,
            "pages" => $pages->find("type = :t", "t=page")->order("id DESC")->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function createPage(?array $data): void
    {
        $page = new Post();

        //CREATE
        if (isset($data['csrf'])) {
            $title = filter_var($data['title'], FILTER_SANITIZE_STRIPPED);
            $description = filter_var($data['description'], FILTER_SANITIZE_STRIPPED);
            $status = filter_var($data['status'], FILTER_SANITIZE_STRIPPED);
            $content = $data['content'];

            $post_verify = $page->find("title = :t AND type = :type", "t={$title}&type=page")->count();
            if ($post_verify) {
                $json['message_warning'] = "Já existe uma página com este título, digite outro para continuar";
                echo json_encode($json);
                return;
            }

            if (!csrf_verify($data)) {
                $json['message_error'] = "Ooops! Tente novamente mais tarde!";
                echo json_encode($json);
                return;
            }
            if (empty(trim($title)) || strlen(trim($title)) < 2) {
                $json['message_warning'] = "Digite um título válido para continuar";
                echo json_encode($json);
                return;
            }
            if ($status != "post" && $status != "draft") {
                $json['message_warning'] = "Status inválido";
                echo json_encode($json);
                return;
            }

            if (empty(trim($content))) {
                $json['message_warning'] = "Escreva algum conteúdo para continuar";
                echo json_encode($json);
                return;
            }

            if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'][0])) {
                $upload = new Upload();

                $ext = ($_FILES['cover']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                $file_name = md5(microtime() . $_FILES['cover']['name'][0]) . $ext;
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_BLOG . "/";
                if ($upload->send($_FILES['cover'], $file_name, $path)) {
                    $page->cover = $file_name;
                } else {
                    $json['message_warning'] = "Erro ao enviar imagem de destaque";
                    echo json_encode($json);
                    return;
                }
            }
            $page->author = $this->user->id_user;
            $page->title = $title;
            $page->slug = str_slug($title);
            $page->description = $description;
            $page->content = $content;
            $page->status = $status;
            $page->type = "page";

            if ($page->save()) {
                $this->message->success("Página criada com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao criar a página";
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Novo post - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("createPage", [
            "head" => $head
        ]);
    }

    /**
     * @param array|null $data
     */
    public function updatePage(?array $data): void
    {
        $page = new Post();

        if (!$page = $page->find("id = :id AND type = :t", "id={$data['id']}&t=page")->fetch()) {
            $this->message->error("Página não encontrada")->flash();
            redirect("/panel/paginas");
            return;
        }

        //UPDATE
        if (isset($data['csrf'])) {
            $title = filter_var($data['title'], FILTER_SANITIZE_STRIPPED);
            $description = filter_var($data['description'], FILTER_SANITIZE_STRIPPED);
            $status = filter_var($data['status'], FILTER_SANITIZE_STRIPPED);
            $content = $data['content'];

            $post_verify = $page->find("title = :t AND type = :type AND id != :id", "t={$title}&type=page&id={$data['id']}")->count();
            if ($post_verify) {
                $json['message_warning'] = "Já existe uma página com este título, digite outro para continuar";
                echo json_encode($json);
                return;
            }

            if (!csrf_verify($data)) {
                $json['message_error'] = "Ooops! Tente novamente mais tarde!";
                echo json_encode($json);
                return;
            }
            if (empty(trim($title)) || strlen(trim($title)) < 2) {
                $json['message_warning'] = "Digite um título válido para continuar";
                echo json_encode($json);
                return;
            }
            if ($status != "post" && $status != "draft") {
                $json['message_warning'] = "Status inválido";
                echo json_encode($json);
                return;
            }

            if (empty(trim($content))) {
                $json['message_warning'] = "Escreva algum conteúdo para continuar";
                echo json_encode($json);
                return;
            }

            if (isset($_FILES['cover']) && !empty($_FILES['cover']['tmp_name'][0])) {
                $upload = new Upload();


                $ext = ($_FILES['cover']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                $file_name = md5(microtime() . $_FILES['cover']['name'][0]) . $ext;
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_BLOG . "/";
                if ($upload->send($_FILES['cover'], $file_name, $path)) {
                    $thumb = (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_BLOG . "/" . $page->cover);
                    if (file_exists($path . $page->cover)) {
                        unlink($path . $page->cover);
                    }
                    $page->cover = $file_name;

                } else {
                    $json['message_warning'] = "Erro ao enviar imagem de destaque";
                    echo json_encode($json);
                    return;
                }
            }
            /*$post->author = $this->user->id;*/
            $page->title = $title;
            $page->slug = str_slug($title);
            $page->description = $description;
            $page->content = $content;
            $page->status = $status;

            if ($page->save()) {
                $this->message->success("Página atualizado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao atualizar a página, entre em contato com o suporte";
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Editar página - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("updatePage", [
            "head" => $head,
            "page" => $page
        ]);
    }

    /**
     * @param array|null $data
     */
    public function createUser(?array $data): void
    {
        //Create User
        if (isset($data['csrf'])) {
            if (!csrf_verify($data)) {
                $json["message"] = $this->message->error("Erro! Tente novamente mais tarde!")->render();
                echo json_encode($json);
                return;
            }

            if (empty(trim($data['first_name']))) {
                $json["message"] = $this->message->warning("Favor preecher o nome")->render();
                echo json_encode($json);
                return;
            }

            if (!is_email($data['email'])) {
                $json["message"] = $this->message->warning("Informe um e-mail válido")->render();
                echo json_encode($json);
                return;
            }

            $emailVerify = (new User())->find("email = :e", "e={$data['email']}")->fetch();
            if ($emailVerify) {
                $json["message"] = $this->message->warning("O e-mail informado já está cadastrado")->render();
                echo json_encode($json);
                return;
            }

            if (empty($data["password"]) || empty($data["password_re"])) {
                $json["message"] = $this->message->warning("Informe e repita a senha para continuar")->render();
                echo json_encode($json);
                return;
            }

            if (!is_passwd($data['password'])) {
                $min = CONF_PASSWD_MIN_LEN;
                $max = CONF_PASSWD_MAX_LEN;
                $json["message"] = $this->message->warning("Senha deve ter entre {$min} e {$max} caracteres.")->render();
                echo json_encode($json);
                return;
            }

            if ($data['password'] != $data['password_re']) {
                $json["message"] = $this->message->warning("Você informou duas senhas diferentes.")->render();
                echo json_encode($json);
                return;
            }

            /** @var  $user User */
            $user = new User();
            $user = $user->register($data['first_name'], $data['email'], $data['password'], $data['phone_whatsapp'], $data['level'], $data['realtor'], "");
            if ($user) {
                $this->message->success("Usuário cadastrado com sucesso")->flash();
                $json['redirect'] = url("/panel/usuarios");
                echo json_encode($json);
                return;
            }
            if ($user->fail()) {
                $json["message"] = $this->message->error("Erro {$user->fail()->getMessage()}")->render();
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Criar novo usuário - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/panel/criar-usuario/"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("create_user", [
            "head" => $head
        ]);
    }


    /**
     * @param array $data
     */
    public function simulate(array $data): void
    {
        if (isset($data['id']) && is_numeric($data['id'])) {
            $user = (new User())->findById($data['id']);
            if ($user) {
                (new Session())->set("authUser", $user->id_user);
                $this->message->success("Você está simulando o usuário! Cuidado com as alterações de dados")->flash();
                redirect("/app");
            } else {
                $this->message->error("Não foi encontrado o usuário selecionado!")->flash();
                redirect("/panel");
            }
        }
    }

    /**
     * @param array|null $data
     */
    public function adverts(?array $data): void
    {
        $head = $this->seo->render(
            CONF_SITE_NAME . " | Anúncios no site",
            CONF_SITE_DESC,
            url("/panel"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("adverts", [
            "head" => $head,
            "adverts" => $adverts = (new Advert())->find('', '', 'id_property, city, state, created_at, final_date, id_user, active, property_slug')->order("id_property DESC")->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function profile(?array $data): void
    {
        if ($data) {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        }
        if (isset($data['first_name'])) {
            if (empty(trim($data['first_name'])) || strlen($data['first_name']) < 3) {
                $this->message->warning(" O seu nome deve ter no mínimo 3 caracteres")->flash();
                redirect("/panel/perfil");
            }
            if (empty(trim($data['last_name'])) || strlen($data['last_name']) < 3) {
                $this->message->warning(" O seu sobrenome deve ter no mínimo 3 caracteres")->flash();
                redirect("/panel/perfil");
            }
            $this->admin->first_name = $data['first_name'];
            $this->admin->last_name = $data['last_name'];
            $this->admin->save();
            $this->message->success(" Dados alterados com sucesso!")->flash();
            redirect("/panel/perfil");
        }

        if (isset($data['password'])) {

            if (empty(trim($data["password"])) || empty(trim($data["password_re"]))) {
                $this->message->info("Informe e repita a senha para continuar")->flash();
                redirect("/panel/perfil/");
            }

            if (!is_passwd($data['password'])) {
                $min = CONF_PASSWD_MIN_LEN;
                $max = CONF_PASSWD_MAX_LEN;
                $this->message->info("Senha deve ter entre {$min} e {$max} caracteres.")->flash();
                redirect("/panel/perfil/");
                exit();
            }

            if ($data['password'] != $data['password_re']) {
                $this->message->warning("Você informou duas senhas diferentes.")->flash();
                redirect("/panel/perfil/");
            }

            $password = passwd($data['password']);
            $this->admin->password = $password;
            $this->admin->save();
            if ($this->admin->fail()) {
                $this->message->error("Oops, ocorreu um erro, tente mais tarde!")->flash();
                redirect("/panel/perfil");
            } else {
                $this->message->success("Senha foi alterada com sucesso!")->flash();
                redirect("/panel/perfil");
            }
        }

        $head = $this->seo->render(
            "Editar meu perfil - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("profile", [
            "head" => $head,
            "user" => $this->user
        ]);

    }

    /**
     * @param array|null $data
     */
    public function tag(?array $data): void
    {
        $tags = new Option();

        //create
        if (isset($data['action']) && $data['action'] == "create") {
            if (empty(trim($data['option_name'])) || empty(trim($data['option_value']))) {
                $json['message_warning'] = "Preencha todos os campos";
                echo json_encode($json);
                return;
            }
            $tags->option_name = $data['option_name'];
            $tags->option_value = $data['option_value'];
            $tags->save();
            $this->message->success("Tag criada com sucesso!")->flash();
            $json['redirect'] = url("/panel/tag");
            echo json_encode($json);
            return;
        }

        //delete
        if (isset($data['action']) && $data['action'] == "delete") {
            $tags = $tags->findById($data['id']);
            $tags->destroy();
            $this->message->success("Tag apagada com sucesso!")->flash();
            $json['redirect'] = url("/panel/tag");
            echo json_encode($json);
            return;
        }

        //update
        if (isset($data['action']) && $data['action'] == "update") {
            if (empty(trim($data['option_name'])) || empty(trim($data['option_value']))) {
                $json['message_warning'] = "Preencha todos os campos";
                echo json_encode($json);
                return;
            }
            $tags = $tags->findById($data['id']);
            if ($tags) {
                $tags->option_name = $data['option_name'];
                $tags->option_value = $data['option_value'];
                $tags->save();
                $this->message->success("Tag atualizada com sucesso!")->flash();
                $json['redirect'] = url("/panel/tag");
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Tags - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("tag", [
            "head" => $head,
            "tags" => $tags->find()->fetch(true)
        ]);
    }


    /**
     * @param array|null $data
     */
    public function configuration(?array $data): void
    {
        $option = new Option();

        //update
        if (isset($data['action']) && $data['action'] == "update") {
            if (empty(trim($data['CONF_SITE_NAME'])) || empty(trim($data['CONF_SITE_TITLE'])) || empty(trim($data['CONF_SITE_DESC']))) {
                $this->message->warning("Os campos nome do site, título do site e descrição não podem ser vazios!")->flash();
                redirect("/panel/configuração");
                return;
            }
            $update = $option->update_at($data);
            if ($update) {
                //Upload da Logo
                if (isset($_FILES['logo']) && !empty($_FILES['logo']['tmp_name'][0])) {
                    $upload = new Upload();

                    if ($data['CONF_SITE_LOGO']) {
                        (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_SITE . "/" . $data['CONF_SITE_LOGO']);
                        $upload->remove($data['CONF_SITE_LOGO'], __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/");
                    }

                    $ext = ($_FILES['logo']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                    $file_name = md5(microtime() . $_FILES['logo']['name'][0]) . $ext;
                    $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/";

                    if ($upload->send($_FILES['logo'], $file_name, $path)) {
                        $tag[$data['tag']] = $file_name;
                        $update = $option->update_at($tag);
                    } else {
                        $upload->message()->before("Oops! ")->flash();
                    }
                }

                $this->message->success("Dados Alterados com sucesso!")->flash();
            } else {
                $option->message->before("Ooops! ")->flash();
            }
            redirect("/panel/configuração");
        }

        if (isset($_FILES['image']) && $data['tag']) {

            if (!empty($_FILES['image']['tmp_name'][0])) {
                $upload = new Upload();

                if ($data['name_image']) {
                    (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_SITE . "/" . $data['name_image']);
                    $upload->remove($data['name_image'], __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/");
                }


                $ext = ($_FILES['image']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                $file_name = md5(microtime() . $_FILES['image']['name'][0]) . $ext;
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/";

                if ($upload->send($_FILES['image'], $file_name, $path)) {
                    $tag[$data['tag']] = $file_name;
                    $update = $option->update_at($tag);
                    $json["message"] = $this->message->success(" Imagem atualizada com sucesso.")->render();
                    echo json_encode($json);
                    return;
                } else {
                    $json['message'] = $upload->message()->before("Oops! ")->render();
                    echo json_encode($json);
                    return;
                }
            } else {
                $json["message"] = $this->message->warning("Você não selecionou nenhuma imagem, tente novamente")->render();
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Configurações - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("configuration", [
            "head" => $head,
            "option" => $option
        ]);
    }

    /**
     * @param array|null $data
     */
    public function banner(?array $data): void
    {
        $option = new Option();

        //update
        if (isset($data['action']) && $data['action'] == "update") {
            if (empty(trim($data['CONF_SITE_BANNER_TITLE']))) {
                $this->message->warning("O campo título não pode ser vazio!")->flash();
                redirect("/panel/banner");
                return;
            }
            $update = $option->update_at($data);
            if ($update) {
                //Upload da Logo
                if (isset($_FILES['logo']) && !empty($_FILES['logo']['tmp_name'][0])) {
                    $upload = new Upload();

                    if ($data['CONF_SITE_LOGO']) {
                        (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_SITE . "/" . $data['CONF_SITE_LOGO']);
                        $upload->remove($data['CONF_SITE_LOGO'], __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/");
                    }

                    $ext = ($_FILES['logo']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                    $file_name = md5(microtime() . $_FILES['logo']['name'][0]) . $ext;
                    $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/";

                    if ($upload->send($_FILES['logo'], $file_name, $path)) {
                        $tag[$data['tag']] = $file_name;
                        $update = $option->update_at($tag);
                    } else {
                        $upload->message()->before("Oops! ")->flash();
                    }
                }

                $this->message->success("Dados Alterados com sucesso!")->flash();
            } else {
                $option->message->before("Ooops! ")->flash();
            }
            redirect("/panel/banner");
        }

        if (isset($_FILES['image']) && $data['tag']) {

            if (!empty($_FILES['image']['tmp_name'][0])) {
                $upload = new Upload();

                if ($data['name_image']) {
                    (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_SITE . "/" . $data['name_image']);
                    $upload->remove($data['name_image'], __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/");
                }


                $ext = ($_FILES['image']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                $file_name = md5(microtime() . $_FILES['image']['name'][0]) . $ext;
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/";

                if ($upload->send($_FILES['image'], $file_name, $path)) {
                    $tag[$data['tag']] = $file_name;
                    $update = $option->update_at($tag);
                    $json["message"] = $this->message->success(" Imagem atualizada com sucesso.")->render();
                    echo json_encode($json);
                    return;
                } else {
                    $json['message'] = $upload->message()->before("Oops! ")->render();
                    echo json_encode($json);
                    return;
                }
            } else {
                $json["message"] = $this->message->warning("Você não selecionou nenhuma imagem, tente novamente")->render();
                echo json_encode($json);
                return;
            }
        }

        $head = $this->seo->render(
            "Banner - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("banner", [
            "head" => $head,
            "option" => $option
        ]);
    }

    /**
     * @param array|null $data
     */
    public function scripts(?array $data): void
    {
        $option = new Option();

        //update
        if (isset($data['action']) && $data['action'] == "update") {

            $update = $option->update_at($data);
            if ($update) {
                //Upload da Logo
                if (isset($_FILES['logo']) && !empty($_FILES['logo']['tmp_name'][0])) {
                    $upload = new Upload();

                    if ($data['CONF_SITE_LOGO']) {
                        (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_SITE . "/" . $data['CONF_SITE_LOGO']);
                        $upload->remove($data['CONF_SITE_LOGO'], __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/");
                    }

                    $ext = ($_FILES['logo']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                    $file_name = md5(microtime() . $_FILES['logo']['name'][0]) . $ext;
                    $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/";

                    if ($upload->send($_FILES['logo'], $file_name, $path)) {
                        $tag[$data['tag']] = $file_name;
                        $update = $option->update_at($tag);
                    } else {
                        $upload->message()->before("Oops! ")->flash();
                    }
                }

                $this->message->success("Dados Alterados com sucesso!")->flash();
            } else {
                $option->message->before("Ooops! ")->flash();
            }
            redirect("/panel/scripts");
        }

        $head = $this->seo->render(
            "Scripts - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("scripts", [
            "head" => $head,
            "option" => $option
        ]);
    }

    /**
     * @param array|null $data
     */
    public function images(?array $data): void
    {
        $option = new Option();
        if (isset($_FILES['image']) && $data['tag']) {

            if (!empty($_FILES['image']['tmp_name'][0])) {
                $upload = new Upload();

                if ($data['name_image']) {
                    (new Thumb())->flush(CONF_UPLOAD_IMAGE_DIR_SITE . "/" . $data['name_image']);
                    $upload->remove($data['name_image'], __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/");
                }


                $ext = ($_FILES['image']['type'][0] == 'image/jpeg' ? '.jpg' : '.png');
                $file_name = md5(microtime() . $_FILES['image']['name'][0]) . $ext;
                $path = __DIR__ . "/../../../" . CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR_SITE . "/";

                if ($upload->send($_FILES['image'], $file_name, $path)) {
                    $tag[$data['tag']] = $file_name;
                    $update = $option->update_at($tag);
                    $json["message"] = $this->message->success(" Imagem atualizada com sucesso.")->render();
                    echo json_encode($json);
                    return;
                } else {
                    $json['message'] = $upload->message()->before("Oops! ")->render();
                    echo json_encode($json);
                    return;
                }
            } else {
                $json["message"] = $this->message->warning("Você não selecionou nenhuma imagem, tente novamente")->render();
                echo json_encode($json);
                return;
            }
        }

        //update
        if (isset($data['action']) && $data['action'] == "update") {
            if (empty(trim($data['CONF_SITE_NAME'])) || empty(trim($data['CONF_SITE_TITLE'])) || empty(trim($data['CONF_SITE_DESC']))) {
                $this->message->warning("Os campos nome do site, título do site e descrição não podem ser vazios!")->flash();
                redirect("/panel/configuração");
                return;
            }

            $update = $option->update_at($data);
            if ($update) {
                $this->message->success("Dados Alterados com sucesso!")->flash();
            } else {
                $option->message->before("Ooops! ")->flash();
            }
            redirect("/panel/configuração");
        }

        $head = $this->seo->render(
            "Configurações - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("images", [
            "head" => $head,
            "option" => $option
        ]);
    }

    /**
     * @param array|null $data
     */
    public function categoryFaq(?array $data): void
    {
        $category = new \Source\Models\Faq\Category();

        if (isset($data['action']) && $data['action'] == "create") {
            if (empty(trim($data['title']))) {
                $json['message_warning'] = "O nome da categoria não pode ser vazio";
                echo json_encode($json);
                return;
            }
            $category->title = $data['title'];
            if ($category->save()) {
                $this->message->success("Categoria foi criada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            if ($category->fail()) {
                $this->message->error("Erro ao cadastrar a categoria! Entre em contato com o suporte")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
        }
        if (isset($data['action']) && $data['action'] == "update") {
            if (empty(trim($data['title']))) {
                $json['message_warning'] = "O nome da categoria não pode ser vazio";
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['id'])) || !is_numeric($data['id'])) {
                $json['message_warning'] = "Categoria inválida";
                echo json_encode($json);
                return;
            }
            $category = $category->findById($data['id']);
            if (!$category) {
                $json['message_warning'] = "Categoria inválida";
                echo json_encode($json);
                return;
            }
            $category->title = $data['title'];
            if ($category->save()) {
                $this->message->success("Categoria atualizada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            if ($category->fail()) {
                $this->message->error("Erro ao atualizar a categoria! Entre em contato com o suporte")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
        }
        if (isset($data['action']) && $data['action'] == "delete") {
            if (empty(trim($data['id'])) || !is_numeric($data['id'])) {
                $json['message_warning'] = "Categoria inválida";
                echo json_encode($json);
                return;
            }
            $category = $category->findById($data['id']);
            if (!$category) {
                $json['message_warning'] = "Categoria inválida";
                echo json_encode($json);
                return;
            }
            if ($category->destroy()) {
                $this->message->success("Categoria apagada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            if ($category->fail()) {
                $this->message->error("Erro ao atualizar a categoria! Entre em contato com o suporte")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
        }

        $head = $this->seo->render(
            "Categorias das Perguntas Frequentes | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/panel"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("categoryFaq", [
            "head" => $head,
            "categories" => $category->find()->fetch(true)
        ]);
    }

    /**
     * @param array|null $data
     */
    public function faqs(?array $data): void
    {
        $faq = new Faq();
        //Validação de campos
        if (isset($data['action']) && ($data['action'] == "create" || $data['action'] == "update")) {
            if (empty(trim($data['title']))) {
                $json['message_warning'] = "O campo pergunta não pode ser vazio";
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['description']))) {
                $json['message_warning'] = "O campo resposta não pode ser vazio";
                echo json_encode($json);
                return;
            }
            if (empty(trim($data['category'])) || !is_numeric($data['category'])) {
                $json['message_warning'] = "Selecione uma categoria para a pergunta";
                echo json_encode($json);
                return;
            }
        }
        if (isset($data['action']) && $data['action'] == "create") {
            $faq->title = $data['title'];
            $faq->description = $data['description'];
            $faq->category = $data['category'];
            if ($faq->save()) {
                $this->message->success("FAQ foi criada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            if ($faq->fail()) {
                var_dump($faq->fail());
                return;
                $this->message->error("Erro ao cadastrar! Entre em contato com o suporte")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
        }
        if (isset($data['action']) && $data['action'] == "update") {
            if (empty(trim($data['id_faq'])) || !is_numeric($data['id_faq'])) {
                $json['message_warning'] = "FAQ Inválida";
                echo json_encode($json);
                return;
            }
            $faq = $faq->findById($data['id_faq']);
            if (!$faq) {
                $json['message_warning'] = "FAQ inválida";
                echo json_encode($json);
                return;
            }
            $faq->title = $data['title'];
            $faq->description = $data['description'];
            $faq->category = $data['category'];
            if ($faq->save()) {
                $this->message->success("FAQ atualizada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            if ($faq->fail()) {
                $this->message->error("Erro ao atualizar! Entre em contato com o suporte")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
        }
        if (isset($data['action']) && $data['action'] == "delete") {
            if (empty(trim($data['id_faq'])) || !is_numeric($data['id_faq'])) {
                $json['message_warning'] = "FAQ inválida";
                echo json_encode($json);
                return;
            }
            $faq = $faq->findById($data['id_faq']);
            if (!$faq) {
                $json['message_warning'] = "FAQ inválida";
                echo json_encode($json);
                return;
            }
            if ($faq->destroy()) {
                $this->message->success("FAQ apagada com sucesso!")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
            if ($faq->fail()) {
                $this->message->error("Erro ao apagar! Entre em contato com o suporte")->flash();
                echo json_encode(["refresh" => true]);
                return;
            }
        }

        $head = $this->seo->render(
            "Perguntas Frequentes | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/panel"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("faqs", [
            "head" => $head,
            "faqs" => $faq->find('', '', 'id_faq, title, category')->fetch(true)
        ]);
    }

    public function createFaq()
    {
        $head = $this->seo->render(
            "Nova FAQ | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/panel"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("createFaq", [
            "head" => $head,
            "categories" => (new \Source\Models\Faq\Category())->find()->fetch(true)
        ]);
    }

    public function updateFaq(array $data)
    {
        if (!isset($data['id_faq']) || !is_numeric($data['id_faq']) || !$faq = (new Faq())->findById($data['id_faq'])) {
            $this->message->info("Não encontramos a pergunta frequente")->flash();
            redirect("/panel/faqs");
        }

        $head = $this->seo->render(
            "Editar FAQ | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url("/panel"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("updateFaq", [
            "head" => $head,
            "categories" => (new \Source\Models\Faq\Category())->find()->fetch(true),
            "faq" => $faq
        ]);
    }

    /**
     * APP LOGOUT
     */
    public function logout()
    {
        (new Message())->info(" Você saiu com sucesso " . $this->user->first_name . ". Volte logo :)")->flash();
        User::logout();
        redirect("/panel/login");
    }
}