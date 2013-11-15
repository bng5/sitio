<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = $code.' Documento no encontrado';
//$this->breadcrumbs=array(
//	'Error',
//);

//var_dump(get_defined_vars());

?>

<img src="/img/404_coetc" alt="404" />

<div class="httperror error_404">
    <h2><span class="statuscode"><?php echo $code; ?></span> Documento no encontrado</h2>
    <p><?php echo CHtml::encode($message); ?></p>
    <div class="imagen"></div>
</div>

<h3>Otros destinos</h3>


<!--<pre class='xdebug-var-dump' dir='ltr'>
<b>array</b> <i>(size=11)</i>
  '_viewFile_' <font color='#888a85'>=&gt;</font> <small>string</small> <font color='#cc0000'>'<?php echo $_viewFile_; ?>'</font>
  '_data_' <font color='#888a85'>=&gt;</font> <?php print_r($_data_); ?>
  '_return_' <font color='#888a85'>=&gt;</font> <small>boolean</small> <font color='#75507b'><?php echo ($_return_ ? 'true' : 'false'); ?></font>
</pre>-->


<!--

<?php

var_dump(
    $code,
    $type,
    $errorCode,
    $message
);
var_dump($_SERVER['REQUEST_URI']);

?>
-->


