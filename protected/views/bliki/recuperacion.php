<?php

echo '<p><tt>'.__FILE__.'</tt></p>
';

foreach($model->tags AS $tag) {
    var_dump($tag->tag);
}

$this->breadcrumbs = array(
	'Bliki' => array('bliki/'),
);

if($model->isNewRecord) {
    $accion_actual = 'Creando '.$model->path;
    $this->breadcrumbs[] = $accion_actual;
}
else {
    $accion_actual = 'Editando '.$model->titulo;
    $this->breadcrumbs[$model->titulo] = array('bliki/'.$model->path);
    $this->breadcrumbs[] = 'edición';
}

//$this->unshiftPageTitle($accion_actual);

?>

<h1><?php echo $accion_actual; ?></h1>

<?php

if(Yii::app()->user->hasFlash('success')) {
    echo '
    <div class="flash-success">
        <p>Cambios guardados.</p>
    </div>';
}

if($error) {
    echo '
    <div class="info important">
        <h3>Aviso</h3>
        <p>'.$error.'</p>
    </div>';
}

if($prev) {
    echo $model->post;
}

?>

<div class="form">

<?php

$form = $this->beginWidget('CActiveForm', array(
	'id' => 'suscriptions-form',
	'enableAjaxValidation' => false,
));


//	<!-- p class="note">Fields with <span class="required">*</span> are required.</p -->

    
echo $form->errorSummary($model);
echo '
    <ul class="form">';

if(!$model->isNewRecord) {
    echo '
    <!-- div class="row" -->
        <li>
            '.$form->labelEx($model, 'id').'
            '.$model->id.'
        </li>
    <!-- /div -->';
}

?>


        
	<!-- div class="row" -->
        <li>
            <?php

            echo $form->labelEx($model, 'titulo');
            echo $form->textField($model, 'titulo',array('size' => 60,'maxlength' => 255));

            ?>
        </li>
    <!-- /div -->

    	<li>
        <?php
        
        echo $form->labelEx($model, 'path');
        echo $form->textField($model, 'path',array('size' => 60,'maxlength' => 255));
        echo $form->error($model, 'path');
        
        ?>
        </li>
    
    	<li>
        <?php
        
        echo $form->labelEx($model, 'post');
        echo $form->textArea($model, 'post', array ('rows' => '15', 'cols' => '68'));
        echo $form->error($model, 'post');
        
        ?>
        </li>
    
        <li>
        <?php
        
        echo $form->labelEx($model, 'fecha_creado');
        echo "<span id=\"mostrar_fecha1\">".$this->formato_fecha($model->fecha_creado)."</span>&nbsp;&nbsp;<img src=\"/img/silk/calendar\" id=\"tn_calendario1\" style=\"cursor: pointer;\" title=\"Abrir calendario\" alt=\"Abrir calendario\" />";
        echo $form->hiddenField($model, 'fecha_creado');
        
        ?>
        </li>

        <li>
        <?php
        
        echo $form->labelEx($model, 'fecha_modificado');
        echo "<span id=\"mostrar_fecha2\">".$this->formato_fecha($model->fecha_modificado)."</span>&nbsp;&nbsp;<img src=\"/img/silk/calendar\" id=\"tn_calendario2\" style=\"cursor: pointer;\" title=\"Abrir calendario\" alt=\"Abrir calendario\" />";
        echo $form->hiddenField($model, 'fecha_modificado');

        ?>
        </li>
    </ul>


	<div class="row buttons">
		<?php
        
        echo CHtml::submitButton('Previsualizar');
        $guardar_label = $model->isNewRecord ? 'Crear' : 'Guardar';
		echo CHtml::submitButton($guardar_label);
		echo CHtml::submitButton($guardar_label.'/Publicar');
        
        ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php

$formato = "%s";
$formatoMst = "%A, %d de %B de %Y, %H:%M hs.";
$mostrarHora = "true";

echo "
        
<script type=\"text/javascript\">
//<![CDATA[
Calendar.setup({
    inputField: \"Post_fecha_creado\",
    ifFormat: \"{$formato}\",
    displayArea: \"mostrar_fecha1\",
    daFormat: \"{$formatoMst}\",
    button: \"tn_calendario1\",
    showsTime: {$mostrarHora}
});
Calendar.setup({
    inputField: \"Post_fecha_modificado\",
    ifFormat: \"{$formato}\",
    displayArea: \"mostrar_fecha2\",
    daFormat: \"{$formatoMst}\",
    button: \"tn_calendario2\",
    showsTime: {$mostrarHora}
});
//]]>
</script>
";




Yii::app()->clientScript->registerScriptFile('/js/calendar.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/calendar_es-uy.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/calendar-setup.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile('/css/calendario.css');
?>