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

        //Verifica primeiro se os arquivos existem. Não foi possível colocar dentro do mesmo foreach
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    if (!file_exists($path . $field['value'])) {
                        $json['message'] = $this->message->warning("O arquivo {$field['value']} não foi encontrado")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }

        }

        //Faz a validação dos campos
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                //Validação dos campos
                if ($field['name'] == "Numero") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O número da despesa do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Data de Pagto") {
                    if (!is_date($field['value'])) {
                        $json['message'] = $this->message->warning("A data do arquivo {$file} está com o formato inválido")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Favorecido") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O nome do favorecido está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Fonte") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("A fonte do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Valor") {
                    if (empty(trim($field['value'])) || !is_decimal($field['value'])) {
                        $json['message'] = $this->message->warning("O valor R$ do arquivo não é válido")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Tipo") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O tipo do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Historico") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O histórico do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Document Filename") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O nome do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Page count in document") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O total de páginas do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }
        }

        //Envia para o Storage
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    $document = $path . $field['value'];
                    $send_storage = (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_EXPENSE . $field['value'], ['application/pdf']);
                }
            }
        }

        //Se enviou com sucesso
        //Salva no Banco de dados
        foreach ($xml->document as $document) {
            $expanse = new Expense();
            foreach ($document as $field) {
                if ($field['name'] == "Numero") {
                    $expanse->number_expense = trim($field['value']);
                }
                if ($field['name'] == "Data de Pagto") {
                    $expanse->date = date_fmt($field['value'], 'Y-m-d');
                }
                if ($field['name'] == "Favorecido") {
                    $expanse->favored = trim($field['value']);
                }
                if ($field['name'] == "Fonte") {
                    $expanse->source = trim($field['value']);
                }
                if ($field['name'] == "Valor") {
                    $expanse->value = trim($field['value']);
                }
                if ($field['name'] == "Tipo") {
                    $expanse->type = trim($field['value']);
                }
                if ($field['name'] == "Historico") {
                    $expanse->historical = trim($field['value']);
                }
                if ($field['name'] == "Document Filename") {
                    $expanse->document_name = trim($field['value']);
                }
                if ($field['name'] == "Page count in document") {
                    $expanse->total_page = trim($field['value']);
                }
            }
            $expanse->save();
            $company = (new Company())->findById($this->company);
            $company->expense_total_pages += $expanse->total_page;
            $company->expense_total_documents += 1;
            $company->save();
        }

        //Apaga os arquivos do diretório raiz
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    $document = $path . $field['value'];
                    unlink($document);
                }
            }
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

        //Verifica primeiro se os arquivos existem. Não foi possível colocar dentro do mesmo foreach
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    if (!file_exists($path . $field['value'])) {
                        $json['message'] = $this->message->warning("O arquivo {$field['value']} não foi encontrado")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }

        }

        //Faz a validação dos campos
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                //Validação dos campos
                if ($field['name'] == "Tipo") {
                    if (empty(trim($field['value'])) || !filter_var($field['value'], FILTER_VALIDATE_INT)) {
                        $json['message'] = $this->message->warning("O campo tipo está em branco ou não é um número")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Numero") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O campo número está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Ementa") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O campo ementa está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Data") {
                    if (empty(trim($field['value'])) || !is_date($field['value'])) {
                        $json['message'] = $this->message->warning("A data do arquivo está com o formato inválido")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Document Filename") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O nome do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Page count in document") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O total de páginas do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }
        }

        //Envia para o Storage
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    $document = $path . $field['value'];
                    $send_storage = (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_LEGISLATION . $field['value'], ['application/pdf']);
                }
            }
        }

        //Se enviou com sucesso
        //Salva no Banco de dados
        foreach ($xml->document as $document) {
            $legislation = new Legislation();
            foreach ($document as $field) {
                if ($field['name'] == "Tipo") {
                    $legislation->type = trim($field['value']);
                }
                if ($field['name'] == "Numero") {
                    $legislation->number = trim($field['value']);
                }
                if ($field['name'] == "Ementa") {
                    $legislation->ementa = trim($field['value']);
                }
                if ($field['name'] == "Data") {
                    $legislation->date = date_fmt($field['value'], 'Y-m-d');
                }
                if ($field['name'] == "Document Filename") {
                    $legislation->document_name = trim($field['value']);
                }
                if ($field['name'] == "Page count in document") {
                    $legislation->total_page = trim($field['value']);
                }
            }
            $legislation->save();
            $company = (new Company())->findById($this->company);
            $company->legislation_total_pages += $legislation->total_page;
            $company->legislation_total_documents += 1;
            $company->save();
        }

        //Apaga os arquivos do diretório raiz
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    $document = $path . $field['value'];
                    unlink($document);
                }
            }
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

        //Verifica primeiro se os arquivos existem. Não foi possível colocar dentro do mesmo foreach
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    if (!file_exists($path . $field['value'])) {
                        $json['message'] = $this->message->warning("O arquivo {$field['value']} não foi encontrado")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }

        }

        //Faz a validação dos campos
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                //Validação dos campos
                if ($field['name'] == "nome") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O campo nome está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Ano") {
                    if (empty(trim($field['value'])) || !is_date($field['value'], 'Y')) {
                        $json['message'] = $this->message->warning("A data do arquivo está com o formato inválido")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Tipo") {
                    if (empty(trim($field['value'])) || !filter_var($field['value'], FILTER_VALIDATE_INT)) {
                        $json['message'] = $this->message->warning("O campo tipo está em branco ou não é um número")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Document Filename") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O nome do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
                if ($field['name'] == "Page count in document") {
                    if (empty(trim($field['value']))) {
                        $json['message'] = $this->message->warning("O total de páginas do arquivo está em branco")->render();
                        echo json_encode($json);
                        return;
                    }
                }
            }
        }

        //Envia para o Storage
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    $document = $path . $field['value'];
                    $send_storage = (new Upload())->sendFile($document, "{$this->company}/" . CONF_UPLOAD_REPORT . $field['value'], ['application/pdf']);
                }
            }
        }

        //Se enviou com sucesso
        //Salva no Banco de dados
        foreach ($xml->document as $document) {
            $report = new Report();
            foreach ($document as $field) {
                if ($field['name'] == "nome") {
                    $report->name = trim($field['value']);
                }
                if ($field['name'] == "Ano") {
                    $report->year = trim($field['value']);
                }
                if ($field['name'] == "Tipo") {
                    $report->type = trim($field['value']);
                }
                if ($field['name'] == "Document Filename") {
                    $report->document_name = $field['value'];
                }
                if ($field['name'] == "Page count in document") {
                    $report->total_page = trim($field['value']);
                }
            }
            $report->save();
            $company = (new Company())->findById($this->company);
            $company->report_total_pages += $report->total_page;
            $company->report_total_documents += 1;
            $company->save();
        }

        //Apaga os arquivos do diretório raiz
        foreach ($xml->document as $document) {
            foreach ($document as $field) {
                if ($field['name'] == "Document Filename") {
                    $document = $path . $field['value'];
                    unlink($document);
                }
            }
        }
        unlink($file);
        $this->message->success("Relatórios anexados com sucesso!")->flash();
        echo json_encode(['refresh' => true]);
        return;
    }
}