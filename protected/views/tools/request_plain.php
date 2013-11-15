<?php

function mostrarValor($valor, $indent = 1) {
    $indent_str = str_repeat('  ', $indent);
    $retorno = '';
//    $retorno .= $indent_str;
    if(is_null($valor)) {
        $retorno .= "null\n";
    }
    elseif(is_string($valor)) {
        $retorno .= "'{$valor}' (".strlen($valor)." bytes)\n";
    }
    elseif(is_array($valor)) {
        if(count($valor)) {
            $retorno = "\n";
            foreach($valor AS $k => $v) {
                $retorno .= "{$indent_str}";
                if(is_string($k))
                    $retorno .= "{$k}: ";
                $retorno .= mostrarValor($v, ++$indent);
                $indent--;
            }
        }
        else {
            $retorno = "empty\n";
        }
    }
    return $retorno;
}

foreach($captura AS $k => $v) {
    echo "
{$k}
".  str_repeat('-', mb_strlen($k))."
".mostrarValor($v);
}


//var_dump($_SERVER);
//var_dump($captura);

?>
