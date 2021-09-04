<?php


namespace Source\Models\Modules;


use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Legislation
 * @package Source\Models\Modules
 * LEGISLAÇÃO
 */
class Legislation extends DataLayer
{

    /**
     *
     */
    const ID_LEGISLATION = 4;

    /**
     * Legislation constructor.
     */
    public function __construct()
    {
        $required = ['type', 'number', 'ementa', 'date', 'document_name', 'total_page'];
        parent::__construct('legislations', $required);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function permission(array $roles)
    {
        if (!in_array(self::ID_LEGISLATION, $roles)) {
            return false;
        }
        return true;
    }
}