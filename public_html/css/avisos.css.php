<?php

/**
    Document   : avisos
    Created on : 03/02/2011, 06:15:29 PM
    Author     : pablo
    Description:
        Purpose of the stylesheet follows.
 *
 *
 * @param estilo
 * @param classnames ...
*/

//$estilo = $_GET['estilo'] ? $_GET['estilo'] : 'redhat';//'yelp';
$estilo = $_GET['estilo'] ? $_GET['estilo'] : 'yelp';
$tam = $_GET['tam'] ? (int) $_GET['tam'] : 48;


$classAvisos = $_GET['classAvisos'] ? ($_GET['classAvisos'] == 'false' ? 'div.caution, div.important, div.note, div.tip, div.warning' : 'div.'.$_GET['classAvisos']) : 'div.aviso';
$classCaution = $_GET['classCaution'] ? $_GET['classCaution'] : 'caution';
$classImportant = $_GET['classImportant'] ? $_GET[''] : 'important';
$classNote = $_GET['classNote'] ? $_GET['classNote'] : 'note';
$classTip = $_GET['classTip'] ? $_GET['classTip'] : 'tip';
$classWarning = $_GET['classWarning'] ? $_GET['classWarning'] : 'warning';

header("Content-Type: text/css; charset=UTF-8");


if(!@include('./avisos/'.$estilo.'.php')) {
    header("Content-Type: text/css; charset=UTF-8", true, 404);
    echo '404 Not Found';
}

?>
