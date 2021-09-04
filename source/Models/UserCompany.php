<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

/**
 * Class UserCompany
 * @package Source\Models
 */
class UserCompany extends DataLayer
{
    /**
     * UserCompany constructor.
     */
    public function __construct()
    {
        parent::__construct('user_company', ['id_user', 'id_company']);
    }

    public function company()
    {
        return (new Company())->findById($this->id_company);
    }
}