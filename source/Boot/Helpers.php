<?php

use Source\Core\Session;
use Source\Models\User;

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
 * @param string $url
 * @return bool
 */
function isYoutubeVideo(string $url): bool
{
    $url = preg_match("/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/", $url);
    if ($url) {
        return true;
    }
    return false;
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
 * @param string $date
 * @return string
 * @throws Exception
 */
function date_fmt_card(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_CARD);
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
 * @param int $valor
 * Converte valor em número por extenso
 */
function str_extension($v = 0)
{

    //$v = filter_var($valor, FILTER_SANITIZE_NUMBER_INT);

    $sin = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
    $plu = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

    $z = 0;

    $v = number_format($v, 2, ".", ".");
    $int = explode(".", $v);

    for ($i = 0; $i < count($int); $i++) {
        for ($ii = mb_strlen($int[$i]); $ii < 3; $ii++) {
            $int[$i] = "0" . $int[$i];
        }
    }

    $rt = null;
    $fim = count($int) - ($int[count($int) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($int); $i++) {
        $v = $int[$i];
        $rc = (($v > 100) && ($v < 200)) ? "cento" : $c[$v[0]];
        $rd = ($v[1] < 2) ? "" : $d[$v[1]];
        $ru = ($v > 0) ? (($v[1] == 1) ? $d10[$v[2]] : $u[$v[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($int) - 1 - $i;
        $r .= $r ? " " . ($v > 1 ? $plu[$t] : $sin[$t]) : "";
        if ($v == "000")
            $z++;
        elseif ($z > 0)
            $z--;

        if (($t == 1) && ($z > 0) && ($int[0] > 0))
            $r .= (($z > 1) ? " de " : "") . $plu[$t];

        if ($r)
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($int[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
    }

    $rt = mb_substr($rt, 1);

    return ($rt ? trim($rt) : "zero");
}

/**
 * @param string $id
 * @return mixed|null
 */
function last_digits_ticket(string $id)
{
    if (empty($id)) {
        return null;
    }
    $explode = explode("-", $id);
    return array_pop($explode);
}

/**
 * @param $date1
 * @param $date2
 * @return float|int|string
 * @throws Exception
 * Retorna a quantidade de meses
 */
function sum_month($date1, $date2)
{
    $date = new \DateTime($date1);
    $diferenca = $date->diff(new \DateTime($date2));
    $diferenca_anos = $diferenca->format('%Y') * 12;
    $diferenca_meses = $diferenca->format('%m');
    return $diferenca_anos + $diferenca_meses;
}

/**
 * @param string|null $status
 * @return string|null
 */
function status_badge(string $status = null): ?string
{
    switch ($status) {
        case 0:
            $status = '<span class="m-badge m-badge--danger m-badge--wide">não finalizado</span>';
            break;
        case 1:
            $status = '<span class="m-badge m-badge--warning m-badge--wide">aguardando pagamento</span>';
            break;
        case 2:
            $status = '<span class="m-badge m-badge--success m-badge--wide">ativo</span>';
            break;
        case 3:
            $status = '<span class="m-badge m-badge--info m-badge--wide">imóvel vendido</span>';
            break;
        case 4:
            $status = '<span class="m-badge m-badge--danger m-badge--wide">anúncio expirou</span>';
            break;
        case 5:
            $status = '<span class="m-badge m-badge--danger m-badge--wide">cartão recusado</span>';
            break;
    }
    return $status;
}


/**
 * @param string|null $status
 * @return string|null
 */
function status_extract(string $status = null): ?string
{
    switch ($status) {
        case "awaiting_transfer":
            $status = '<span class="m-badge m-badge--warning m-badge--wide">aguardando transferência</span>';
            break;
        case "credit":
            $status = '<span class="m-badge m-badge--success m-badge--wide">crédito</span>';
            break;
        case "debt":
            $status = '<span class="m-badge m-badge--danger m-badge--wide">débito</span>';
            break;
    }
    return $status;
}


/**
 * @param string|null $status
 * @return string|null
 */
function status_contract(string $status = null): ?string
{
    switch ($status) {
        case "created":
            $status = '<span class="m-badge m-badge--info m-badge--wide">contrato criado</span>';
            break;
        case "pending":
            $status = '<span class="m-badge m-badge--warning m-badge--wide">aguardando assinaturas</span>';
            break;
        case "signed":
            $status = '<span class="m-badge m-badge--success m-badge--wide">assinado</span>';
            break;
        case "new":
            $status = '<span class="m-badge m-badge--warning m-badge--wide">aguardando assinar</span>';
            break;
        case "link-opened":
            $status = '<span class="m-badge m-badge--info m-badge--wide">visualizou o contrato</span>';
            break;
    }
    return $status;
}

/**
 * @param string|null $category
 * @return string|null
 */
function plans_badge(string $category = null): ?string
{
    switch ($category) {
        case 1:
            $category = '<span class="m-badge m-badge--danger m-badge--wide">Venda</span>';
            break;
        case 2:
            $category = '<span class="m-badge m-badge--warning m-badge--wide">Aluguel</span>';
            break;
        case 3:
            $category = '<span class="m-badge m-badge--success m-badge--wide">Venda e Aluguel</span>';
            break;
        default:
            $category = '<span class="m-badge m-badge--info m-badge--wide">Grátis</span>';
            break;
    }
    return $category;
}

/**
 * @param string|null $status
 * @return string|null
 */
function status_badge_rent(string $status = null): ?string
{
    switch ($status) {
        case "paid":
            $status = '<span class="m-badge m-badge--success m-badge--wide">Ativo</span>';
            break;
        case "trialing":
            $status = '<span class="m-badge m-badge--info m-badge--wide">Período de teste</span>';
            break;
        case "pending_payment":
            $status = '<span class="m-badge m-badge--warning m-badge--wide">Aguardando pagamento</span>';
            break;
        case "unpaid":
            $status = '<span class="m-badge m-badge--danger m-badge--wide">Desativado</span>';
            break;
        case "canceled":
            $status = '<span class="m-badge m-badge--danger m-badge--wide">Cancelado</span>';
            break;
        default:
            $status = '<span class="m-badge m-badge--danger m-badge--wide"></span>';
            break;
    }
    return $status;
}

/**
 * @param string $status
 * @return string|null
 */
function status_billet_rent(string $status): ?string
{
    switch ($status) {
        case "paid":
            $status = '<span class="m-badge m-badge--success m-badge--wide">Pago</span>';
            break;
        case "awaiting_payment":
            $status = '<span class="m-badge m-badge--warning m-badge--wide">Aguardando</span>';
            break;
        case "canceled":
            $status = '<span class="m-badge m-badge--danger m-badge--wide">Cancelado</span>';
            break;
        case "paid_manually":
            $status = '<span class="m-badge m-badge--info m-badge--wide">Recebimento manual</span>';
            break;
        default:
            $status = '<span class="m-badge m-badge--danger m-badge--wide"></span>';
            break;
    }
    return $status;
}

/**
 * @param string $state
 * @return string
 */
function abbreviate_state(string $state): string
{
    switch ($state) {
        case 'Acre' :
            return 'AC';
            break;
        case 'Alagoas' :
            return 'AL';
            break;
        case 'Amapá' :
            return 'AP';
            break;
        case 'Amazonas' :
            return 'AM';
            break;
        case 'Bahia' :
            return 'BA';
            break;
        case 'Ceará' :
            return 'CE';
            break;
        case 'Distrito Federal' :
            return 'DF';
            break;
        case 'Espírito Santo' :
            return 'ES';
            break;
        case 'Goiás' :
            return 'GO';
            break;
        case 'Maranhão' :
            return 'MA';
            break;
        case 'Mato Grosso' :
            return 'MT';
            break;
        case 'Mato Grosso do Sul' :
            return 'MS';
            break;
        case 'Minas Gerais' :
            return 'MG';
            break;
        case 'Pará' :
            return 'PA';
            break;
        case 'Paraíba' :
            return 'PB';
            break;
        case 'Paraná' :
            return 'PR';
            break;
        case 'Pernambuco' :
            return 'PE';
            break;
        case 'Piauí' :
            return 'PI';
            break;
        case 'Rio de Janeiro' :
            return 'RJ';
            break;
        case 'Rio Grande do Norte' :
            return 'RN';
            break;
        case 'Rio Grande do Sul' :
            return 'RS';
            break;
        case 'Rondônia' :
            return 'RO';
            break;
        case 'Roraima' :
            return 'RR';
            break;
        case 'Santa Catarina' :
            return 'SC';
            break;
        case 'São Paulo' :
            return 'SP';
            break;
        case 'Sergipe' :
            return 'SE';
            break;
        case 'Tocantins' :
            return 'TO';
            break;
        default :
            return 'Não informado.';
            break;
    }
}

/**
 * @param null $value
 * @return string
 */
function convert_money($value = null): string
{
    if (!empty($value)) {
        $value = numer_int($value);
        //sem centavos
        //$value = number_format($value, 0, ',', '.');
        //com centavos
        $value = number_format($value, 2, ',', '.');
        return $value;
    } else {
        return null;
    }
}

/**
 * @param $time
 * @return string
 * Cálcula tempo corrido e retorna em leitura humana
 */
function running_time($time)
{

    $now = strtotime(date('m/d/Y H:i:s'));
    $time = strtotime($time);
    $diff = $now - $time;

    $seconds = $diff;
    $minutes = round($diff / 60);
    $hours = round($diff / 3600);
    $days = round($diff / 86400);
    $weeks = round($diff / 604800);
    $months = round($diff / 2419200);
    $years = round($diff / 29030400);

    if ($seconds <= 60) return "1 min atrás";
    else if ($minutes <= 60) return $minutes == 1 ? '1 min atrás' : $minutes . ' min atrás';
    else if ($hours <= 24) return $hours == 1 ? '1 hrs atrás' : $hours . ' hrs atrás';
    else if ($days <= 7) return $days == 1 ? '1 dia atras' : $days . ' dias atrás';
    else if ($weeks <= 4) return $weeks == 1 ? '1 semana atrás' : $weeks . ' semanas atrás';
    else if ($months <= 12) return $months == 1 ? '1 mês atrás' : $months . ' meses atrás';
    else return $years == 1 ? 'um ano atrás' : $years . ' anos atrás';
}

/**
 * @param null $sale
 * @param null $rent
 * @return string
 * Retorna compra ou venda
 */
function sale_rent($sale = null, $rent = null)
{
    if (!empty($sale) && empty($rent)) {
        return "Venda";
    } elseif (!empty($rent) && empty($sale)) {
        return "Aluguel";
    } else {
        return "Venda | Aluguel";
    }
}

/**
 * @param $status
 * Retorna o status do post
 */
function post_status($status)
{
    switch ($status) {
        case "post":
            $status = '<span class="m-badge m-badge--success m-badge--wide">Ativo</span>';
            break;
        case "draft":
            $status = '<span class="m-badge m-badge--warning m-badge--wide">Rascunho</span>';
            break;
        case "trash":
            $status = '<span class="m-badge m-badge--danger m-badge--wide">Lixo</span>';
            break;
    }

    return $status;
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
 * @param string $type
 * @return string
 */
function type_genre(string $type): string
{
    $string = substr($type, -1);
    if ($string == "a") {
        return "uma";
    }
    return "um";
}


/**
 * @return string
 */
function get_city_from_ip()
{
    if (strpos(url(), "localhost")) {
        $ip = "170.231.150.62";
        //$ip = "200.25.50.153"; //cidade de Buenos Aires
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://api.ipstack.com/{$ip}?access_key=f4da1661fb89e035ada0e32bceee167c&fields=city&format=1",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => "",
        CURLOPT_COOKIE => "__cfduid=dcef81c9c73881b3a924f623e5bfc61fd1608578485",
    ]);

    $response = json_decode(curl_exec($curl));
    $err = curl_error($curl);
    curl_close($curl);
    setcookie('visitor_city', $response->city, time() + 60 * 60 * 24 * 30, '/');
    return $response->city;
}


/**
 * @return string|null
 */
function city_from_cookie()
{
    if (!isset($_COOKIE['visitor_city'])) {
        $geo = get_city_from_ip();
        if ($geo != "error") {
            return ucwords($geo);
        } else {
            return null;
        }
    }
    return ucwords($_COOKIE['visitor_city']);
}


/**
 * @param int $bedrooms
 * @return string|null
 */
function response_bedrooms(int $bedrooms)
{
    switch ($bedrooms) {
        case 1:
            return "1 Dormitório";
            break;
        case 2:
            return "2 Dormitórios";
            break;
        case 3:
            return "3 Dormitórios";
            break;
        case 4:
            return "4 Dormitórios";
            break;
        case 5:
            return "+5 Dormitórios";
            break;
    }
    return null;
}

/**
 * @param int $garage
 * @return string|null
 */
function response_garage(int $garage)
{
    switch ($garage) {
        case 1:
            return "1 Vaga";
            break;
        case 2:
            return "2 Vagas";
            break;
        case 3:
            return "3 Vagas";
            break;
        case 4:
            return "4 Vagas";
            break;
        case 5:
            return "+5 Vagas";
            break;
    }
    return null;
}

/**
 * @param int $toilet
 * @return string|null
 */
function response_toilet(int $toilet)
{
    switch ($toilet) {
        case 1:
            return "1 Banheiro";
            break;
        case 2:
            return "2 Banheiros";
            break;
        case 3:
            return "3 Banheiros";
            break;
        case 4:
            return "4 Banheiros";
            break;
        case 5:
            return "+5 Banheiros";
            break;
    }
    return null;
}

/**
 * @param int $suite
 * @return string|null
 */
function response_suite(int $suite)
{
    switch ($suite) {
        case 1:
            return "1 Suíte";
            break;
        case 2:
            return "2 Suítes";
            break;
        case 3:
            return "3 Suítes";
            break;
        case 4:
            return "4 Suítes";
            break;
        case 5:
            return "+5 Suítes";
            break;
    }
    return null;
}

/**
 * @param $file_post
 * @return array
 */
function reArrayFiles(&$file_post)
{

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

/**
 * @param string $date
 * @param string $standard_day
 * @return false|string
 * @throws Exception
 */
function next_date(string $date, string $standard_day)
{
    $date_time = new DateTime($date);
    $nxtm = $date_time->modify('first day of next month');
    $nxtm = $nxtm->format('m');
    $days_next_month = cal_days_in_month(CAL_GREGORIAN, $nxtm, date_fmt($date, 'Y'));
    if ($standard_day > 28) {
        $d = $days_next_month == 31 ? 31 : ($days_next_month == 30 ? 30 : ($days_next_month == 29 ? 29 : ($days_next_month == 28 ? 28 : "")));
        $y = date_fmt($date, 'm') == 12 ? date('Y', strtotime('next Year')) : date('Y');
        return "{$y}-{$nxtm}-{$d}";
    }
    return date('Y-m-d', strtotime('+1 month', strtotime($date)));
}