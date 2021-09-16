<?php


namespace Source\Models\Modules;


use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Contract
 * @package Source\Models\Modules
 */
class Contract extends DataLayer
{
    /**
     * Contrato
     */
    const ID_CONTRACT = 3;

    public function __construct()
    {
        parent::__construct('contracts', ['type', 'provider', 'number_process', 'value', 'date', 'object', 'document_name', 'total_page']);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function permission(array $roles)
    {
        if (!in_array(self::ID_CONTRACT, $roles)) {
            return false;
        }
        return true;
    }
}