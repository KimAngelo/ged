<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;
use Source\Core\Session;

/**
 * Class Company
 * @package Source\Models
 */
class Company extends DataLayer
{
    /**
     * Company constructor.
     */
    public function __construct()
    {
        parent::__construct('companies', ['name', 'type']);
    }

    /**
     * @return DataLayer|null
     */
    public static function company()
    {
        $session = new Session();
        if (!$session->has('company')) {
            return null;
        }
        return (new Company())->findById($session->company);
    }


    /**
     * @param int $company
     * @return array
     */
    public function listXML(int $company)
    {
        $path = __DIR__ . "/../../storage/company/{$company}/";
        if (!is_dir($path)) {
            mkdir($path);
        }
        $dir = dir($path);
        $file = [];
        while ($files = $dir->read()) {
            $info = pathinfo($files);
            if ($info['extension'] === "XML") {
                $file[] = [
                    "name" => $info['basename']
                ];

            }
        }
        $dir->close();
        return $file;
    }

    public function index(int $company, $file)
    {
        $path = __DIR__ . "/../../storage/company/{$company}/";
        $file = __DIR__ . "/../../storage/company/{$company}/{$file}";
        if (file_exists($file)) {
            $xml = simplexml_load_file($file);
            foreach ($xml->document as $item) {
                foreach ($item->field as $field) {
                    echo $field['name'], ':', $field['value']."</br>";
                }
            }
            exit();
        }
    }



}