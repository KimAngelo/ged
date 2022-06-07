<?php


namespace Source\Controller;

use Source\Core\Controller;
use Source\Core\Session;
use Source\Models\Company;
use Source\Models\Modules\Bidding;
use Source\Models\Modules\Contract;
use Source\Models\Modules\Convention;
use Source\Models\Modules\Expense;
use Source\Models\Modules\Legislation;
use Source\Models\Modules\Report;
use Source\Models\Modules\Sign;
use Source\Models\Upload;
use Source\Models\User;
use Source\Support\Pager;

/**
 * Class App
 * @package Source\Controller
 */
class App extends Controller
{
    private $session;

    private $user;

    private $company;

    /**
     * App constructor.
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
    public function monitoring(?array $data): void
    {
        $total_documents = $this->company->expense_total_documents + $this->company->bidding_total_documents +
            $this->company->contract_total_documents + $this->company->convention_total_documents + $this->company->legislation_total_documents + $this->company->report_total_documents;

        $total_pages = $this->company->expense_total_pages + $this->company->bidding_total_pages + $this->company->contract_total_pages +
            $this->company->convention_total_pages + $this->company->legislation_total_pages + $this->company->report_total_pages;
        $reports = [
            "expense_total_documents" => $this->company->expense_total_documents,
            "expense_total_pages" => $this->company->expense_total_pages,
            "bidding_total_documents" => $this->company->bidding_total_documents,
            "bidding_total_pages" => $this->company->bidding_total_pages,
            "contract_total_documents" => $this->company->contract_total_documents,
            "contract_total_pages" => $this->company->contract_total_pages,
            "legislation_total_documents" => $this->company->legislation_total_documents,
            "legislation_total_pages" => $this->company->legislation_total_pages,
            "report_total_documents" => $this->company->report_total_documents,
            "report_total_pages" => $this->company->report_total_pages,
            "convention_total_documents" => $this->company->convention_total_documents,
            "convention_total_pages" => $this->company->convention_total_pages,
            "total_documents" => $total_documents,
            "total_pages" => $total_pages
        ];

        $head = $this->seo->render(
            "Monitoramento | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.monitoring"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("monitoring", [
            "head" => $head,
            "reports" => (object)$reports
        ]);
    }

    /**
     * @param array|null $data
     * Controlador para alterar senha do usuário
     */
    public function updatePassword(?array $data): void
    {
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
            $this->user->password = passwd($data['password']);
            if ($this->user->save()) {
                $this->message->success("Sua senha foi atualizada com sucesso {$this->user->first_name}!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            if ($this->user->fail()) {
                $json['message_error'] = "Erro ao atualizar a senha do usuário, entre em contato com o suporte!";
                echo json_encode($json);
                return;
            }
        }

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
    public function expenses(?array $data): void
    {
        $expense = new Expense();
        if (!$expense->permission(explode(';', $this->user->roles))) {
            $this->message->error("Você não tem permissão para acessar esse módulo")->flash();
            $this->router->redirect("app.search");
        }

        if (isset($data['action']) && $data['action'] == "send_document_email") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!is_email($data['email'])) {
                $json['message_warning'] = "Preencha o campo com um e-mail válido";
                echo json_encode($json);
                return;
            }
            if (!$expense = $expense->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja encaminhar por e-mail";
                echo json_encode($json);
                return;
            }
            //Faz o envio do e-mail
            $email = new \Source\Support\Email();
            $view = new \Source\Core\View($this->router, __DIR__ . "/../../shared/views/email");
            $subject = "[DESPESA] documento número {$expense->number_expense}";
            $message = "<p>{$this->user->first_name} te encaminhou este documento.</p>
                        <p>Para abrir o arquivo, basta clicar no link abaixo</p>
                        <p><a target='_blank' href='" . storage($expense->document_name, $this->company->id . "/" . CONF_UPLOAD_EXPENSE) . "'>ABRIR DOCUMENTO</a></p>            
";
            $body = $view->render("mail", [
                "subject" => $subject,
                "message" => $message
            ]);
            $email->bootstrap(
                $subject,
                $body,
                $data['email'],
                ''
            )->send();
            $this->message->success("Documento encaminhado com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "sign") {
            if ($this->user->sign != 1) {
                echo json_encode(['message_warning' => "Você não tem permissão para assinar documentos"]);
                return;
            }
            if (!isset($data['documents']) || in_array('', $data['documents'])) {
                echo json_encode(['message_warning' => "Selecione algum documento para assinar"]);
                return;
            }
            if (empty($this->company->certificate_pem)) {
                echo json_encode(['message_warning' => "Você não possui certificado digital"]);
                return;
            }

            foreach ($data['documents'] as $document) {
                $file = (new Expense())->find("id = :id AND id_company = :id_company", "id={$document}&id_company={$this->company->id}")->fetch();
                if (!$file) {
                    echo json_encode(['message_warning' => "Esse documento não pertence a sua empresa"]);
                    return;
                }
                $sign = (new Sign())->index(
                    storage($file->document_name, $this->company->id . "/" . CONF_UPLOAD_EXPENSE),
                    $this->company,
                    CONF_UPLOAD_EXPENSE,
                    $file->document_name,
                    $new_file_name = 'signed_' . $file->document_name
                );
                $file->document_name = $new_file_name;
                $file->signed = 'true';
                $file->save();
            }
            $this->message->success("Documentos assinados com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "delete") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!$expense = $expense->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja excluir";
                echo json_encode($json);
                return;
            }

            $this->company->expense_total_documents -= 1;
            $this->company->expense_total_pages -= $expense->total_page;
            $this->company->save();

            if ($expense->destroy()) {
                //Apaga o documento
                (new Upload())->removeStorage($this->company->id . "/" . CONF_UPLOAD_EXPENSE . $expense->document_name);

                $this->message->success("Documento apagado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao apagar documento";
            echo json_encode($json);
            return;
        }

        $page = isset($_GET['page']) ? filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT) : 1;
        $where = "";
        $params = "";

        if (isset($_GET['filter']) && $_GET['filter'] == 's') {
            $number_expense = filter_input(INPUT_GET, 'number_expense', FILTER_SANITIZE_STRIPPED);
            $date_start = filter_input(INPUT_GET, 'date_start', FILTER_SANITIZE_STRIPPED);
            $date_end = filter_input(INPUT_GET, 'date_end', FILTER_SANITIZE_STRIPPED);
            $favored = filter_input(INPUT_GET, 'favored', FILTER_SANITIZE_STRIPPED);
            $source = filter_input(INPUT_GET, 'source', FILTER_SANITIZE_STRIPPED);
            $value_start = filter_input(INPUT_GET, 'value_start', FILTER_SANITIZE_STRIPPED);
            $value_end = filter_input(INPUT_GET, 'value_end', FILTER_SANITIZE_STRIPPED);
            $historical = filter_input(INPUT_GET, 'historical', FILTER_SANITIZE_STRIPPED);

            if (!empty(trim($number_expense))) {
                $where .= " AND number_expense LIKE :number_expense";
                $params .= "&number_expense=" . urlencode("%{$number_expense}%");
            }
            if (!empty(trim($date_start)) && is_date($date_start) && !empty(trim($date_end)) && is_date($date_end)) {
                $date_start = date_fmt($date_start, 'Y-m-d');
                $date_end = date_fmt($date_end, 'Y-m-d');
                $where .= " AND date BETWEEN :date_start AND :date_end";
                $params .= "&date_start={$date_start}&date_end={$date_end}";
            }
            if ((!empty($date_start) && is_date($date_start) && empty($date_end)) || ((!empty($date_end) && is_date($date_end) && empty($date_start)))) {
                $date = !empty($date_start) ? date_fmt($date_start, 'Y-m-d') : date_fmt($date_end, 'Y-m-d');
                $where .= " AND date = :date";
                $params .= "&date={$date}";
            }
            if (!empty(trim($favored))) {
                $where .= " AND favored LIKE :f";
                $params .= "&f=%{$favored}%";
            }
            if (!empty(trim($source))) {
                $where .= " AND source LIKE :s";
                $params .= "&s=" . urlencode("%{$source}%");
            }
            if (!empty(trim($value_start)) && !empty(trim($value_end))) {
                $value_start = str_price_decimal($value_start);
                $value_end = str_price_decimal($value_end);
                $where .= " AND value BETWEEN :value_start AND :value_end";
                $params .= "&value_start={$value_start}&value_end={$value_end}";
            }
            if ((!empty($value_start) && empty($value_end)) || (empty($value_start) && !empty($value_end))) {
                $value = !empty($value_start) ? str_price_decimal($value_start) : str_price_decimal($value_end);
                $where .= " AND value = :value";
                $params .= "&value={$value}";
            }
            if (!empty(trim($historical))) {
                $where .= " AND historical LIKE :h";
                $params .= "&h=%{$historical}%";
            }

            $expenses = $expense->find("id_company = :id_company{$where}", "id_company={$this->company->id}{$params}");

            $pager = new Pager($this->router->route('app.expenses', [
                'filter' => 's',
                'number_expense' => $number_expense,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'favored' => $favored,
                'source' => $source,
                'value_start' => $value_start,
                'value_end' => $value_end,
                'historical' => $historical,
                'page' => ""
            ]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($expenses->count(), 30, $page);
        } else {
            $xpenses = $expense->find('id_company = :id_company', "id_company={$this->company->id}");

            $pager = new Pager($this->router->route('app.expenses',
                ['page' => ""]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($xpenses->count(), 30, $page);

        }

        $expanses = $expense->offset($pager->offset())->limit($pager->limit())->order('date DESC')->fetch(true);

        $head = $this->seo->render(
            "Despesas | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.expenses"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("expenses", [
            "head" => $head,
            "expenses" => $expanses,
            "render" => $pager->render(),
            "release_subscription" => !empty($this->company->certificate_pem) && $this->user->sign == 1 ? true : false,
            "delete_document" => (integer)$this->user->delete_document === 1
        ]);
    }

    /**
     * @param array|null $data
     */
    public function bidding(?array $data): void
    {
        $bidding = new Bidding();
        if (!$bidding->permission(explode(';', $this->user->roles))) {
            $this->message->error("Você não tem permissão para acessar esse módulo")->flash();
            $this->router->redirect("app.search");
        }

        if (isset($data['action']) && $data['action'] == "sign") {
            if ($this->user->sign != 1) {
                echo json_encode(['message_warning' => "Você não tem permissão para assinar documentos"]);
                return;
            }
            if (!isset($data['documents']) || in_array('', $data['documents'])) {
                echo json_encode(['message_warning' => "Selecione algum documento para assinar"]);
                return;
            }
            if (empty($this->company->certificate_pem)) {
                echo json_encode(['message_warning' => "Você não possui certificado digital"]);
                return;
            }

            foreach ($data['documents'] as $document) {
                $file = (new Bidding())->find("id = :id AND id_company = :id_company", "id={$document}&id_company={$this->company->id}")->fetch();
                if (!$file) {
                    echo json_encode(['message_warning' => "Esse documento não pertence a sua empresa"]);
                    return;
                }
                $sign = (new Sign())->index(
                    storage($file->document_name, $this->company->id . "/" . CONF_UPLOAD_BIDDING),
                    $this->company,
                    CONF_UPLOAD_BIDDING,
                    $file->document_name,
                    $new_file_name = 'signed_' . $file->document_name
                );
                $file->document_name = $new_file_name;
                $file->signed = 'true';
                $file->save();
            }
            $this->message->success("Documentos assinados com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "send_document_email") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!is_email($data['email'])) {
                $json['message_warning'] = "Preencha o campo com um e-mail válido";
                echo json_encode($json);
                return;
            }
            if (!$bidding = $bidding->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja encaminhar por e-mail";
                echo json_encode($json);
                return;
            }
            //Faz o envio do e-mail
            $email = new \Source\Support\Email();
            $view = new \Source\Core\View($this->router, __DIR__ . "/../../shared/views/email");
            $subject = "[LICITAÇÃO] N° do processo {$bidding->number_process}";
            $message = "<p>{$this->user->first_name} te encaminhou este documento.</p>
                        <p>Para abrir o arquivo, basta clicar no link abaixo</p>
                        <p><a target='_blank' href='" . storage($bidding->document_name, $this->company->id . "/" . CONF_UPLOAD_BIDDING) . "'>ABRIR DOCUMENTO</a></p>            
";
            $body = $view->render("mail", [
                "subject" => $subject,
                "message" => $message
            ]);
            $email->bootstrap(
                $subject,
                $body,
                $data['email'],
                ''
            )->send();
            $this->message->success("Documento encaminhado com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "delete") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!$bidding = $bidding->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja excluir";
                echo json_encode($json);
                return;
            }

            $this->company->bidding_total_documents -= 1;
            $this->company->bidding_total_pages -= $bidding->total_page;
            $this->company->save();

            if ($bidding->destroy()) {
                //Apaga o documento
                (new Upload())->removeStorage($this->company->id . "/" . CONF_UPLOAD_BIDDING . $bidding->document_name);

                $this->message->success("Documento apagado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao apagar documento";
            echo json_encode($json);
            return;
        }

        $page = isset($_GET['page']) ? filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT) : 1;
        $where = "";
        $params = "";

        if (isset($_GET['filter']) && $_GET['filter'] == 's') {
            $number_process = filter_input(INPUT_GET, 'number_process', FILTER_SANITIZE_STRIPPED);
            $date_start = filter_input(INPUT_GET, 'date_start', FILTER_SANITIZE_STRIPPED);
            $date_end = filter_input(INPUT_GET, 'date_end', FILTER_SANITIZE_STRIPPED);
            $modality = filter_input(INPUT_GET, 'modality', FILTER_SANITIZE_NUMBER_INT);
            $object = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_STRIPPED);

            if (!empty(trim($number_process))) {
                $where .= " AND (number_process LIKE :number_process or number_modality LIKE :number_modality)";
                $params .= "&number_process=" . urlencode("%{$number_process}%") . "&number_modality=" . urlencode("%{$number_process}%");
            }
            if (!empty(trim($date_start)) && is_date($date_start) && !empty(trim($date_end)) && is_date($date_end)) {
                $date_start = date_fmt($date_start, 'Y-m-d');
                $date_end = date_fmt($date_end, 'Y-m-d');
                $where .= " AND date BETWEEN :date_start AND :date_end";
                $params .= "&date_start={$date_start}&date_end={$date_end}";
            }
            if ((!empty($date_start) && is_date($date_start) && empty($date_end)) || ((!empty($date_end) && is_date($date_end) && empty($date_start)))) {
                $date = !empty($date_start) ? date_fmt($date_start, 'Y-m-d') : date_fmt($date_end, 'Y-m-d');
                $where .= " AND date = :date";
                $params .= "&date={$date}";
            }
            if (!empty(trim($modality))) {
                $where .= " AND modality = :modality";
                $params .= "&modality={$modality}";
            }
            if (!empty(trim($object))) {
                $where .= " AND object LIKE :object";
                $params .= "&object=" . urlencode("%{$object}%");
            }

            $biddings = $bidding->find("id_company = :id_company{$where}", "id_company={$this->company->id}{$params}");

            $pager = new Pager($this->router->route('app.bidding', [
                'filter' => 's',
                'number_process' => $number_process,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'modality' => $modality,
                'object' => $object,
                'page' => ""
            ]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($biddings->count(), 30, $page, 2);
        } else {
            $biddings = $bidding->find('id_company = :id_company', "id_company={$this->company->id}");

            $pager = new Pager($this->router->route('app.bidding',
                ['page' => ""]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($biddings->count(), 30, $page, 2);

        }

        $biddings = $bidding->offset($pager->offset())->limit($pager->limit())->order('date DESC')->fetch(true);

        $head = $this->seo->render(
            "Licitação | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.bidding"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("bidding", [
            "head" => $head,
            "biddings" => $biddings,
            "render" => $pager->render(),
            "release_subscription" => !empty($this->company->certificate_pem) && $this->user->sign == 1 ? true : false,
            "delete_document" => (integer)$this->user->delete_document === 1
        ]);
    }

    /**
     * @param array|null $data
     */
    public function contract(?array $data): void
    {
        $contract = new Contract();
        if (!$contract->permission(explode(';', $this->user->roles))) {
            $this->message->error("Você não tem permissão para acessar esse módulo")->flash();
            $this->router->redirect("app.search");
        }

        if (isset($data['action']) && $data['action'] == "sign") {
            if ($this->user->sign != 1) {
                echo json_encode(['message_warning' => "Você não tem permissão para assinar documentos"]);
                return;
            }
            if (!isset($data['documents']) || in_array('', $data['documents'])) {
                echo json_encode(['message_warning' => "Selecione algum documento para assinar"]);
                return;
            }
            if (empty($this->company->certificate_pem)) {
                echo json_encode(['message_warning' => "Você não possui certificado digital"]);
                return;
            }

            foreach ($data['documents'] as $document) {
                $file = (new Contract())->find("id = :id AND id_company = :id_company", "id={$document}&id_company={$this->company->id}")->fetch();
                if (!$file) {
                    echo json_encode(['message_warning' => "Esse documento não pertence a sua empresa"]);
                    return;
                }
                $sign = (new Sign())->index(
                    storage($file->document_name, $this->company->id . "/" . CONF_UPLOAD_CONTRACT),
                    $this->company,
                    CONF_UPLOAD_CONTRACT,
                    $file->document_name,
                    $new_file_name = 'signed_' . $file->document_name
                );
                $file->document_name = $new_file_name;
                $file->signed = 'true';
                $file->save();
            }
            $this->message->success("Documentos assinados com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "send_document_email") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!is_email($data['email'])) {
                $json['message_warning'] = "Preencha o campo com um e-mail válido";
                echo json_encode($json);
                return;
            }
            if (!$contract = $contract->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja encaminhar por e-mail";
                echo json_encode($json);
                return;
            }
            //Faz o envio do e-mail
            $email = new \Source\Support\Email();
            $view = new \Source\Core\View($this->router, __DIR__ . "/../../shared/views/email");
            $subject = "[CONTRATO] N° do contrato {$contract->number_contract}";
            $message = "<p>{$this->user->first_name} te encaminhou este documento.</p>
                        <p>Para abrir o arquivo, basta clicar no link abaixo</p>
                        <p><a target='_blank' href='" . storage($contract->document_name, $this->company->id . "/" . CONF_UPLOAD_CONTRACT) . "'>ABRIR DOCUMENTO</a></p>            
";
            $body = $view->render("mail", [
                "subject" => $subject,
                "message" => $message
            ]);
            $email->bootstrap(
                $subject,
                $body,
                $data['email'],
                ''
            )->send();
            $this->message->success("Documento encaminhado com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "delete") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!$contract = $contract->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja excluir";
                echo json_encode($json);
                return;
            }

            $this->company->contract_total_documents -= 1;
            $this->company->contract_total_pages -= $contract->total_page;
            $this->company->save();

            if ($contract->destroy()) {
                //Apaga o documento
                (new Upload())->removeStorage($this->company->id . "/" . CONF_UPLOAD_CONTRACT . $contract->document_name);

                $this->message->success("Documento apagado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao apagar documento";
            echo json_encode($json);
            return;
        }

        $page = isset($_GET['page']) ? filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT) : 1;
        $where = "";
        $params = "";

        if (isset($_GET['filter']) && $_GET['filter'] == 's') {
            $number_contract = filter_input(INPUT_GET, 'number_contract', FILTER_SANITIZE_STRIPPED);
            $type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_NUMBER_INT);
            $object = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_STRIPPED);

            if (!empty(trim($number_contract))) {
                $where .= " AND number_contract LIKE :number_contract";
                $params .= "&number_contract=" . urlencode("%{$number_contract}%");
            }
            if (!empty(trim($type))) {
                $where .= " AND type = :type";
                $params .= "&type={$type}";
            }
            if (!empty(trim($object))) {
                $where .= " AND object LIKE :object";
                $params .= "&object=" . urlencode("%{$object}%");
            }

            $contracts = $contract->find("id_company = :id_company{$where}", "id_company={$this->company->id}{$params}");

            $pager = new Pager($this->router->route('app.contract', [
                'filter' => 's',
                'number_contract' => $number_contract,
                'type' => $type,
                'object' => $object,
                'page' => ""
            ]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($contracts->count(), 30, $page, 2);
        } else {
            $contracts = $contract->find('id_company = :id_company', "id_company={$this->company->id}");

            $pager = new Pager($this->router->route('app.contract',
                ['page' => ""]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($contracts->count(), 30, $page, 2);

        }

        $contracts = $contract->offset($pager->offset())->limit($pager->limit())->order('date DESC')->fetch(true);

        $head = $this->seo->render(
            "Contrato | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.contract"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("contract", [
            "head" => $head,
            "contracts" => $contracts,
            "render" => $pager->render(),
            "release_subscription" => !empty($this->company->certificate_pem) && $this->user->sign == 1 ? true : false,
            "delete_document" => (integer)$this->user->delete_document === 1
        ]);
    }

    /**
     * @param array|null $data
     */
    public function legislation(?array $data): void
    {
        $legislation = new Legislation();
        if (!$legislation->permission(explode(';', $this->user->roles))) {
            $this->message->error("Você não tem permissão para acessar esse módulo")->flash();
            $this->router->redirect("app.search");
        }

        if (isset($data['action']) && $data['action'] == "sign") {
            if ($this->user->sign != 1) {
                echo json_encode(['message_warning' => "Você não tem permissão para assinar documentos"]);
                return;
            }
            if (!isset($data['documents']) || in_array('', $data['documents'])) {
                echo json_encode(['message_warning' => "Selecione algum documento para assinar"]);
                return;
            }
            if (empty($this->company->certificate_pem)) {
                echo json_encode(['message_warning' => "Você não possui certificado digital"]);
                return;
            }

            foreach ($data['documents'] as $document) {
                $file = (new Legislation())->find("id = :id AND id_company = :id_company", "id={$document}&id_company={$this->company->id}")->fetch();
                if (!$file) {
                    echo json_encode(['message_warning' => "Esse documento não pertence a sua empresa"]);
                    return;
                }
                $sign = (new Sign())->index(
                    storage($file->document_name, $this->company->id . "/" . CONF_UPLOAD_LEGISLATION),
                    $this->company,
                    CONF_UPLOAD_LEGISLATION,
                    $file->document_name,
                    $new_file_name = 'signed_' . $file->document_name
                );
                $file->document_name = $new_file_name;
                $file->signed = 'true';
                $file->save();
            }
            $this->message->success("Documentos assinados com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "send_document_email") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!is_email($data['email'])) {
                $json['message_warning'] = "Preencha o campo com um e-mail válido";
                echo json_encode($json);
                return;
            }
            if (!$legislation = $legislation->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja encaminhar por e-mail";
                echo json_encode($json);
                return;
            }
            //Faz o envio do e-mail
            $email = new \Source\Support\Email();
            $view = new \Source\Core\View($this->router, __DIR__ . "/../../shared/views/email");
            $subject = "[LEGISLAÇÃO] documento número {$legislation->number}";
            $message = "<p>Olá</p>
                        <p>{$this->user->first_name} te encaminhou um documento através do E-Arquivo, para visualizar e baixar, clique no link descrito abaixo!.</p>
                        <p><a target='_blank' href='" . storage($legislation->document_name, $this->company->id . "/" . CONF_UPLOAD_LEGISLATION) . "'>ABRIR DOCUMENTO</a></p>            
                        ";
            $body = $view->render("mail", [
                "subject" => $subject,
                "message" => $message
            ]);
            $email->bootstrap(
                $subject,
                $body,
                $data['email'],
                ''
            )->send();
            $this->message->success("Documento encaminhado com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "delete") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!$legislation = $legislation->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja excluir";
                echo json_encode($json);
                return;
            }

            $this->company->legislation_total_documents -= 1;
            $this->company->legislation_total_pages -= $legislation->total_page;
            $this->company->save();

            if ($legislation->destroy()) {
                //Apaga o documento
                (new Upload())->removeStorage($this->company->id . "/" . CONF_UPLOAD_LEGISLATION . $legislation->document_name);

                $this->message->success("Documento apagado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao apagar documento";
            echo json_encode($json);
            return;
        }

        $page = isset($_GET['page']) ? filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT) : 1;
        $where = "";
        $params = "";

        if (isset($_GET['filter']) && $_GET['filter'] == 's') {
            $number = filter_input(INPUT_GET, 'number', FILTER_SANITIZE_STRIPPED);
            $date_start = filter_input(INPUT_GET, 'date_start', FILTER_SANITIZE_STRIPPED);
            $date_end = filter_input(INPUT_GET, 'date_end', FILTER_SANITIZE_STRIPPED);
            $type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);
            $ementa = filter_input(INPUT_GET, 'ementa', FILTER_SANITIZE_STRIPPED);

            if (!empty(trim($number))) {
                $where .= " AND number LIKE :number";
                $params .= "&number=" . urlencode("%{$number}%");
            }
            if (!empty(trim($date_start)) && is_date($date_start) && !empty(trim($date_end)) && is_date($date_end)) {
                $date_start = date_fmt($date_start, 'Y-m-d');
                $date_end = date_fmt($date_end, 'Y-m-d');
                $where .= " AND date BETWEEN :date_start AND :date_end";
                $params .= "&date_start={$date_start}&date_end={$date_end}";
            }
            if ((!empty($date_start) && is_date($date_start) && empty($date_end)) || ((!empty($date_end) && is_date($date_end) && empty($date_start)))) {
                $date = !empty($date_start) ? date_fmt($date_start, 'Y-m-d') : date_fmt($date_end, 'Y-m-d');
                $where .= " AND date = :date";
                $params .= "&date={$date}";
            }
            if (!empty(trim($type))) {
                $where .= " AND type = :t";
                $params .= "&t={$type}";
            }
            if (!empty(trim($ementa))) {
                $where .= " AND ementa LIKE :e";
                $params .= "&e=%{$ementa}%";
            }

            $legislations = $legislation->find("id_company = :id_company{$where}", "id_company={$this->company->id}{$params}");

            $pager = new Pager($this->router->route('app.legislation', [
                'filter' => 's',
                'number' => $number,
                'date_start' => $date_start,
                'date_end' => $date_end,
                'type' => $type,
                'ementa' => $ementa,
                'page' => ""
            ]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($legislations->count(), 30, $page);
        } else {
            $legislations = $legislation->find('id_company = :id_company', "id_company={$this->company->id}");

            $pager = new Pager($this->router->route('app.legislation',
                ['page' => ""]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($legislations->count(), 30, $page);

        }

        $legislations = $legislation->offset($pager->offset())->limit($pager->limit())->order('id DESC')->fetch(true);

        $head = $this->seo->render(
            "Legislação | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.legislation"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("legislation", [
            "head" => $head,
            "legislations" => $legislations,
            "render" => $pager->render(),
            "release_subscription" => !empty($this->company->certificate_pem) && $this->user->sign == 1 ? true : false,
            "delete_document" => (integer)$this->user->delete_document === 1
        ]);
    }

    /**
     * @param array|null $data
     */
    public function report(?array $data): void
    {
        $report = new Report();
        if (!$report->permission(explode(';', $this->user->roles))) {
            $this->message->error("Você não tem permissão para acessar esse módulo")->flash();
            $this->router->redirect("app.search");
        }

        if (isset($data['action']) && $data['action'] == "sign") {
            if ($this->user->sign != 1) {
                echo json_encode(['message_warning' => "Você não tem permissão para assinar documentos"]);
                return;
            }
            if (!isset($data['documents']) || in_array('', $data['documents'])) {
                echo json_encode(['message_warning' => "Selecione algum documento para assinar"]);
                return;
            }
            if (empty($this->company->certificate_pem)) {
                echo json_encode(['message_warning' => "Você não possui certificado digital"]);
                return;
            }

            foreach ($data['documents'] as $document) {
                $file = (new Report())->find("id = :id AND id_company = :id_company", "id={$document}&id_company={$this->company->id}")->fetch();
                if (!$file) {
                    echo json_encode(['message_warning' => "Esse documento não pertence a sua empresa"]);
                    return;
                }

                $sign = (new Sign())->index(
                    storage($file->document_name, $this->company->id . "/" . CONF_UPLOAD_REPORT),
                    $this->company,
                    CONF_UPLOAD_REPORT,
                    $file->document_name,
                    $new_file_name = 'signed_' . $file->document_name
                );
                var_dump($sign);
                exit();
                $file->document_name = $new_file_name;
                $file->signed = 'true';
                $file->save();
            }
            $this->message->success("Documentos assinados com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "send_document_email") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!is_email($data['email'])) {
                $json['message_warning'] = "Preencha o campo com um e-mail válido";
                echo json_encode($json);
                return;
            }
            if (!$report = $report->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja encaminhar por e-mail";
                echo json_encode($json);
                return;
            }
            //Faz o envio do e-mail
            $email = new \Source\Support\Email();
            $view = new \Source\Core\View($this->router, __DIR__ . "/../../shared/views/email");
            $subject = "[RELATÓRIO] documento número {$report->id}";
            $message = "<p>Olá</p>
                        <p>{$this->user->first_name} te encaminhou um documento através do E-Arquivo, para visualizar e baixar, clique no link descrito abaixo!.</p>
                        <p><a target='_blank' href='" . storage($report->document_name, $this->company->id . "/" . CONF_UPLOAD_REPORT) . "'>ABRIR DOCUMENTO</a></p>            
                        ";
            $body = $view->render("mail", [
                "subject" => $subject,
                "message" => $message
            ]);
            $email->bootstrap(
                $subject,
                $body,
                $data['email'],
                ''
            )->send();
            $this->message->success("Documento encaminhado com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "delete") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!$report = $report->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja excluir";
                echo json_encode($json);
                return;
            }

            $this->company->report_total_documents -= 1;
            $this->company->report_total_pages -= $report->total_page;
            $this->company->save();

            if ($report->destroy()) {
                //Apaga o documento
                (new Upload())->removeStorage($this->company->id . "/" . CONF_UPLOAD_REPORT . $report->document_name);

                $this->message->success("Documento apagado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao apagar documento";
            echo json_encode($json);
            return;
        }

        $page = isset($_GET['page']) ? filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT) : 1;
        $where = "";
        $params = "";

        if (isset($_GET['filter']) && $_GET['filter'] == 's') {
            $name = filter_input(INPUT_GET, 'name', FILTER_SANITIZE_STRIPPED);
            $year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);
            $type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);

            if (!empty(trim($name))) {
                $where .= " AND name LIKE :name";
                $params .= "&name=" . urlencode("%{$name}%");
            }
            if (!empty(trim($year))) {
                $where .= " AND year = :y";
                $params .= "&y={$year}";
            }
            if (!empty(trim($type))) {
                $where .= " AND type = :t";
                $params .= "&t={$type}";
            }


            $reports = $report->find("id_company = :id_company{$where}", "id_company={$this->company->id}{$params}");

            $pager = new Pager($this->router->route('app.report', [
                'filter' => 's',
                'name' => $name,
                'year' => $year,
                'type' => $type,
                'page' => ""
            ]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($reports->count(), 30, $page, 2);
        } else {
            $reports = $report->find('id_company = :id_company', "id_company={$this->company->id}");

            $pager = new Pager($this->router->route('app.report',
                ['page' => ""]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($reports->count(), 30, $page, 2);

        }

        $reports = $report->offset($pager->offset())->limit($pager->limit())->order('year DESC, id DESC')->fetch(true);

        $head = $this->seo->render(
            "Relatórios | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.report"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("report", [
            "head" => $head,
            "reports" => $reports,
            "render" => $pager->render(),
            "release_subscription" => !empty($this->company->certificate_pem) && $this->user->sign == 1 ? true : false,
            "delete_document" => (integer)$this->user->delete_document === 1
        ]);
    }

    /**
     * @param array|null $data
     */
    public function convention(?array $data): void
    {
        $convention = new Convention();
        if (!$convention->permission(explode(';', $this->user->roles))) {
            $this->message->error("Você não tem permissão para acessar esse módulo")->flash();
            $this->router->redirect("app.search");
        }

        if (isset($data['action']) && $data['action'] == "sign") {
            if ($this->user->sign != 1) {
                echo json_encode(['message_warning' => "Você não tem permissão para assinar documentos"]);
                return;
            }
            if (!isset($data['documents']) || in_array('', $data['documents'])) {
                echo json_encode(['message_warning' => "Selecione algum documento para assinar"]);
                return;
            }
            if (empty($this->company->certificate_pem)) {
                echo json_encode(['message_warning' => "Você não possui certificado digital"]);
                return;
            }

            foreach ($data['documents'] as $document) {
                $file = (new Convention())->find("id = :id AND id_company = :id_company", "id={$document}&id_company={$this->company->id}")->fetch();
                if (!$file) {
                    echo json_encode(['message_warning' => "Esse documento não pertence a sua empresa"]);
                    return;
                }
                $sign = (new Sign())->index(
                    storage($file->document_name, $this->company->id . "/" . CONF_UPLOAD_CONVENTION),
                    $this->company,
                    CONF_UPLOAD_CONVENTION,
                    $file->document_name,
                    $new_file_name = 'signed_' . $file->document_name
                );
                $file->document_name = $new_file_name;
                $file->signed = 'true';
                $file->save();
            }
            $this->message->success("Documentos assinados com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "send_document_email") {
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!is_email($data['email'])) {
                $json['message_warning'] = "Preencha o campo com um e-mail válido";
                echo json_encode($json);
                return;
            }
            if (!$convention = $convention->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja encaminhar por e-mail";
                echo json_encode($json);
                return;
            }
            //Faz o envio do e-mail
            $email = new \Source\Support\Email();
            $view = new \Source\Core\View($this->router, __DIR__ . "/../../shared/views/email");
            $subject = "[CONVÊNIO] documento número {$convention->number}";
            $message = "<p>Olá</p>
                        <p>{$this->user->first_name} te encaminhou um documento através do E-Arquivo, para visualizar e baixar, clique no link descrito abaixo!.</p>
                        <p><a target='_blank' href='" . storage($convention->document_name, $this->company->id . "/" . CONF_UPLOAD_CONVENTION) . "'>ABRIR DOCUMENTO</a></p>            
                        ";
            $body = $view->render("mail", [
                "subject" => $subject,
                "message" => $message
            ]);
            $email->bootstrap(
                $subject,
                $body,
                $data['email'],
                ''
            )->send();
            $this->message->success("Documento encaminhado com sucesso!")->flash();
            echo json_encode(['refresh' => true]);
            return;
        }

        if (isset($data['action']) && $data['action'] == "delete") {

            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
            if (!$convention = $convention->findById($data['document'])) {
                $json['message_error'] = "Não encontramos o documento que deseja excluir";
                echo json_encode($json);
                return;
            }

            $this->company->convention_total_documents -= 1;
            $this->company->convention_total_pages -= $convention->total_page;
            $this->company->save();

            if ($convention->destroy()) {
                //Apaga o documento
                (new Upload())->removeStorage($this->company->id . "/" . CONF_UPLOAD_CONVENTION . $convention->document_name);

                $this->message->success("Documento apagado com sucesso!")->flash();
                echo json_encode(['refresh' => true]);
                return;
            }
            $json['message_error'] = "Erro ao apagar documento";
            echo json_encode($json);
            return;
        }

        $page = isset($_GET['page']) ? filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT) : 1;
        $where = "";
        $params = "";

        if (isset($_GET['filter']) && $_GET['filter'] == 's') {
            $number = filter_input(INPUT_GET, 'number', FILTER_SANITIZE_STRIPPED);
            $type = filter_input(INPUT_GET, 'type', FILTER_VALIDATE_INT);
            $object = filter_input(INPUT_GET, 'object', FILTER_SANITIZE_STRIPPED);
            $grantor = filter_input(INPUT_GET, 'grantor', FILTER_SANITIZE_STRIPPED);

            if (!empty(trim($number))) {
                $where .= " AND number LIKE :number";
                $params .= "&number=" . urlencode("%{$number}%");
            }
            if (!empty(trim($type))) {
                $where .= " AND type = :t";
                $params .= "&t={$type}";
            }
            if (!empty(trim($object))) {
                $where .= " AND object LIKE :object";
                $params .= "&object=" . urlencode("%{$object}%");
            }
            if (!empty(trim($grantor))) {
                $where .= " AND grantor LIKE :grantor";
                $params .= "&grantor=" . urlencode("%{$grantor}%");
            }

            $conventions = $convention->find("id_company = :id_company{$where}", "id_company={$this->company->id}{$params}");

            $pager = new Pager($this->router->route('app.convention', [
                'filter' => 's',
                'number' => $number,
                'type' => $type,
                'object' => $object,
                'grantor' => $grantor,
                'page' => ""
            ]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($conventions->count(), 30, $page, 2);
        } else {
            $conventions = $convention->find('id_company = :id_company', "id_company={$this->company->id}");

            $pager = new Pager($this->router->route('app.convention',
                ['page' => ""]), "Página", ["Primeira Página", "«"], ["Última Página", "»"]);
            $pager->pager($conventions->count(), 30, $page, 2);

        }

        $conventions = $conventions->offset($pager->offset())->limit($pager->limit())->order('id DESC')->fetch(true);

        $head = $this->seo->render(
            "Convênio | " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            $this->router->route("app.convention"),
            image(CONF_SITE_SHARE, 1200, 630, CONF_UPLOAD_IMAGE_DIR_SITE),
            false
        );

        echo $this->view->render("convention", [
            "head" => $head,
            "conventions" => $conventions,
            "render" => $pager->render(),
            "release_subscription" => !empty($this->company->certificate_pem) && $this->user->sign == 1 ? true : false,
            "delete_document" => (integer)$this->user->delete_document === 1
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