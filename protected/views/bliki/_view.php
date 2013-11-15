<?php
/* @var $this AdminController */
/* @var $data Post */

// echo CHtml::encode($data->getAttributeLabel('id'));

?>

<div class="view">
    
    <h2><?php 
    
//    echo $data->titulo;
    echo CHtml::link(CHtml::encode($data->titulo), array($data->path));
    ?></h2>

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_creado')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_creado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_modificado')); ?>:</b>
	<?php echo CHtml::encode($data->fecha_modificado); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resumen')); ?>:</b>
	<?php echo CHtml::encode($data->resumen); ?>
	<br />


</div>