<?php


namespace Source\Core;


/**
 * Class Environment
 * @package Source\Core
 */
class Environment
{
    /** Método responsável por carregar as variáveis de ambiente do projeto
     * @param $dir
     * Caminho absoluto da pasta onde encontra-se o arquivo .env
     * @return bool
     */
    public static function load($dir)
    {
        //Verifica se o arquivo .ENV existe
        if (!file_exists($dir . '/.env')) {
            return false;
        }

        //DEFINE AS VARIÁVEIS DE AMBIENTE
        $lines = file($dir . '/.env');
        foreach ($lines as $line) {
            putenv(trim($line));
        }
        return true;
    }
}