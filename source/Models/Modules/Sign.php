<?php


namespace Source\Models\Modules;


use League\Flysystem\FileNotFoundException;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use setasign\Fpdi\Tcpdf\Fpdi;
use Source\Models\Company;
use Source\Models\Upload;

class Sign
{
    public function index($path_original, Company $company, $folder, $document_name, $new_file_name)
    {

        $cert = realpath(__DIR__ . '/../../../storage/certificados/' . $company->certificate_crt);

        if (!file_exists($cert)) {
            echo json_encode(['message_warning' => "Não encontramos o seu certificado digital .crt"]);
            exit();
        }

        //Faz download do pdf no storage e salva em pasta temporária
        $temporary = __DIR__ . '/../../../storage/tmp/file_tmp.pdf';
        file_put_contents($temporary, file_get_contents($path_original));

        $fpdi = new Fpdi(); //Abre biblioteca de assinatura
        $fpdi->setSignature('file://' . $cert, 'file://' . $cert, $company->certificate_password, '', 2, []);
        try {
            $count_pages = $fpdi->setSourceFile($temporary);//Quantidade de páginas para o loop
        } catch (PdfParserException $e) {
            return false;
        }

        for ($i = 0; $i < $count_pages; $i++) {
            $fpdi->AddPage();
            try {
                $tplId = $fpdi->importPage($i + 1);
            } catch (CrossReferenceException $e) {
                echo json_encode(['message_warning' => 'Erro ao importar as páginas do documento para assinar']);
                exit();
            } catch (FilterException $e) {
                echo json_encode(['message_warning' => 'Erro ao importar as páginas do documento para assinar']);
                exit();
            } catch (PdfTypeException $e) {
                echo json_encode(['message_warning' => 'Erro ao importar as páginas do documento para assinar']);
                exit();
            } catch (PdfParserException $e) {
                echo json_encode(['message_warning' => 'Erro ao importar as páginas do documento para assinar']);
                exit();
            } catch (PdfReaderException $e) {
                echo json_encode(['message_warning' => 'Erro ao importar as páginas do documento para assinar']);
                exit();
            }
            $fpdi->useTemplate($tplId, 0, 0); //Importa nas medidas originais
        }

        $fpdi->Output($temporary, 'F'); //Salva documento assinado em pasta temporária sobreescrevendo arquivo que foi feito donwnload
        $upload = new Upload();
        try {
            $upload->removeStorage($company->id . "/" . $folder . $document_name);//Remove documento antigo do storage
        } catch (FileNotFoundException $e) {
            echo json_encode(['message_warning' => "Erro ao apagar documento no storage"]);
            exit();
        }
        $upload->sendFile($temporary, $company->id . "/" . $folder . $new_file_name, ['application/pdf']); //Salvar novo documento
        unlink($temporary);
        return true;
    }
}