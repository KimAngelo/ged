<?php


namespace Source\Models\Modules;


/**
 * Class Contract
 * @package Source\Models\Modules
 */
class Contract
{
    /**
     * Contrato
     */
    const ID_CONTRACT = 3;

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