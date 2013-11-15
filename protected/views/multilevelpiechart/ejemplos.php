<?php

Yii::app()->clientScript->registerScriptFile('/js/multilevelpiechart/multilevelpiechart.js', CClientScript::POS_HEAD);
$this->breadcrumbs[] = 'Básico';

ob_start();
$included = @include('js/multilevelpiechart/examples/'.$file.'.js');
$source = ob_get_clean();

Yii::app()->clientScript->registerScript('drawchart',"

{$source}

//});
", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('init',"
//$(function() {

drawchart();

//});
", CClientScript::POS_READY);

?>

<h1>Básico</h1>

<h3>Ejemplo</h3>
<div id="contenedor" style="width: 800px;"></div>

<h3>Código</h3>

<?php


require_once('geshi.php');
$geshi = new GeSHi($source, 'javascript');
//$geshi->set_link_target('_blank');
$geshi->set_header_type(GESHI_HEADER_DIV);//GESHI_HEADER_NONE);//GESHI_HEADER_PRE);//
$geshi->enable_classes();
$geshi->set_link_styles(GESHI_LINK, 'color: #000060;');
$geshi->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');

Yii::app()->clientScript->registerCss('geshi', "
".$geshi->get_stylesheet()."
");

//if(!($parsed_code = Yii::app()->cache->get('geshi:mlpc:'.$file))) {
    $parsed_code = $geshi->parse_code();
    Yii::app()->cache->set('geshi:mlpc:'.$file, $parsed_code);
//}
echo $parsed_code;


/******************************************************************************/



?>
