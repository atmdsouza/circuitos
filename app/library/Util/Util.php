<?php

namespace Util;

date_default_timezone_set('America/Belem');

class Util {
    /*
     * Função Máscara
     * Exemplo de uso:

      $cnpj = "11222333000199";
      $cpf = "00100200300";
      $cep = "08665110";
      $data = "10102010";

     * Aplicando as máscaras 
      echo mask($cnpj,'##.###.###/####-##');
      echo mask($cpf,'###.###.###-##');
      echo mask($cep,'#####-###');
      echo mask($data,'##/##/####');
     * 
     * Saídas
      11.222.333/0001-99
      001.002.003-00
      08665-110
      10/10/2010
     *      */

    public function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            }
            else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    public function retiraAcentos($texto) {
        $array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç"
        , "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
        $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c"
        , "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C");
        return str_replace($array1, $array2, $texto);
    }

    public function converterParaMaiuscula($string) {
        $maiuscula = strtoupper($string);
        return $maiuscula;
    }

    public function converterDataHoraParaBr($data_americana) {
        if (!empty($data_americana)) {
            $data_americana = strtotime($data_americana);
            $data_br = date('d/m/Y H:i:s', $data_americana);

            return $data_br;
        } else {
            return null;
        }
    }

    public function converterDataParaBr($data_americana) {
        if (!empty($data_americana)) {
            $data_americana = strtotime($data_americana);
            $data_br = date('d/m/Y', $data_americana);

            return $data_br;
        } else {
            return null;
        }
    }

    public function converterDataHoraUSA($data_brasil) {

        $data_brasil = strtotime($data_brasil); // Gera o timestamp de $data_mysql
        $data_usa = date('Y-m-d H:i:s', $data_brasil);

        return $data_usa; //retorna o valor formatado para gravar no banco
    }

    public function converterDataUSA($data_brasil) {

        $data_brasil1 = $data_brasil;
        $data_brasil2 = explode('/', $data_brasil1);
        $data_brasil3 = array_reverse($data_brasil2);
        $data_usa = implode('-', $data_brasil3);

        return $data_usa; //retorna o valor formatado para gravar no banco
    }

    public function formataCpfCnpj($cpf_cnpj) {
        $source = array('.', '/', '-', ',', '(', ')', ' ');
        $cpf_cnpj = str_replace($source, '', $cpf_cnpj);

        return $cpf_cnpj; //retorna o valor formatado para gravar no banco
    }

    public function formataFone($fone) {
        $source = array('(', ')', '-', ' ');
        $tel = str_replace($source, '', $fone);
        $ddd = substr($tel, 0, 2);
        $telefone = substr($tel, 2);
        $arr = array();
        $arr['ddd'] = $ddd;
        $arr['fone'] = $telefone;

        return $arr; //retorna o valor formatado para gravar no banco
    }

    public function formataNumeroTelefone($phone) {
        $phones = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        $source = array('(', ')', '-', ' ');
        $tel = str_replace($source, '', $phones);
        return $tel;
    }

    public function formataNumeroMoeda($valor_americano) {
        $valor = number_format($valor_americano, 2, '.', ''); //remove os pontos e substitui a virgula pelo ponto
        return $valor; //retorna o valor formatado para gravar no banco
    }

    public function formataMoedaReal($valor_americano) {
        $valor = number_format($valor_americano, 2, ',', '.');
        return $valor; //retorna o valor formatado para gravar no banco
    }

    public function formataNumero($get_valor) {
        $source = array('.', ',');
        $replace = array('', '.');
        $valor = str_replace($source, $replace, $get_valor); //remove os pontos e substitui a virgula pelo ponto
        return $valor; //retorna o valor formatado para gravar no banco
    }

    public function calculaTempo($data1, $data2) {
        $DataFuturo = $data2;
        $DataAtual = $data1;

        $date_time = new DateTime($DataAtual);
        $diff = $date_time->diff(new DateTime($DataFuturo));
        return $diff->format('%H hora(s), %i minuto(s) e %s segundo(s)');
    }

    /**
     * Função para listar os aquivos dentro de um diretório expecificado
     * @author Kalil Kelvin <kalil@emagesolucoes.com.br>
     * @version 1.0
     * @param String $dir Caminho do diretório a ser listado
     * @return array Array com os arquivos
     */
    public function listaDir($dir) {
        $ponteiro = opendir($dir);
        // monta os vetores com os itens encontrados na pasta
        while ($nome_itens = readdir($ponteiro)) {
            $itens[] = $nome_itens;
        }
        // ordena o vetor de itens
        sort($itens);
        // percorre o vetor para fazer a separacao entre arquivos e pastas 
        foreach ($itens as $listar) {
            // retira "./" e "../" para que retorne apenas pastas e arquivos
            if ($listar != "." && $listar != "..") {
                // checa se o tipo de arquivo encontrado é uma pasta
                if (is_dir($listar)) {
                    // caso VERDADEIRO adiciona o item à variável de pastas
                    $pastas[] = $listar;
                } else {
                    // caso FALSO adiciona o item à variável de arquivos
                    $arquivos[] = $listar;
                }
            } else {
                $arquivos = '';
            }
        }
        // lista os arquivos se houverem
        if ($arquivos != "") {
            return $arquivos;
        } else {
            return false;
        }
    }

    public function recuperarLastId($tabela, $coluna) {
        $model = new Cadastros_Model_Cliente();
        $select = $model->fetchRow($model->select()
            ->setIntegrityCheck(false)
            ->from($tabela, "MAX($coluna) AS COLUNA")
        );
        $lastId = (int) $select["COLUNA"];
        if ($lastId == null) {
            $lastId = 1;
        } else {
            $lastId += 1;
        }
        return $lastId;
    }

    public function latlng($endereco) {
        $endereco = str_replace(" ", "+", $endereco);
        $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$endereco&amp;sensor=false&amp;region=BR");
        $json = json_decode($json);
        $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
        $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
        return array('latitude' => $lat, 'longitude' => $long);
    }

    public function end_Latlng($lat, $lng) {
        $json1 = file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lng&sensor=true");
        $json = json_decode($json1);
        $endereco = $json->{'results'}[0]->{'formatted_address'};
        return $endereco;
    }

    /**
     * Função que calcula dias úteis no mês
     *
     * @autor Carlos Maniero
     */
    public function dias_uteis($mes, $ano) {
        $uteis = 0;
        // Obtém o número de dias no mês 
        // (http://php.net/manual/en/function.cal-days-in-month.php)
        $dias_no_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        for ($dia = 1; $dia <= $dias_no_mes; $dia++) {
            // Aqui você pode verifica se tem feriado
            // ----------------------------------------
            // Obtém o timestamp
            // (http://php.net/manual/pt_BR/function.mktime.php)
            $timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
            $semana = date("N", $timestamp);
            if ($semana < 7) //Considerando o sábado como dia de trabalho
                $uteis++;
        }
        return $uteis;
    }

    //CALCULANDO DIAS NORMAIS
    /* Abaixo vamos calcular a diferença entre duas datas. Fazemos uma reversão da maior sobre a menor 
      para não termos um resultado negativo. */
    public function CalculaDias($xDataInicial, $xDataFinal) {
        $util = new Recursos_Util();
        $time1 = $util->dataToTimestamp($xDataInicial);
        $time2 = $util->dataToTimestamp($xDataFinal);
        $tMaior = $time1 > $time2 ? $time1 : $time2;
        $tMenor = $time1 < $time2 ? $time1 : $time2;
        $diff = $tMaior - $tMenor;
        $numDias = $diff / 86400; //86400 é o número de segundos que 1 dia possui  
        return $numDias;
    }

    //LISTA DE FERIADOS NO ANO
    /* Abaixo criamos um array para registrar todos os feriados existentes durante o ano. */
    public function Feriados($ano, $posicao) {
        $dia = 86400;
        $datas = array();
        $datas['pascoa'] = easter_date($ano);
        $datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
        $datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
        $datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);
        $feriados = array(
            '01/01',
            date('d/m', $datas['carnaval']),
            date('d/m', $datas['sexta_santa']),
            date('d/m', $datas['pascoa']),
            '21/04',
            '01/05',
            date('d/m', $datas['corpus_cristi']),
            '15/08',
            '12/10',
            '02/11',
            '15/11',
            '25/12'
        );

        return $feriados[$posicao] . "/" . $ano;
    }

    //FORMATA COMO TIMESTAMP
    /* Esta função é bem simples, e foi criada somente para nos ajudar a formatar a data já em formato  TimeStamp facilitando nossa soma de dias para uma data qualquer. */
    public function dataToTimestamp($data) {
        $ano = substr($data, 6, 4);
        $mes = substr($data, 3, 2);
        $dia = substr($data, 0, 2);
        return mktime(0, 0, 0, $mes, $dia, $ano);
    }

    //SOMA 01 DIA   
    public function Soma1dia($data) {
        $ano = substr($data, 6, 4);
        $mes = substr($data, 3, 2);
        $dia = substr($data, 0, 2);
        return date("d/m/Y", mktime(0, 0, 0, $mes, $dia + 1, $ano));
    }

    //CALCULA DIAS UTEIS (Sábado útil)
    /* É nesta função que faremos o calculo. Abaixo podemos ver que faremos o cálculo normal de dias ($calculoDias), após este cálculo, faremos a comparação de dia a dia, verificando se este dia é um sábado, domingo ou feriado e em qualquer destas condições iremos incrementar 1 */
    public function DiasUteisFeriado($yDataInicial, $yDataFinal) {
        $util = new Recursos_Util();
        $diaFDS = 0; //dias não úteis(Sábado=6 Domingo=0)
        $calculoDias = $util->CalculaDias($yDataInicial, $yDataFinal); //número de dias entre a data inicial e a final
        $diasUteis = 0;
        while ($yDataInicial != $yDataFinal) {
            $diaSemana = date("w", $util->dataToTimestamp($yDataInicial));
            if ($diaSemana == 0) {
                //se DOMINGO, SOMA 01
                $diaFDS++;
            } else {
                //senão vemos se este dia é FERIADO
                for ($i = 0; $i <= 11; $i++) {
                    if ($yDataInicial == $util->Feriados(date("Y"), $i)) {
                        $diaFDS++;
                    }
                }
            }
            $yDataInicial = $util->Soma1dia($yDataInicial); //dia + 1
        }
        $total_dias_uteis = ($calculoDias + 1) - $diaFDS;
        return $total_dias_uteis;
    }

    //CALCULA DIAS UTEIS (Segunda a Sexta)
    /* É nesta função que faremos o calculo. Abaixo podemos ver que faremos o cálculo normal de dias ($calculoDias), após este cálculo, faremos a comparação de dia a dia, verificando se este dia é um sábado, domingo ou feriado e em qualquer destas condições iremos incrementar 1 */
    public function DiasUteisFeriadosemSabado($yDataInicial, $yDataFinal) {
        $util = new Recursos_Util();
        $diaFDS = 0; //dias não úteis(Sábado=6 Domingo=0)
        $calculoDias = $util->CalculaDias($yDataInicial, $yDataFinal); //número de dias entre a data inicial e a final
        $diasUteis = 0;

        while ($yDataInicial != $yDataFinal) {
            $diaSemana = date("w", $util->dataToTimestamp($yDataInicial));
            if ($diaSemana == 0 || $diaSemana == 6) {
                //se SABADO OU DOMINGO, SOMA 01
                $diaFDS++;
            } else {
                //senão vemos se este dia é FERIADO
                for ($i = 0; $i <= 11; $i++) {
                    if ($yDataInicial == $util->Feriados(date("Y"), $i)) {
                        $diaFDS++;
                    }
                }
            }
            $yDataInicial = $util->Soma1dia($yDataInicial); //dia + 1
        }
        $total_dias_uteis = ($calculoDias + 1) - $diaFDS;
        return $total_dias_uteis;
    }

    /*
     * Função que cálcula um intervalo de 1 mes entre datas
     */

    public function datasArray($data_inicio, $data_fim = null) {
        $data_fim = !$data_fim ? date('d-m-Y') : $data_fim;

        list($dia, $mes, $ano) = explode("-", $data_inicio);

        $dataInicial = getdate(strtotime($data_inicio));
        $dataFinal = getdate(strtotime($data_fim));
        $dif = (($dataFinal[0] - $dataInicial[0]) / 86400);
        $meses = round($dif / 30) + 1;  // +1 serve para adiconar a data fim no array

        for ($x = 0; $x < $meses; $x++) {
            $datas[] = date("d-m-Y", strtotime("+" . $x . " month", mktime(0, 0, 0, $mes, $dia, $ano)));
        }

        return $datas;
    }

    /*
     * Função que retorna o último dia do mês baseado na data fornecida
     */

    public function ultimoDiaMes($data) {
        $date = new DateTime($data);
        $date->modify('last day of this month');
        return $date->format('Y-m-d');
    }

    /**
     * (PHP 4, PHP 5, PHP 7)<br/>
     * Split a string by string
     * @link http://php.net/manual/en/function.explode.php
     * @param string $delimiter <p>
     * The boundary string.
     * </p>
     * @param string $string <p>
     * The input string.
     * </p>
     * @param int $limit [optional] <p>
     * If <i>limit</i> is set and positive, the returned array will contain
     * a maximum of <i>limit</i> elements with the last
     * element containing the rest of <i>string</i>.
     * </p>
     * <p>
     * If the <i>limit</i> parameter is negative, all components
     * except the last -<i>limit</i> are returned.
     * </p>
     * <p>
     * If the <i>limit</i> parameter is zero, then this is treated as 1.
     * </p>
     * @return array an array of strings
     * created by splitting the <i>string</i> parameter on
     * boundaries formed by the <i>delimiter</i>.
     * </p>
     * <p>
     * If <i>delimiter</i> is an empty string (""),
     * <b>explode</b> will return <b>FALSE</b>.
     * If <i>delimiter</i> contains a value that is not
     * contained in <i>string</i> and a negative
     * <i>limit</i> is used, then an empty array will be
     * returned, otherwise an array containing
     * <i>string</i> will be returned.
     */
    public function diaUteisMes($data_inicial, $data_final) {
        $util = new Recursos_Util();
        $data_inicio = explode("-", $data_inicial);
        $mes_inicio = $data_inicio[1];
        $mes_inicio2 = $data_inicio[1];
        $ano_inicio = $data_inicio[0];

        $data_fim = explode("-", $data_final);
        $mes_fim = $data_fim[1];
        $ano_fim = $data_fim[0];

        $arr_meses = array();

        while ((int) $mes_inicio <= (int) $mes_fim) {
            if ((int) $mes_inicio == (int) $mes_inicio2) {//Primeiro mês
                if ((int) $mes_inicio <= (int) $mes_fim) {
                    $primeir_data = $util->converterDataParaBr($data_inicial);
                    $segunda_data = $util->converterDataParaBr($data_final);
                    $dias_uteis = $util->DiasUteisFeriado($primeir_data, $segunda_data);
                    $arr_meses += [(int) $mes_inicio => $dias_uteis];
                } else {
                    $ultimo_dia_mes = $util->ultimoDiaMes($data_inicial);
                    $primeir_data = $util->converterDataParaBr($data_inicial);
                    $segunda_data = $util->converterDataParaBr($ultimo_dia_mes);
                    $dias_uteis = $util->DiasUteisFeriado($primeir_data, $segunda_data);
                    $arr_meses += [(int) $mes_inicio => $dias_uteis];
                }
            } elseif ((int) $mes_inicio == (int) $mes_fim) {//Último mês
                $primeiro_dia_mes = $ano_fim . "-" . $mes_inicio . "-01";
                $primeir_data = $util->converterDataParaBr($primeiro_dia_mes);
                $segunda_data = $util->converterDataParaBr($data_final);
                $dias_uteis = $util->DiasUteisFeriado($primeir_data, $segunda_data);
                $arr_meses += [(int) $mes_inicio => $dias_uteis];
            } else {//Meses intermediários
                $primeiro_dia_mes = $ano_inicio . "-" . $mes_inicio . "-01";
                $ultimo_dia_mes = $util->ultimoDiaMes($primeiro_dia_mes);
                $primeir_data = $util->converterDataParaBr($primeiro_dia_mes);
                $segunda_data = $util->converterDataParaBr($ultimo_dia_mes);
                $dias_uteis = $util->DiasUteisFeriado($primeir_data, $segunda_data);
                $arr_meses += [(int) $mes_inicio => $dias_uteis];
            }
            (int) $mes_inicio++; //incremento de mais um mês
        }
        return $arr_meses;
    }

    /*
     * Funções para fazer Debug
     */

    public function debug_var($variavel) {
        echo "<pre >";
        var_dump($variavel);
        echo "</pre>";
        die();
    }

    public function debug() {
        $debug_arr = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $line = $debug_arr[0]['line'];
        $file = $debug_arr[0]['file'];

        header('Content-Type: text/plain');

        echo "linha: $line\n";
        echo "arquivo: $file\n\n";
        print_r(array('GET' => $_GET, 'POST' => $_POST, 'SERVER' => $_SERVER));
        exit;
    }

    public function validate_email($email) {
        if ($email) {
            $er = "/^(([0-9a-zA-Z]+[-._+&])*[0-9a-zA-Z]+@([-0-9a-zA-Z]+[.])+[a-zA-Z]{2,6}){0,1}$/";
            if (preg_match($er, $email)) {
                $dom = explode("@", $email);
                if (checkdnsrr($dom[1], "MX")) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function formataTelefoneSMS($phone) {
        $phones = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        $source = array('(', ')', '-', ' ');
        $tel = str_replace($source, '', $phones);
        $tam = strlen($tel);
        $telefonesms = null;
        if ($tam > 11) {
            $zero = substr($tel, 0, 1);
            switch ($zero) {
                case "0":
                    $telefonesms = substr($tel, 1);
                    break;
                default:
                    $telefonesms = $tel;
                    break;
            }
        } elseif ($tam == 11) {
            $zero = substr($tel, 0, 1);
            switch ($zero) {
                case "0":
                    $tel5 = substr($tel, 1);
                    $tel3 = substr($tel5, 0, 2);
                    $tel4 = substr($tel5, 2);
                    $inicial = substr($tel4, 0, 1);
                    switch ($inicial) {
                        case "9":
                            $telefonesms = $tel3 . "9" . $tel4;
                            break;
                        case "8":
                            $telefonesms = $tel3 . "9" . $tel4;
                            break;
                        case "7":
                            $telefonesms = $tel3 . "9" . $tel4;
                            break;
                        default:
                            $telefonesms = null;
                            break;
                    }
                    break;
                default:
                    $telefonesms = $tel;
                    break;
            }
        } elseif ($tam == 10) {
            $tel3 = substr($tel, 0, 2);
            $tel4 = substr($tel, 2);
            $inicial = substr($tel4, 0, 1);
            switch ($inicial) {
                case "9":
                    $telefonesms = $tel3 . "9" . $tel4;
                    break;
                case "8":
                    $telefonesms = $tel3 . "9" . $tel4;
                    break;
                case "7":
                    $telefonesms = $tel3 . "9" . $tel4;
                    break;
                default:
                    $telefonesms = null;
                    break;
            }
        } elseif ($tam == 8) {
            $inicial = substr($tel, 0, 1);
            switch ($inicial) {
                case "9":
                    $telefonesms = "919" . $tel;
                    break;
                case "8":
                    $telefonesms = "919" . $tel;
                    break;
                case "7":
                    $telefonesms = "919" . $tel;
                    break;
                default:
                    $telefonesms = null;
                    break;
            }
        } elseif ($tam == 9) {
            $telefonesms = "91" . $tel;
        }
        if ($telefonesms) {
            return $telefonesms; //retorna o valor formatado para enviar o SMS
        } else {
            return false;
        }
    }

    public function selecionaTelefone($id_cliente) {
        $model_pedidos = new Pedidos_Model_Pedido();
        $util = new Recursos_Util();
        $cliente = $model_pedidos->findAuxiliar("CLIENTES", "CODIGOCLIENTE=$id_cliente");
        $celular = null;
        $telefone1 = null;
        $telefone2 = null;
        $telefone3 = null;
        $telefone4 = null;
        if (!empty($cliente["TELEFONEWHATSAPP"])) {
            $telefone1 = $util->formataTelefoneSMS($cliente["TELEFONEWHATSAPP"]);
        }
        if (!empty($cliente["FAX_CELULAR"])) {
            $telefone2 = $util->formataTelefoneSMS($cliente["FAX_CELULAR"]);
        }
        if (!empty($cliente["TELEFONE2"])) {
            $telefone3 = $util->formataTelefoneSMS($cliente["TELEFONE2"]);
        }
        if (!empty($cliente["TELEFONE1"])) {
            $telefone4 = $util->formataTelefoneSMS($cliente["TELEFONE1"]);
        }
        if ($telefone1) {
            $celular = $telefone1;
        } elseif ($telefone2) {
            $celular = $telefone2;
        } elseif ($telefone3) {
            $celular = $telefone3;
        } elseif ($telefone4) {
            $celular = $telefone4;
        }
        if ($celular) {
            return $celular;
        } else {
            return false;
        }
    }

    public function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d') {
        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
        while ($current <= $last) {

            $dates[] = date($format, $current);
            $current = strtotime($step, $current);
        }
        return $dates;
    }

    public function removerFormatacaoNumero($strNumero)
    {
        $strNumero = trim(str_replace("R$", null, $strNumero));
        $vetVirgula = explode(",", $strNumero);
        if (count($vetVirgula) == 1)
        {
            $acentos = array(".");
            $resultado = str_replace($acentos, "", $strNumero);
            return $resultado;
        }
        else if (count($vetVirgula) != 2)
        {
            return $strNumero;
        }
        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr($vetVirgula[1], 0, 2);
        $acentos = array(".");
        $resultado = str_replace($acentos, "", $strNumero);
        $resultado = $resultado . "." . $strDecimal;
        return $resultado;
    }

    public function converteExtenso($valor = 0, $bolExibirMoeda = false, $bolPalavraFeminina = false)
    {
        $valor = self::removerFormatacaoNumero($valor);
        $singular = null;
        $plural = null;
        if ($bolExibirMoeda)
        {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        else
        {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
        if ($bolPalavraFeminina)
        {
            if ($valor == 1)
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            else
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }
        $z = 0;
        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++)
            for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++)
                $inteiro[$i] = "0" . $inteiro[$i];
        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        for ($i = 0; $i < count($inteiro); $i++)
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000")
                $z++;
            elseif ($z > 0)
                $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0))
                $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r)
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }
        $rt = mb_substr($rt, 1);
        return($rt ? trim($rt) : "zero");
    }

}
