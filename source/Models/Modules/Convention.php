<?php


namespace Source\Models\Modules;


use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Convention
 * @package Source\Models\Modules
 * CONVÊNIO
 */
class Convention extends DataLayer
{
    /**
     *
     */
    const ID_CONVENTION = 6;

    public function __construct()
    {
        parent::__construct('convention', ['type', 'number', 'object', 'document_name', 'total_page', 'id_company']);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function permission(array $roles)
    {
        if (!in_array(self::ID_CONVENTION, $roles)) {
            return false;
        }
        return true;
    }
}