<?php
/***
 * Exibe o conteúdo de Var_dump com Quebras de Linhas no HTML
 */
if (! function_exists('html_vdump') ) {
    function html_vdump() {
        ob_start();
        $var = func_get_args();
        $htmlentities = false;
        $lastIndex = count($var)-1;
        if ( is_array($var[$lastIndex]) and (count($var) > 1 ) and ( isset($var[$lastIndex]['html_entities']) and $var[$lastIndex]['html_entities']==true ) ) {
            unset($var[$lastIndex]);
            $htmlentities = true;
        }
        call_user_func_array('var_dump', $var);
        if ($htmlentities)
            echo "<pre>".htmlentities(ob_get_clean())."</pre>\n";
        else
            echo "<pre>".html_entity_decode(ob_get_clean())."</pre>\n";
    }
}

/***
 * MaskText($str, $mask)
 * $str = Texto para ser mascarado
 * $mask = Mascara para ser executada no texto
 */
if (! function_exists('MaskText') ) {
    function MaskText($str, $mask) {
      $str = str_replace(' ','',$str);
      try {
          for($i=0; $i < strlen($mask); $i++) {
            $char = '';
            for ($j=0; $j < strlen($str); $j++) {
                switch( $mask[$i] ) {
                    case '9':
                        $char = preg_replace('/\D/', '', $str[0]); break;
                    case 'a': case 'A':
                        $char = preg_replace('/[^a-zA-ZÀ-ü]/', '', $str[0]); break;
                    case '#':
                        $char = preg_replace('/\W|_/', '', $str[0]); break;
                    default:
                        $char = $mask[$i]; break;
                }
                //html_vdump( "\n \$i: $i \n \$mask: $mask \n \$str: $str \n \$mask[$i]: ${mask[$i]} \n \$str[0]: ${str[0]} \n \$char: $char \n " );
                if ($char=='') {
                        $str = substr($str, 1);
                    continue;
                } else {
                    if ($char == $str[0])
                        $str = substr($str, 1);
                    break;
                }
            }
            if ($char=='') {
                $mask = substr($mask, 0, $i);
                break;
            } else
                $mask[$i] = $char;
          }
      } catch (Exception $e) {
        echo $e->getMessage();
      }
      return $mask;
    }

    /*function MaskText($str, $mask, $type='#') {
      if($type=='#')
        $str = preg_replace('/\W|_/','',$str);
      else if($type=='9')
        $str = preg_replace('/\D/','',$str);
      else if(strtoupper($type)=='A')
        $str = preg_replace('/[^a-zA-ZÀ-ü]/','',$str);

      $str = str_replace(' ','',$str);
      $mask = str_replace($type,'#', $mask);

      try {
          for($i=0; $i<strlen($str); $i++) {
            $mask[stripos($mask,'#')] = $str[$i];
          }
      } catch (Exception $e) {
        echo $e->getMessage();
      }

      return $mask;
    }*/

    /*function mascara_string($mascara,$string) {
       $string = str_replace(" ", "", $string);
       for($i=0;$i<strlen($string);$i++) {
          $mascara[strpos($mascara,"#")] = $string[$i];
       }
       return $mascara;
    }*/
}

/*
 * Altera o formato das Datas; Apenas o primeiro parametro é obrigatório;
 * Ao passar os demais parametros, pode ou não, passar o parametro $reverseDate como terceiro parametro, seguido dos delimitadores;
 * Pode se usar um formato de Data como usado na função date() do PHP. Os argumentos aceitos nesse caso são apenas a Data (obrigatório)
 *      e os formatos de saída (opcional) e formato de entrada (opcional)
 *
 * formatDate($date[, $removeHour, $delimiter, $delimiterReplace])
 * formatDate($date[, $removeHour, $reverseDate, $delimiter, $delimiterReplace])
 * formatDate($date[, $maskDateReplace, $maskDate])
 *
 * @date - Data a ser formatada;
 * @removeHour - Booleano para definir se o resultado imprime ou não a hora se tiver;
 * @reverseDate - Caso passado como 3º parametro, inverte a posição da data 'dd/mm/YYYY' para 'YYYY/mm/dd' ou vice versa;
 * @delimiter - Define o Separador da Data do texto atual; Valor padrão é '/'; É o 4º parametro se passado o parametro reverseDate, ou 3º se não passado;
 * @delimiterReplace - Define o Separador da Data do texto formatado; Valor padrão é '-'; É o 5º parametro se passado o parametro reverseDate, ou 4º se não passado;
 * @maskDateReplace - Define o formato de saída da Data
 * @maskDate - Define o formato de entrada da Data
 *
 * @autor Pyetro Costa
 * @firstVersion Felipe Iwata
 *
 * @defaultFormatDate - Assume the default formate to DATE_ISO8601 (Example: 2013-04-12T15:52:01+0000)
 *
 * Resultados de uso:
 *    formatDate('1955-02-20', false);                   // 20/02/1955
 *    formatDate('1955/02/20', false, '/', '-');         // 20-02-1955
 *    formatDate('20-02-1955', false);                   // 1955/02/20
 *    formatDate('20/02/1955', false, '/', '-');         // 1955-02-20
 *
 *    formatDate('1955-02-20', false, true);             // 20/02/1955
 *    formatDate('1955/02/20', false, true, '/', '-');   // 20-02-1955
 *    formatDate('20-02-1955', false, true);             // 1955/02/20
 *    formatDate('20/02/1955', false, true, '/', '-');   // 1955-02-20
 *
 *    formatDate('1955-02-20', false, false);            // 1955/02/20
 *    formatDate('1955/02/20', false, false, '/', '-');  // 1955-02-20
 *    formatDate('20-02-1955', false, false);            // 20/02/1955
 *    formatDate('20/02/1955', false, false, '/', '-');  // 20-02-1955
 *
 *    formatDate('1955-02-20', 'd/m/Y');                 // 20/02/1955
 *    formatDate('1955/02/20', 'd-m-Y', 'Y/m/d');        // 20-02-1955
 *    formatDate('20-02-1955', 'Y/m/d', 'd-m-Y');        // 1955/02/20
 *    formatDate('20/02/1955', 'Y-m-d', 'd/m/Y');        // 1955-02-20
 *
 * Demonstração:
 * var_dump(
 *    '1955-02-20 ==> '.formatDate('1955-02-20', false)                       ." ( false ) ",
 *    '1955/02/20 ==> '.formatDate('1955/02/20', false, '/', '-')             ." ( false, '/', '-' ) ",
 *    '20-02-1955 ==> '.formatDate('20-02-1955', false)                       ." ( false ) ",
 *    '20/02/1955 ==> '.formatDate('20/02/1955', false, '/', '-')             ." ( false, '/', '-' ) ",
 *    '',
 *    '1955-02-20 ==> '.formatDate('1955-02-20', false, true)                 ." ( false, true ) ",
 *    '1955/02/20 ==> '.formatDate('1955/02/20', false, true, '/', '-')       ." ( false, true, '/', '-' ) ",
 *    '20-02-1955 ==> '.formatDate('20-02-1955', false, true)                 ." ( false, true ) ",
 *    '20/02/1955 ==> '.formatDate('20/02/1955', false, true, '/', '-')       ." ( false, true, '/', '-' ) ",
 *    '',
 *    '1955-02-20 ==> '.formatDate('1955-02-20', false, false)                ." ( false, false ) ",
 *    '1955/02/20 ==> '.formatDate('1955/02/20', false, false, '/', '-')      ." ( false, false, '/', '-' ) ",
 *    '20-02-1955 ==> '.formatDate('20-02-1955', false, false)                ." ( false, false ) ",
 *    '20/02/1955 ==> '.formatDate('20/02/1955', false, false, '/', '-')      ." ( false, false, '/', '-' ) ",
 *    '',
 *    '1955-02-20 ==> '.formatDate('1955-02-20', 'd/m/Y')                     ." ( 'd/m/Y' ) ",
 *    '1955/02/20 ==> '.formatDate('1955/02/20', 'd-m-Y', 'Y/m/d')            ." ( 'd-m-Y', 'Y/m/d' ) ",
 *    '20-02-1955 ==> '.formatDate('20-02-1955', 'Y/m/d', 'd-m-Y')            ." ( 'Y/m/d', 'd-m-Y' ) ",
 *    '20/02/1955 ==> '.formatDate('20/02/1955', 'Y-m-d', 'd/m/Y')            ." ( 'Y-m-d', 'd/m/Y' ) "
 * );
 */
if (! function_exists('formatDate') ) {
    function formatDate($value) {
        // Vars
            $numArgs = func_num_args();
            $getArgs = func_get_args();
            $removeHour = false;
            $reverseDate = ($numArgs>=5 and gettype($getArgs[2]!='boolean')) ? false : true;
            $delimiter = '-';
            $delimiterReplace = '/';
            $maskDateReplace = null;
            $maskDate = null;

        if ($value == null or $value == '')
            return null;

        // Seta parametro removeHour se passado como argumento no segundo parametro como Booleano
        if ($numArgs >= 2) {
            $removeHour = (gettype($getArgs[1])=='boolean') ? $getArgs[1] : $removeHour;
            if ($numArgs == 2 and gettype($getArgs[1])=='string')
                $maskDateReplace = $getArgs[1];
        }

        // Seta parametro reverseDate se passado como argumento no terceiro parametro como Booleano
        // Seta parametro delimiter se passado como argumento no terceiro (s/ reserveDate) ou quarto (c/ reserveDate) parametro como String
        // Seta parametro delimiter se passado como argumento no quarto (s/ reserveDate) ou quinto (c/ reserveDate) parametro como String
        if ($numArgs == 3) {
            if (gettype($getArgs[1])=='string') {
                list(, $maskDateReplace, $maskDate) = $getArgs;
            } else {
                $reverseDate        = (gettype($getArgs[2])=='boolean') ? $getArgs[2] : $reverseDate;
                $delimiter          = (gettype($getArgs[2])=='string') ? $getArgs[2] : $delimiter;
            }
        }
        else if ($numArgs==4) {
            if (gettype($getArgs[2])=='boolean')
                list(,, $reverseDate, $delimiter ) = $getArgs;
            else
                list(,, $delimiter, $delimiterReplace) = $getArgs;
        }
        else if ($numArgs==5) {
            list (,, $reverseDate, $delimiter, $delimiterReplace) = $getArgs;
        }

        // Usado apenas quando não for MaskedDate
        $date = $reverseDate ? array_reverse(explode($delimiter, substr($value, 0, 10))) : explode($delimiter, substr($value, 0, 10));

        // Anonymous Function to Mask Date
        $maskedDate = function () use ($value, $maskDateReplace, $maskDate, $delimiter, $delimiterReplace) {

            $maskDate = $maskDate==null? 'Y-m-d H:i:s' : $maskDate;
            $fmt = array('year'=>'1970','month'=>'01','day'=>'01','hour'=>'00','minute'=>'00','second'=>'00',);

            if ( $maskDate !== 'Y-m-d H-i-s' ) {
                $maskDate = str_split(preg_replace('/[^a-zA-Z]/i', '', $maskDate));
                $value = preg_replace('/[^0-9]/i', '', $value);
                for($i=0; $i < count($maskDate); $i++) {
                    switch($maskDate[$i]) {
                        case 'Y':
                            $fmt['year'] = (substr($value, 0, 4)!=''? substr($value, 0, 4) : $fmt['year']);
                            $value = substr($value, 4);
                            break;
                        case 'y':
                            $fmt['year'] = (substr($value, 0, 2)!=''? substr($value, 0, 2) : $fmt['year']);
                            $value = substr($value, 2);
                            break;
                        case 'm': case 'M':
                            $fmt['month'] = (substr($value, 0, 2)!=''? substr($value, 0, 2) : $fmt['month']);
                            $value = substr($value, 2);
                            break;
                        case 'd': case 'D':
                            $fmt['day'] = (substr($value, 0, 2)!=''? substr($value, 0, 2) : $fmt['day']);
                            $value = substr($value, 2);
                            break;
                        case 'g': case 'G':
                            $fmt['hour'] = (substr($value, 0, 1)!=''? substr($value, 0, 1) : $fmt['hour']);
                            $value = substr($value, 1);
                            break;
                        case 'h': case 'H':
                            $fmt['hour'] = (substr($value, 0, 2)!=''? substr($value, 0, 2) : $fmt['hour']);
                            $value = substr($value, 2);
                            break;
                        case 'i': case 'I':
                            $fmt['minute'] = (substr($value, 0, 2)!=''? substr($value, 0, 2) : $fmt['minute']);
                            $value = substr($value, 2);
                            break;
                        case 's': case 'S':
                            $fmt['second'] = (substr($value, 0, 2)!=''? substr($value, 0, 2) : $fmt['second']);
                            $value = substr($value, 2);
                            break;
                    }
                }
            }
            else {
                $datetime = explode(' ', $value);
                $date = explode($delimiter, $datetime[0]);
                $time = explode(':', $datetime[1]);

                list($fmt['year'], $fmt['month'], $fmt['day'] ) = $date;
                if ( count($time) == 3 )
                    list($fmt['hour'], $fmt['minute'], $fmt['second']) = $time;
                else
                    $fmt['hour'] = $fmt['minute'] = $fmt['second'] = '00';
            }

            //dd( $fmt, $value, $maskDateReplace );
            try {
                $mkTime = mktime($fmt['hour'], $fmt['minute'], $fmt['second'], $fmt['month'], $fmt['day'], $fmt['year']);
            } catch (Exception $e) {
                //dd( $e->getMessage(), $fmt, $value, $maskDateReplace, $temp );
                return ($e->getMessage() );
            }
            return date($maskDateReplace, $mkTime);
        };

        // Data Hora Com Mascara
        if ($maskDateReplace!==null)
            return $maskedDate();

        // Apenas data
        if (strlen($value) == 10) {
            return implode( $delimiterReplace, $date);
        }
        // Data e hora
        else if (strlen($value) > 10) {
            if ($removeHour == true) {
                return implode( $delimiterReplace, $date);
            } else {
                return implode( $delimiterReplace, $date).' '.substr($value, 11, 8);
            }
        }
    }
}

// Insere máscara para telefones. O valor do telefone pode ser passado somente com números ou com qualquer outro caractere
if (! function_exists('formatPhone') ) {
    function formatPhone($value) {
        $value = preg_replace('/\D+/', '', $value);

        if ($value !== '' or !is_null($value)) {

            if (strlen($value) == 8) // Telefone fixo sem DDD
                return substr($value, 0, 4).'-'.substr($value, -4);

            else if (strlen($value) == 9) // Celular sem DDD
                return substr($value, 0, 5).'-'.substr($value, -4);

            else if (strlen($value) == 10) // Telefone fixo com DDD
                return '('.substr($value, 0, 2).') '.substr($value, 2, 4).'-'.substr($value, -4);

            else if (strlen($value) == 11) // Celular com DDD
                return '('.substr($value, 0, 2).') '.substr($value, 2, 5).'-'.substr($value, -4);

            else if (strlen($value) == 12) // Telefone fixo com DDD e DDI do Brasil
                return '+'.substr($value, 0, 2).' ('.substr($value, 2, 2).') '.substr($value, 4, 4).'-'.substr($value, -4);

            else if (strlen($value) == 13) // Celular com DDD e DDI do Brasil
                return '+'.substr($value, 0, 2).' ('.substr($value, 2, 2).') '.substr($value, 4, 5).'-'.substr($value, -4);

        } else
            return 'Número de telefone inválido !';
    }
}

if (! function_exists('strReduce') ) {
    function strReduce($str, $length, $ellipsis=true) {
        return (strlen($str)>$length ? substr($str, 0, $length).($ellipsis?'...':'') : $str);
    }
}

/***
 * Alter the timestamp of a DateTime object by incrementing or decrementing in a number of days
 *
 * @param (DateTime $aDate) or (String $aDate)
 * @param int $days positive or negative
 *
 * @return DateTime new instance - original parameter is unchanged
 *
 * Autor: Pyetro Costa
 */
if (! function_exists('addDays') ) {
    function addDays ($aDate, $days) {

        $debug_backtrace = debug_backtrace()[0];
        if (gettype($aDate) == 'string') {
            try {
                if (validateDate($aDate) == false) {
                    throw new \Error( 'Data inválida ! Chamado em '.$debug_backtrace['file'].' na linha '.$debug_backtrace['line']);
                } else {
                    $aDate = new DateTime($aDate);
                }
            } catch (\Exception $e) {
                throw new \Error( $e->getMessage().', called in '.$debug_backtrace['file'].' on line '.$debug_backtrace['line'] );
            }
        } else if (!($aDate instanceof DateTime)) {
            throw new \Error('Type error: Argument 1 passed to addDays() must be an instance of DateTime or String (of date), '.(gettype($debug_backtrace['args'][0])).' given,'
                            .' called in '.$debug_backtrace['file'].' on line '.$debug_backtrace['line']);
        }

        $dateA = clone($aDate);
        return $dateA->modify($days . ' Day')->format('Y-m-d');
    }
}

/***
 * Correctly calculates end of months when we shift to a shorter or longer month
 * workaround for http://php.net/manual/en/datetime.add.php#example-2489
 *
 * Makes the assumption that shifting from the 28th Feb +1 month is 31st March
 * Makes the assumption that shifting from the 28th Feb -1 month is 31st Jan
 * Makes the assumption that shifting from the 29,30,31 Jan +1 month is 28th (or 29th) Feb
 *
 *
 * @param (DateTime $aDate) or (String $aDate)
 * @param int $months positive or negative
 *
 * @return DateTime new instance - original parameter is unchanged
 *
 * Autor: mangotonk@gmail.com
 * Reference: http://php.net/manual/pt_BR/datetime.modify.php#120513
 * Adapted: Pyetro Costa
 */
if (! function_exists('addMonths') ) {
    function addMonths ($aDate, $months) {

        // $debug_backtrace = debug_backtrace()[0];
        // //dd( $debug_backtrace );
        // if (gettype($aDate) == 'string') {
        //     try {
        //         if (validateDate($aDate) == false) {
        //             throw new \Error( 'Data inválida ! Chamado em '.$debug_backtrace['file'].' na linha '.$debug_backtrace['line']);
        //         } else {
        //             $aDate = new DateTime($aDate);
        //         }

        //     } catch (\Exception $e) {
        //         throw new \Error( $e->getMessage().', called in '.$debug_backtrace['file'].' on line '.$debug_backtrace['line'] );
        //     }
        // } else if (!($aDate instanceof DateTime)) {
        //     throw new \Error('Type error: Argument 1 passed to addMonths() must be an instance of DateTime or String (of date), '.(gettype($debug_backtrace['args'][0])).' given,'
        //                     .' called in '.$debug_backtrace['file'].' on line '.$debug_backtrace['line']);
        // }

        // $dateA = clone($aDate);
        // $dateB = clone($aDate);
        // $plusMonths = clone($dateA->modify($months . ' Month'));

        // // Check whether reversing the month addition gives us the original day back
        // if ($dateB != $dateA->modify($months*-1 . ' Month')) {
        //     $result = $plusMonths->modify('last day of last month');
        // } else if ($aDate == $dateB->modify('last day of this month')) {
        //     $result =  $plusMonths->modify('last day of this month');
        // } else {
        //     $result = $plusMonths;
        // }
        // return $result->format('Y-m-d');
    }
}

/***
 * Correctly calculates end of months when we shift to a shorter or longer year
 *
 * This function depends on the function addMonth();
 *
 * Makes the assumption that shifting from the 28th Feb 1999 +1 year is 29th Feb 2000
 * Makes the assumption that shifting from the 28th Feb 1999 -1 year is 28th Feb 1998
 * Makes the assumption that shifting from the 28,29 Feb 2000 +1 year is 28th Feb 2001
 *
 * @param (DateTime $aDate) or (String $aDate)
 * @param int $years positive or negative
 *
 * @return DateTime new instance - original parameter is unchanged
 *
 * Autor: Pyetro Costa
 * Reference: http://php.net/manual/pt_BR/datetime.modify.php#120513
 */
if (! function_exists('addYears') ) {
    function addYears ($aDate, $years) {
        //return addMonths($aDate, 12 * $years);
    }
}

if (! function_exists('truncate_float') ) {
    function truncate_float($number, $places) {
        $power = pow(10, $places);
        if($number > 0){
            return number_format((floor($number * $power) / $power), $places, '.', '');
        } else {
            return number_format((ceil($number * $power) / $power), $places, '.', '');
        }
    }
}

if (! function_exists('round_float') ) {
    function round_float($number, $places) {
        return number_format(round($number, $places), $places, '.', '');
    }
}

/**
 * Função ToUpperCase
 * @text = Texto a ser alterado para Caixa Alta
 * @encoding = Codificação de caractere desejada, se omitido usa o valor da codificação do caractere interna.
 * Font: http://php.net/manual/pt_BR/function.mb-strtoupper.php
 */
if (! function_exists('toUpperCase') ) {
    function toUpperCase($text, $encoding = '') {
        if ($encoding == '')
            $encoding = mb_internal_encoding();
        return mb_strtoupper($text, $encoding);
    }
}

/**
 * Função ToLowerCase
 * @text = Texto a ser alterado para Caixa Baixa
 * @encoding = Codificação de caractere desejada, se omitido usa o valor da codificação do caractere interna.
 * Font: http://php.net/manual/pt_BR/function.mb-strtoupper.php
 */
if (! function_exists('toLowerCase') ) {
    function toLowerCase($text, $encoding = '') {
        if ($encoding == '')
            $encoding = mb_internal_encoding();
        return mb_strtolower($text, $encoding);
    }
}

/**
 * Função custom_json_error_msg
 *
 * A função retorna uma mensagem customizada a partir do retorno de json_las_error()
 *
 * @no-params
 * @return string
 * Font: http://php.net/manual/pt_BR/function.json-last-error.php#refsect1-function.json-last-error-examples
 */
if (! function_exists('custom_json_error_msg')) {
    function custom_json_error_msg() {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return 'No errors';
            break;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
                return 'Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
            default:
                return 'Unknown error';
            break;
        }
    }
}

/**
 * Define Constants for Debug functions
 * DEBUG_ALL: gets all backtrace
 * DEBUG_first: gets the first index of the backtrace
 * DEBUG_ALL: gets last index of the backtrace
 * DEBUG_FROM: gets a limited amount of items from the begin of the backtrace
 * DEBUG_LAST: gets a limited amount of items from the end of the backtrace
*/
if (!defined('DEBUG_ALL')) {
    define('DEBUG_ALL', 'DEBUG_ALL');
}
if (!defined('DEBUG_FIRST')) {
    define('DEBUG_FIRST', 'DEBUG_FIRST');
}
if (!defined('DEBUG_LAST')) {
    define('DEBUG_LAST', 'DEBUG_LAST');
}
if (!defined('DEBUG_FROM')) {
    define('DEBUG_FROM', 'DEBUG_FROM');
}
if (!defined('DEBUG_TO')) {
    define('DEBUG_TO', 'DEBUG_TO');
}

/**
 * For use with Debugs functions bellow
 * Example:
 * dbgDD('var 1', \DebugLevel::get(DEBUG_ALL));
 * dbgDD('var 2', \DebugLevel::get(DEBUG_LAST));
 * dbgDD('var 3', \DebugLevel::get(DEBUG_TO, 3));
 * dbgDD('var 4', \DebugLevel::get(DEBUG_TO, 3));
 * dbgDD('var 5', \DebugLevel::get(DEBUG_FROM, 5, 3));
 * dbgDD('var 6', \DebugLevel::get(DEBUG_FROM, 5, 3));
*/
if (!class_exists('DebugLevel')) {
    class DebugLevel {
        public static function get($type=DEBUG_ALL, $length=1, $offset=null) {
            $obj        = new static();
            $obj->type  = $type;
            $obj->length = $length;
            $obj->offset = $offset;
            return $obj;
        }
    }
}

/**
 * Dump the passed variables.
 *
 * @param  mixed
 * @return void
 */
if (! function_exists('d')) {
    function d()
    {
        foreach (func_get_args() as $x) {
            (new \Symfony\Component\VarDumper\VarDumper)
                ->dump($x);
        };
    }
}

/**
 * Função dbgInfo
 * Debuga uma mensagem através de DD ou Var_dump, passando antes informaçõres da linha e do arquivo chamado!
 */
if (! function_exists('dbgInfo') ) {
    function dbgInfo($type = 'var_dump', $dbg) {

        if (isset($dbg['file'])) {
            $arr = array (
                'file'      => $dbg['file'] ?? '',
                'line'      => $dbg['line'] ?? ''
            );
        } else if (is_array($dbg)) {
            $arr = $dbg; //array_map(function($item) { return array('file' => $item['file']??'', 'line' => $item['line']??''); }, $dbg);
        }

        $args = func_get_args()[2];

        if ($type == 'd' or $type == 'dd') {
            (new \Symfony\Component\VarDumper\VarDumper)->dump($arr);
            foreach ($args as $x) {
                (new \Symfony\Component\VarDumper\VarDumper)->dump($x);
            }
            if ($type == 'dd')
                die();
        }

        if ($type == 'var_dump') {
            var_dump($arr);
            foreach ($args as $x) {
                var_dump($x);
            }
        }
    }
}

/**
 * Function to Get Debug Backtrace level (order and quantity) required, when passed to arguments.
 * See Examples in DebugLevel function comments
 */
if (! function_exists('dbgGetLevel') ) {
    function dbgGetLevel($dbt, &$args) {
        $last = end($args);
        reset($args);
        if ( $last instanceof \DebugLevel) {
            $last = array_pop($args);
            switch($last->type) {
                    case 'DEBUG_ALL': $dbg = $dbt; break;
                    case 'DEBUG_FIRST': $dbg = array_shift($dbt); break;
                    case 'DEBUG_LAST':  $dbg = array_pop($dbt); break;
                    case 'DEBUG_FROM':  $dbg = array_slice($dbt, $last->offset, $last->length); break;
                    case 'DEBUG_TO':    $dbg = array_slice($dbt, -$last->length, $last->offset); break;
            }
            if (end($dbg[0]['args']) instanceof \DebugLevel)
                $dbg[0]['args'] = array_slice($dbg[0]['args'], 0, -1);
        } else
             $dbg = array_shift($dbt);
        return $dbg;
    }
}

/**
 * Função dbgDD
 * Chama função dbgInfo passando como parametro 'DD'
 */
if (! function_exists('dbgD') ) {
    function dbgD() {
        $dbt = debug_backtrace();
        $args = (func_num_args() > 0 ? func_get_args() : null);
        $dbg = dbgGetLevel( $dbt, $args );
        dbgInfo('d', $dbg, $args);
    }
}

/**
 * Função dbgDD
 * Chama função dbgInfo passando como parametro 'DD'
 */
if (! function_exists('dbgDD') ) {
    function dbgDD() {
        $dbt = debug_backtrace();
        $args = (func_num_args() > 0 ? func_get_args() : null);
        $dbg = dbgGetLevel( $dbt, $args );
        dbgInfo('dd', $dbg, $args);
    }
}

/**
 * Função dbgVD
 * Chama função dbgInfo passando como parametro 'VD'
 */
if (! function_exists('dbgVD') ) {
    function dbgVD() {
        $dbt = debug_backtrace();
        $args = (func_num_args() > 0 ? func_get_args() : null);
        $dbg = dbgGetLevel( $dbt, $args );
        dbgInfo('var_dump', $dbg, $args);
    }
}

if (!function_exists('array_unset_recursive_key')) {
    function array_unset_recursive_key(&$array, $remove, $deep=1) {
        if (!is_array($remove))
            $remove = array($remove);
        foreach ($array as $key => &$value) {
            //if (is_array($value))
                //dd( $array, $key, $value, $remove, in_array($key, $remove) );
            if ($deep==1 and in_array($key, $remove))
                unset($array[$key]);
            else if ($deep>1 and is_array($value)) {
                array_unset_recursive_key($value, $remove, ($deep-1));
            }
        }
    }
}

if (!function_exists('array_unset_recursive')) {
    function array_unset_recursive(&$array, $remove) {
        if (!is_array($remove))
            $remove = array($remove);
        foreach ($array as $key => &$value) {
            if (in_array($value, $remove))
                unset($array[$key]);
            else if (is_array($value))
                array_unset_recursive($value, $remove);
        }
    }
}

if (!function_exists('getRequestAction')) {
    function getRequestAction()
    {
        try {
            return \Request::route()->getActionMethod() ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}