<?php


namespace Source\Models\Modules;


use CoffeeCode\DataLayer\DataLayer;

/**
 * Class Expense
 * @package Source\Models\Modules
 * DESPESA
 */
class Expense extends DataLayer
{
    /**
     *
     */
    const ID_EXPENSES = 1;

    /**
     * Expense constructor.
     */
    public function __construct()
    {
        $required = ['number_expense', 'date', 'favored', 'source', 'value', 'historical', 'document_name', 'total_page'];
        parent::__construct('expense', $required);
    }

    /**
     * @param array $roles
     * @return bool
     */
    public function permission(array $roles)
    {
        if (!in_array(self::ID_EXPENSES, $roles)) {
            return false;
        }
        return true;
    }
}