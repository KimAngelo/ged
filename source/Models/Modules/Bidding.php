<?php


namespace Source\Models\Modules;


use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Bidding
 * @package Source\Models\Modules
 */
class Bidding extends DataLayer
{
    /**
     * Licitação
     */
    const ID_BIDDING = 2;

    public function __construct()
    {
        parent::__construct('biddings', ['type', 'number_process', 'date', 'object', 'document_name', 'total_page']);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function permission(array $roles)
    {
        if (!in_array(self::ID_BIDDING, $roles)) {
            return false;
        }
        return true;
    }
}