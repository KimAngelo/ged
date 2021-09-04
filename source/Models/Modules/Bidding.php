<?php


namespace Source\Models\Modules;


/**
 * Class Bidding
 * @package Source\Models\Modules
 */
class Bidding
{
    /**
     * Licitação
     */
    const ID_BIDDING = 2;

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