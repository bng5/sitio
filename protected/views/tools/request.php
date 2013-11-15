<?php

function mostrarValor($valor) {
    if(is_null($valor)) {
        $retorno = "<var style=\"color:#3465a4;\">null</var>\n";
    }
    elseif(is_string($valor)) {
        $retorno = "<span style=\"color:#cc0000;\">'{$valor}'</span> <small>".strlen($valor)." bytes</small>";
    }
    elseif(is_array($valor)) {
        if(count($valor)) {
            $retorno = '
                <ul>';
            foreach($valor AS $k => $v) {
                $retorno .= "
                    <li><em>{$k}:</em> ".mostrarValor($v)."</li>";
            }
            $retorno .= '
                </ul>';
        }
        else {
            $retorno = '<i style="color:#888a85;">empty</i>';
        }
    }
    return $retorno;
}

echo "
    <dl>";
foreach($captura AS $k => $v) {
    echo "
        <dt>{$k}</dt>
        <dd>".mostrarValor($v).'</dd>';
}
echo "
    </dl>";

?>

<noscript><p>Su navegador no cuenta con JavaScript habilitado.</p></noscript>

<?php

Yii::app()->clientScript->registerScript('search', "

$('#screen_info').html('<table>'+
    '<tr><td>'+screen.availHeight +'</td><td>availHeight</td> <td>Specifies the height of the screen, in pixels, minus interface features such as the taskbar in Windows.</td></tr>'+
    '<tr><td>'+screen.availWidth  +'</td><td>availWidth</td>  <td>Specifies the width of the screen, in pixels, minus interface features such as the taskbar in Windows.</td></tr>'+
    '<tr><td>'+screen.colorDepth  +'</td><td>colorDepth</td>  <td>The bit depth of the color palette available for displaying images in bits per pixel.</td></tr>'+
    '<tr><td>'+screen.height      +'</td><td>height</td>      <td>The total height of the screen, in pixels.</td></tr>'+
    '<tr><td>'+screen.width       +'</td><td>width</td>       <td>The total width of the screen, in pixels.</td></tr>'+
    '</table>');
    
//console.log(screen);

", CClientScript::POS_READY);


//var_dump($_SERVER);
//var_dump($captura);

?>

<div id="screen_info"></div>
