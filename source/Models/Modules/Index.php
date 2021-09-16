<?php


namespace Source\Models\Modules;


use Source\Models\Company;
use Source\Models\Upload;
use Source\Support\Message;

/**
 * Class Index
 * @package Source\Models\Modules
 */
class Index
{
    /**
     * @var Message
     */
    protected $message;
    /**
     * @var
     */
    private $company;
    /**
     * @var
     */
    private $file;

    /**
     * Index constructor.
     */
    public function __construct()
    {
        $this->message = new Message();
    }

    /**
     * @param int $company
     * @return array
     */
    public function listXML(int $company)
    {
        $path = __DIR__ . "/../../../storage/company/{$company}/";
        if (!is_dir($path)) {
            mkdir($path);
        }
        $dir = dir($path);
        $file = [];
        while ($files = $dir->read()) {
            $info = pathinfo($files);
            $explode_filename = explode('_', $info['filename']);
            if ($info['extension'] === "XML") {
                $file[] = [
                    "name" => $info['basename'],
                    "module" => $explode_filename[0]
                ];

            }
        }
        $dir->close();
        return $file;
    }

    /**
     * @param int $company
     * @param string $file
     * @param string $module
     */
    public function index(int $company, string $file, string $module)
    {
        $this->company = $company;
        $this->file = $file;

        if (file_exists(__DIR__ . "/../../../storage/company/{$company}/{$file}")) {
            switch ($module) {
                case "despesa":
                    $this->expense();
                    break;
                case "legislacao":
                    $this->legislation();
                    break;
                case "relatorio":
                    $this->report();
                    break;
                case "licitacao":
                    $this->bidding_contract();
                    break;
                default:
                    $json['message'] = $this->message->error("Esse modulo não foi implementado. Entre em contato com o suporte")->render();
                    echo json_encode($json);
                    break;
            }
        }
    }

    /**
     * Despesas
     */
    private function expense()
    {
        set_time_limit(0);
        $path = __DIR__ . "/../../../storage/company/{$this->company}/";
        $file = __DIR__ . "/../../../storage/company/{$this->company}/{$this->file}";
        $xml = simplexml_load_file($file);

        //Transforma XML em array
        $array = [];
        $key = 0;
        foreach ($xml->document as $document) {
            $array[$key] = [];
            foreach ($document as $field) {
                $name = trim($field['name']);
                $array[$key][$name] = trim($field['value']);
            }
            $key += 1;
        }

        //Verifica primeiro se os arquivos existem. Não foi possível colocar dentro do mesmo foreach
        foreach ($array as $item) {
            if (!file_exists($path . $item['Document Filename'])) {
                $json['message'] = $this->message->warning("O arquivo {$item['Document Filename']} não foi encontrado")->render();
                echo json_encode($json);
                return;
            }
        }

        //Faz a validação dos campos
        foreach ($array as $item) {
            if (empty($item['Numero'])) {
                $json['message'] = $this->message->warning("O número da despesa do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty($item['Data de Pagto']) || !is_date($item['Data de Pagto'])) {
                $json['message'] = $this->message->warning("A data do arquivo {$item['Document Filename']} está com o formato inválido ou em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty($item['Favorecido'])) {
                $json['message'] = $this->message->warning("O nome do favorecido no arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty($item['Fonte'])) {
                $json['message'] = $this->message->warning("A fonte do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Valor'])) || !is_decimal($item['Valor'])) {
                $json['message'] = $this->message->warning("O valor R$ do arquivo {$item['Document Filename']} não é válido")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Tipo']))) {
                $json['message'] = $this->message->warning("O tipo do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Historico']))) {
                $json['message'] = $this->message->warning("O histórico do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Historico']))) {
                $json['message'] = $this->message->warning("O histórico do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
        }

        //Envia para o Storage
        foreach ($array as $item) {
            $document = $path . $item['Document Filename'];
            (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_EXPENSE . $item['Document Filename'], ['application/pdf']);
        }

        //Salva no Banco de dados
        foreach ($array as $item) {
            $expanse = new Expense();
            $expanse->number_expense = $item['Numero'];
            $expanse->date = date_fmt($item['Data de Pagto'], 'Y-m-d');
            $expanse->favored = $item['Favorecido'];
            $expanse->source = $item['Fonte'];
            $expanse->value = $item['Valor'];
            $expanse->type = $item['Tipo'];
            $expanse->historical = $item['Historico'];
            $expanse->document_name = $item['Document Filename'];
            $expanse->total_page = $item['Page count in document'];
            $expanse->id_company = $this->company;
            $expanse->save();

            $company = (new Company())->findById($this->company);
            $company->expense_total_pages += $expanse->total_page;
            $company->expense_total_documents += 1;
            $company->save();

        }

        //Apaga os arquivos do diretório raiz
        foreach ($array as $item) {
            $document = $path . $item['Document Filename'];
            unlink($document);
        }

        unlink($file);
        $this->message->success("Despesas anexadas com sucesso!")->flash();
        echo json_encode(['refresh' => true]);
        return;
    }

    /**
     * Legislação
     */
    private function legislation()
    {
        set_time_limit(0);
        $path = __DIR__ . "/../../../storage/company/{$this->company}/";
        $file = __DIR__ . "/../../../storage/company/{$this->company}/{$this->file}";
        $xml = simplexml_load_file($file);

        //Transforma XML em array
        $array = [];
        $key = 0;
        foreach ($xml->document as $document) {
            $array[$key] = [];
            foreach ($document as $field) {
                $name = trim($field['name']);
                $array[$key][$name] = trim($field['value']);
            }
            $key += 1;
        }

        //Verifica primeiro se os arquivos existem. Não foi possível colocar dentro do mesmo foreach
        foreach ($array as $item) {
            if (!file_exists($path . $item['Document Filename'])) {
                $json['message'] = $this->message->warning("O arquivo {$item['Document Filename']} não foi encontrado")->render();
                echo json_encode($json);
                return;
            }
        }

        //Faz a validação dos campos
        foreach ($array as $item) {
            if (empty(trim($item['Tipo'])) || !filter_var($item['Tipo'], FILTER_VALIDATE_INT)) {
                $json['message'] = $this->message->warning("O campo tipo está em branco ou não é um número no documento {$item['Document Filename']}")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Numero']))) {
                $json['message'] = $this->message->warning("O campo número está em branco no documento {$item['Document Filename']}")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Ementa']))) {
                $json['message'] = $this->message->warning("O campo ementa está em branco no documento {$item['Document Filename']}")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Data'])) || !is_date($item['Data'])) {
                $json['message'] = $this->message->warning("A data do arquivo {$item['Document Filename']} está com o formato inválido")->render();
                echo json_encode($json);
                return;
            }
        }

        //Envia para o Storage
        foreach ($array as $item) {
            $document = $path . $item['Document Filename'];
            (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_LEGISLATION . $item['Document Filename'], ['application/pdf']);
        }

        //Salva no Banco de dados
        foreach ($array as $item) {
            $legislation = new Legislation();
            $legislation->type = $item['Tipo'];
            $legislation->number = $item['Numero'];
            $legislation->ementa = $item['Ementa'];
            $legislation->date = date_fmt($item['Data'], 'Y-m-d');
            $legislation->document_name = $item['Document Filename'];
            $legislation->total_page = $item['Page count in document'];
            $legislation->id_company = $this->company;
            $legislation->save();

            $company = (new Company())->findById($this->company);
            $company->legislation_total_pages += $legislation->total_page;
            $company->legislation_total_documents += 1;
            $company->save();
        }

        //Apaga os arquivos do diretório raiz
        foreach ($array as $item) {
            $document = $path . $item['Document Filename'];
            unlink($document);
        }
        unlink($file);
        $this->message->success("Legislações anexadas com sucesso!")->flash();
        echo json_encode(['refresh' => true]);
        return;
    }

    /**
     * Relatórios
     */
    private function report()
    {
        set_time_limit(0);
        $path = __DIR__ . "/../../../storage/company/{$this->company}/";
        $file = __DIR__ . "/../../../storage/company/{$this->company}/{$this->file}";
        $xml = simplexml_load_file($file);

        //Transforma XML em array
        $array = [];
        $key = 0;
        foreach ($xml->document as $document) {
            $array[$key] = [];
            foreach ($document as $field) {
                $name = trim($field['name']);
                $array[$key][$name] = trim($field['value']);
            }
            $key += 1;
        }

        //Verifica primeiro se os arquivos existem. Não foi possível colocar dentro do mesmo foreach
        foreach ($array as $item) {
            if (!file_exists($path . $item['Document Filename'])) {
                $json['message'] = $this->message->warning("O arquivo {$item['Document Filename']} não foi encontrado")->render();
                echo json_encode($json);
                return;
            }
        }

        //Faz a validação dos campos
        foreach ($array as $item) {
            if (empty(trim($item['nome']))) {
                $json['message'] = $this->message->warning("O campo nome está em branco no arquivo {$item['Document Filename']}")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Ano'])) || !is_date($item['Ano'], 'Y')) {
                $json['message'] = $this->message->warning("A data do arquivo {$item['Document Filename']} está com o formato inválido")->render();
                echo json_encode($json);
                return;
            }
            if (empty(trim($item['Tipo'])) || !filter_var($item['Tipo'], FILTER_VALIDATE_INT)) {
                $json['message'] = $this->message->warning("O campo tipo está em branco ou não é um número no documento {$item['Document Filename']}")->render();
                echo json_encode($json);
                return;
            }
        }

        //Envia para o Storage
        foreach ($array as $item) {
            $document = $path . $item['Document Filename'];
            $send_storage = (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_REPORT . $item['Document Filename'], ['application/pdf']);
        }

        //Salva no Banco de dados
        foreach ($array as $item) {
            $report = new Report();
            $report->name = $item['nome'];
            $report->year = $item['Ano'];
            $report->type = $item['Tipo'];
            $report->document_name = $item['Document Filename'];
            $report->total_page = $item['Page count in document'];
            $report->id_company = $this->company;
            $report->save();

            $company = (new Company())->findById($this->company);
            $company->report_total_pages += $report->total_page;
            $company->report_total_documents += 1;
            $company->save();
        }

        //Apaga os arquivos do diretório raiz
        foreach ($array as $item){
            $document = $path . $item['Document Filename'];
            unlink($document);
        }

        unlink($file);
        $this->message->success("Relatórios anexados com sucesso!")->flash();
        echo json_encode(['refresh' => true]);
        return;
    }

    /**
     * Legislação e Contratos vem no mesmo XML
     * O que muda são so tipos
     * 1 = LICITAÇÃO, 2 = CONTRATOS, 3 = ADITIVOS, 4 = RESCISÃO E 5 = ATAS DE RP
     */
    private function bidding_contract()
    {
        set_time_limit(0);
        $path = __DIR__ . "/../../../storage/company/{$this->company}/";
        $file = __DIR__ . "/../../../storage/company/{$this->company}/{$this->file}";
        $xml = simplexml_load_file($file);

        //Transforma XML em array
        $array = [];
        $key = 0;
        foreach ($xml->document as $document) {
            $array[$key] = [];
            foreach ($document as $field) {
                $name = trim($field['name']);
                $array[$key][$name] = trim($field['value']);
            }
            $key += 1;
        }

        //Verifica primeiro se os arquivos existem. Não foi possível colocar dentro do mesmo foreach
        foreach ($array as $item) {
            if (!file_exists($path . $item['Document Filename'])) {
                $json['message'] = $this->message->warning("O arquivo {$item['Document Filename']} não foi encontrado")->render();
                echo json_encode($json);
                return;
            }
        }

        //Validação de dados
        foreach ($array as $item) {
            //Validações em comum
            if (empty($item['Modalidade']) || !filter_var($item['Modalidade'], FILTER_VALIDATE_INT)) {
                $json['message'] = $this->message->warning("A modalidade do arquivo {$item['Document Filename']} está em branco ou não é um número")->render();
                echo json_encode($json);
                return;
            }
            if (empty($item['Objeto'])) {
                $json['message'] = $this->message->warning("O objeto do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty($item['Nº Modalidade'])) {
                $json['message'] = $this->message->warning("O número da modalidade do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            if (empty($item['Data Ass']) || !is_date($item['Data Ass'], 'd/m/Y')) {
                $json['message'] = $this->message->warning("A data do arquivo {$item['Document Filename']} está em branco ou com formato inválido")->render();
                echo json_encode($json);
                return;
            }
            if (empty($item['Nº Processo'])) {
                $json['message'] = $this->message->warning("O número do processo do arquivo {$item['Document Filename']} está em branco")->render();
                echo json_encode($json);
                return;
            }
            //Tipo Licitação
            if ($item['Tipo'] == '1') {

            } //Tipo Contrato
            else {
                if (empty($item['Fornecedor'])) {
                    $json['message'] = $this->message->warning("O fornecedor do arquivo {$item['Document Filename']} está em branco")->render();
                    echo json_encode($json);
                    return;
                }
                if (empty($item['Nº Contrato - Ata'])) {
                    $json['message'] = $this->message->warning("O número do contrato do arquivo {$item['Document Filename']} está em branco")->render();
                    echo json_encode($json);
                    return;
                }
                if (empty($item['Valor']) || !is_decimal($item['Valor'])) {
                    $json['message'] = $this->message->warning("O valor R$ do arquivo {$item['Document Filename']} está em branco ou no formato incorreto")->render();
                    echo json_encode($json);
                    return;
                }
            }
        }
        //Envia para o Storage
        foreach ($array as $item) {
            //Tipo Licitação
            if ($item['Tipo'] == '1') {
                $document = $path . $item['Document Filename'];
                (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_BIDDING . $item['Document Filename'], ['application/pdf']);
            } //Tipo Contrato
            else {
                $document = $path . $item['Document Filename'];
                (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_CONTRACT . $item['Document Filename'], ['application/pdf']);
            }
        }

        //Salva no Banco de dados
        foreach ($array as $item) {
            //Tipo Licitação
            if ($item['Tipo'] == '1') {
                $bidding = new Bidding();
                $bidding->type = $item['Tipo'];
                $bidding->number_process = $item['Nº Processo'];
                $bidding->number_modality = $item['Nº Modalidade'];
                $bidding->modality = $item['Modalidade'];
                $bidding->date = date_fmt($item['Data Ass'], 'Y-m-d');
                $bidding->object = $item['Objeto'];
                $bidding->document_name = $item['Document Filename'];
                $bidding->total_page = $item['Page count in document'];
                $bidding->id_company = $this->company;
                $bidding->save();

                $company = (new Company())->findById($this->company);
                $company->bidding_total_pages += $bidding->total_page;
                $company->bidding_total_documents += 1;
                $company->save();

            } //Tipo Contrato
            else {
                $contract = new Contract();
                $contract->type = $item['Tipo'];
                $contract->provider = $item['Fornecedor'];
                $contract->number_process = $item['Nº Processo'];
                $contract->number_modality = $item['Nº Modalidade'];
                $contract->number_contract = $item['Nº Contrato - Ata'];
                $contract->value = $item['Valor'];
                $contract->modality = $item['Modalidade'];
                $contract->date = date_fmt($item['Data Ass'], 'Y-m-d');
                $contract->object = $item['Objeto'];
                $contract->document_name = $item['Document Filename'];
                $contract->total_page = $item['Page count in document'];
                $contract->id_company = $this->company;
                $contract->save();

                $company = (new Company())->findById($this->company);
                $company->contract_total_pages += $contract->total_page;
                $company->contract_total_documents += 1;
                $company->save();
            }
        }

        //Apaga os arquivos do diretório raiz
        foreach ($array as $item) {
            $document = $path . $item['Document Filename'];
            unlink($document);
        }

        unlink($file);
        $this->message->success("Licitações anexadas com sucesso!")->flash();
        echo json_encode(['refresh' => true]);
        return;

    }

    /**
     *
     */
    private function bidding(object $bidding)
    {
        var_dump($bidding);
        exit();
    }
}