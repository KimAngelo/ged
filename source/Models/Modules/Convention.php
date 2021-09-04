<?php


namespace Source\Models\Modules;


/**
 * Class Convention
 * @package Source\Models\Modules
 * CONVÊNIO
 */
class Convention
{
    /**
     *
     */
    const ID_CONVENTION = 6;

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