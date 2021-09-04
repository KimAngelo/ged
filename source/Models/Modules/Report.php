<?php


namespace Source\Models\Modules;


use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Report
 * @package Source\Models\Modules
 * RELATÓRIO
 */
class Report extends DataLayer
{
    /**
     *
     */
    const ID_REPORT = 5;

    /**
     * Report constructor.
     */
    public function __construct()
    {
        $required = ['name', 'year', 'type', 'document_name', 'total_page'];
        parent::__construct('reports', $required);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function permission(array $roles)
    {
        if (!in_array(self::ID_REPORT, $roles)) {
            return false;
        }
        return true;
    }
}