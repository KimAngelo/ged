<?php

use Source\Core\Session;
use Source\Models\User;
use Source\Support\GoogleStorage;

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}


/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
        return true;
    }

    return false;
}

/**
 * @param $date
 * @param string $format
 * @return bool
 */
function is_date($date, $format = "Y-m-d")
{
    $d = DateTime::createFromFormat($format, $date);

    if ($d && $d->format($format) != $date) {
        return false;
    }

    return true;
}

/**
 * @param $number
 * @return string
 */
function numer_int($number): string
{
    return preg_replace("/[^0-9]/", "", $number);
}

/**
 * @param string $string
 * @return string|string[]|null
 * retorna somente letras
 */
function letter(string $string)
{
    return preg_replace("/[^A-Za-z]/", "", $string);
}

/**
 * @param $cep
 * @return bool
 */
function cep_validation($cep): bool
{
    if (!empty($cep)) {
        $cep = numer_int($cep);
        // Valida tamanho
        if (strlen($cep) != 8) {
            return false;
        }
        return true;
    }
}

/**
 * @param $cpf
 * @return bool
 */
function cpf_validation($cpf): bool
{
    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

/**
 * @param $cnpj
 * @return bool|string
 */
function cnpj_validation($cnpj)
{
    $cnpj = preg_replace('/[^0-9]/', '', (string)$cnpj);

    // Valida tamanho
    if (strlen($cnpj) != 14)
        return false;

    // Verifica se todos os digitos são iguais
    if (preg_match('/(\d)\1{13}/', $cnpj))
        return false;

    // Valida primeiro dígito verificador
    for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
        return false;

    // Valida segundo dígito verificador
    for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $j;
        $j = ($j == 2) ? 9 : $j - 1;
    }

    $resto = $soma % 11;

    return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}

/**
 * @param $telefone
 * @return bool
 */
function phone_validation($telefone)
{
    $telefone = trim(str_replace('/', '', str_replace(' ', '', str_replace('-', '', str_replace(')', '', str_replace('(', '', $telefone))))));

    //$regexTelefone = "^[0-9]{11}$";

    $regexCel = '/[0-9]{2}[6789][0-9]{4}[0-9]{4}/'; // Regex para validar somente celular

    $regexTelCel = '/[1-9]{2}[0-9]{4,5}[0-9]{4}/'; // Regex para validar celular e telefone
    if (preg_match($regexTelCel, $telefone)) {
        return true;
    }
    return false;
}

/**
 * @param string $value
 * @return bool
 * Verifica se é um decimal válido
 */
function is_decimal(string $value)
{
    if (preg_match("/^[0-9]+\.[0-9]{2}$/", $value)) {
        return true;
    }
    return false;
}

/**
 * @param $data
 * @return bool
 */
function data_validation($data): bool
{
// data é menor que 8
    if (strlen($data) < 8) {
        return false;
    } else {
        // verifica se a data possui
        // a barra (/) de separação
        if (strpos($data, "/") !== FALSE) {
            //
            $partes = explode("/", $data);
            // pega o dia da data
            $dia = $partes[0];
            // pega o mês da data
            $mes = $partes[1];
            // prevenindo Notice: Undefined offset: 2
            // caso informe data com uma única barra (/)
            $ano = isset($partes[2]) ? $partes[2] : 0;

            if (strlen($ano) < 4) {
                return false;
            } else {
                // verifica se a data é válida
                if (checkdate($mes, $dia, $ano)) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }
}

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }

    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}


/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * @param string $date
 * @param string $format
 * @return string
 */
function date_fmt(string $date = "now", string $format = "d/m/Y H\hi"): string
{
    return (new DateTime(str_replace("/", "-", $date)))->format($format);
}

/**
 * @param string $date
 * @param string $format
 * @return string
 * @throws Exception
 * Quando a entrada de data é no formato BR
 */
function date_br(string $date = "now", string $format = "d/m/Y H\hi"): string
{
    $str_replace = str_replace("/", "-", $date);
    echo (new DateTime(str_replace("/", "-", $date)))->format($format);
    exit();
    $new_date = new DateTime($str_replace);
    if ($new_date->format("d-m-Y") || $new_date->format("d/m/Y")) {
        return $new_date->format($format);
    }
    return $new_date->format($format);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_br(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_app(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_APP);
}


/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(" ", "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $text
 * @return string
 */
function text_area(string $text): string
{
    $text = filter_var($text, FILTER_SANITIZE_STRIPPED);
    $arrayReplace = ["&#10;", "&#10;&#10;", "&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;&#10;", "&#10;&#10;&#10;&#10;&#10;&#10;"];
    return "<p>" . str_replace($arrayReplace, "</p><p>", $text) . "</p>";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}


/**
 * @param string|null $price
 * @return string
 */
function str_price(string $price = null): string
{
    if (!empty($price)) {
        return number_format($price, 2, ",", ".");
    }
    return "0,00";
}

/**
 * @param string $price
 * @return string
 */
function str_price_decimal(string $price): string
{
    if (!empty($price)) {
        return str_replace([".", ","], ["", "."], $price);
    }
    return null;
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";

}

/**
 * @param string $router
 * @return string
 */
function menu_active(string $router = null)
{

    /*if (strpos(url(), 'localhost')) {
        //Pega a URI do localhost para ser dinâmico
        $explode_local = explode('localhost', CONF_URL_TEST);
        $uri = url(str_replace($explode_local[1] . '/', '', $_SERVER['REQUEST_URI']));
    } else {
        $uri = url($_SERVER['REQUEST_URI']);
    }
    return $uri == $router ? "active menu-item-active" : "";*/
    $url = url($_GET['route'] ?? "");
    return $url == $router ? "active menu-item-active" : "";

}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param string $path
 * @return string
 */
function url(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE) {
        if ($path) {
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }

    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * @return string
 */
function url_back(): string
{
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}

/**
 * ##################
 * ###   ASSETS   ###
 * ##################
 */

/**
 * @return User|null
 */
function user(): ?\Source\Models\User
{
    return \Source\Models\User::user();
}

/**
 * @return \Source\Models\Company|null
 */
function company(): ?\Source\Models\Company
{
    return \Source\Models\Company::company();
}

/**
 * @param string|null $path
 * @param string $theme
 * @return string
 */
function theme(string $path = null, string $theme = CONF_VIEW_THEME): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE) {
        if ($path) {
            return CONF_URL_TEST . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }

        return CONF_URL_TEST . "/themes/{$theme}";
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE . "/themes/{$theme}";
}

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @return string
 */
function image(string $image, int $width, int $height = null, ?string $path = null): string
{
    if (!empty($path)) {
        $image = $path . "/" . $image;
        return url() . "/" . (new \Source\Support\Thumb())->make($image, $width, $height);
    }
    return url() . "/" . (new \Source\Support\Thumb())->make($image, $width, $height);
}


/**
 * @param string $name
 * @param null $path
 * @return string
 */
function storage(string $name, $path = null): string
{
    $path = "/" . $path . $name;
    return (new GoogleStorage())->url($path);
}

/**
 * @param string $filename
 * @param string $theme
 * @return string
 */
function css_version_control(string $filename, string $theme = CONF_VIEW_THEME): string
{
    $asset = url("/") . sprintf("themes/{$theme}/assets/{$filename}.css?v=%s",
            substr(md5_file("themes/{$theme}/assets/{$filename}.css"), 0, 10));

    return "<link rel=\"stylesheet\" href=\"{$asset}\">";
}


/**
 * @param string $filename
 * @param string $theme
 * @return string
 */
function js_version_control(string $filename, string $theme = CONF_VIEW_THEME): string
{
    $asset = url("/") . sprintf("themes/{$theme}/assets/{$filename}.js?v=%s",
            substr(md5_file("themes/{$theme}/assets/{$filename}.js"), 0, 10));

    return "<script src=\"{$asset}\"></script>";
}


/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */

/**
 * @return string
 */
function csrf_input(): string
{
    $session = new \Source\Core\Session();
    $session->csrf();
    return "<input type='hidden' name='csrf' value='" . ($session->csrf_token ?? "") . "'/>";
}

/**
 * @param $request
 * @return bool
 */
function csrf_verify($request): bool
{
    $session = new \Source\Core\Session();

    if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
        return false;
    }
    return true;
}

/**
 * @return null|string
 */
function flash(): ?string
{
    $session = new \Source\Core\Session();
    if ($flash = $session->flash()) {
        return $flash;
    }
    return null;
}

/**
 * @param string $key
 * @param int $limit
 * @param int $seconds
 * @return bool
 */
function request_limit(string $key, int $limit = 5, int $seconds = 60): bool
{
    $session = new Session();
    if ($session->has($key) && $session->$key->time >= time() && $session->$key->request < $limit) {
        $session->set($key, [
            "time" => time() + $seconds,
            "request" => $session->$key->request + 1
        ]);
        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->request >= $limit) {
        return true;
    }

    $session->set($key, [
        "time" => time() + $seconds,
        "request" => 1
    ]);

    return false;
}

/**
 * @param string $field
 * @param string $value
 * @return bool
 */
function request_reapeat(string $field, string $value): bool
{
    $session = new Session();
    if ($session->has($field) && $session->$field == $value) {
        return true;
    }
    $session->set($field, $value);
    return false;
}


/**
 * @param $needle
 * @param $haystack
 * @return bool
 */
function in_array_r($needle, $haystack)
{
    foreach ($needle as $item) {
        if (!in_array($item, $haystack)) {
            return false;
        }
    }
    return true;
}

/**
 * @param string $type
 * @return string
 */
function type_expense(string $type)
{
    switch ($type) {
        case "1.0":
            return "Orçamentária";
            break;
        case "2.0":
            return "Extra-orçamentaria";
            break;
        default:
            return "";
    }
}

/**
 * @param string $type
 * @return string
 */
function type_legislation(string $type)
{
    switch ($type) {
        case "1":
            return "Leis";
            break;
        case "2":
            return "Decretos";
            break;
        case "3":
            return "Portarias";
            break;
        case "4":
            return "Indicação";
            break;
        case "5":
            return "Moção";
            break;
        default:
            return $type;
    }
}

/**
 * @param string $type
 * @return string
 */
function type_report(string $type)
{
    switch ($type) {
        case "1":
            return "Contábil";
            break;
        case "2":
            return "Diário de Obra";
            break;
        case "3":
            return "Recursos Humanos";
            break;
        case "4":
            return "Licitação";
            break;
        default:
            return $type;
    }
}


function type_bidding(string $type): string
{
    switch ($type) {
        case "1":
            return "Licitação";
            break;
        case "2":
            return "Contratos";
            break;
        case "3":
            return "Aditivos";
            break;
        case "4":
            return "Rescisão";
            break;
        case "5":
            return "Atas de RP";
            break;
        default:
            return "";
    }
}

function modality_bidding(string $modality): string
{
    switch ($modality) {
        case "1":
            return "Pregão Presencial";
            break;
        case "2":
            return "Pregão Eletrônico";
            break;
        case "3":
            return "Carta Convite";
            break;
        case "4":
            return "Tomada de Preço";
            break;
        case "5":
            return "Concorrência Pública";
            break;
        case "6":
            return "Leilão";
            break;
        case "7":
            return "Concurso";
            break;
        case "8":
            return "Adesão à Registro de Preços";
            break;
        case "9":
            return "Inexigibilidade";
            break;
        case "10":
            return "Dispensa";
            break;
        default:
            return "";
    }
}

function type_contract(string $type): string
{
    switch ($type) {
        case "2":
            return "Contrato";
            break;
        case "3":
            return "Aditivo";
            break;
        case "4":
            return "Rescisão";
            break;
        case "5":
            return "Ata de RP";
            break;
        default:
            return "";
    }
}