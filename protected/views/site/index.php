<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

// echo CHtml::encode(Yii::app()->name);
//	echo $this->getLayoutFile('main');


/*
<h2><a name="articulos">Artículos</a></h2>
<div class="entry">
    <h3><a>Hola mundo en jQuery</a></h3>
    <p>
        Player YouTube <a href="https://twitter.com/bng5/status/13412106948">https://twitter.com/bng5/status/13412106948</a>
    </p>
</div>
<!-- hr / -->
<div class="entry">
    <h3><a>SubSync</a></h3>
    <p>
        Star Trek
    </p>
</div>
<!-- hr / -->
<!-- div class="entry">
    <h3><a></a></h3>
    <p>
    </p>
</div -->
<!-- hr / -->


<h2>Mapa del sitio</h2>
<ul>
    <li><a href="/mysqldiff">/mysqldiff</a></li>
    <li><a href="/bliki">/bliki</a>
        <ul>
            <li><a href="/bliki/multi_level_pie_chart">Multi-level pie chart</a></li>
            <li><a href="/bliki/sub_sync">Sub-sync</a></li>
            <li><a href="/bliki/youtube_jquery_chromeless_player">/chromeless_player (Hola mundo en jQuery)</a></li>
            <li><a href="/bliki/api_alertas">API Alertas</a></li>
        </ul>
    <li><a href="/experimentos/">/experimentos</a>
        <ul>
            <li><a href="/experimentos/css">/css</a>
                <ul>
                    <li><a href="/experimentos/">/angulos (CSS ángulos irregulares [Lugano])</a></li>
                </ul>
            </li>
            <li><a href="/experimentos/javascript">/javascript</a>
                <ul>
                    <li><a href="/experimentos/javascript/">/eventos_imagenes</a></li>
                </ul>
            </li>
            <li><a href="/experimentos/xul">XUL</a>
                <ul>
                    <li><a href="/experimentos/xul/gtk_sock">GTK Stock</a></li>
                </ul>
            </li>
        </ul>
    </li>
</ul>

<h2>Subdominios</h2>
<dl>
    <dt><a href="http://3po.bng5.net">3po.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://archivo.bng5.net">archivo.bng5.net</a></dt>
    <dd>
        <ul>
            <li><a href="http://archivo.bng5.net/quitline.com.uy/">http://archivo.bng5.net/quitline.com.uy/</a></li>
        </ul>
    </dd>

    <dt><a href="http://bng5.haciendoruido.net">bng5.haciendoruido.net</a></dt>
    <dd></dd>

    <dt><a href="http://cms.bng5.net">cms.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://demo.bng5.net">demo.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://admin.demo.bng5.net">admin.demo.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://dlugano.bng5.net">dlugano.bng5.net</a></dt>
    <dd>Eliminar subdominio, mover junto con QuitLine</dd>

    <dt><a href="http://dwiki.bng5.net">dwiki.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://erhitran.bng5.net">erhitran.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://facebook.bng5.net">facebook.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://fotos.bng5.net">fotos.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://gitbook.bng5.net">gitbook.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://juegos.bng5.net">juegos.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://oauth.bng5.net">oauth.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://pablo.bng5.net">pablo.bng5.net</a></dt>
    <dd></dd>

    <dt><a href="http://pear.bng5.net">pear.bng5.net</a></dt>
    <dd></dd>

</dl>

<?php

var_dump($matches);

?>


<pre>

<?php

echo "
REDIRECT_QUERY_STRING       {$_SERVER["REDIRECT_QUERY_STRING"]}
REDIRECT_URL                {$_SERVER["REDIRECT_URL"]}
REQUEST_METHOD              {$_SERVER["REQUEST_METHOD"]}
QUERY_STRING                {$_SERVER["QUERY_STRING"]}
REQUEST_URI                 {$_SERVER["REQUEST_URI"]}

SITIO_TITULO                ".SITIO_TITULO."
APPLICATION_PATH            ".APPLICATION_PATH."
CMS_PATH                    ".CMS_PATH."
";

var_dump($_GET);

?>

</pre>


<ul>
    <li><a href="template">Template</a></li>
    <li><a href="template2">Template2</a></li>
</ul>
*/

//echo CHtml::link('Multi-level pie chart', array('/multilevelpiechart'));



if(isset($model)) {
    foreach($model as $data) {
        
?>

        <h2><?php 
//    echo $data->titulo;
    echo CHtml::link(CHtml::encode($data->title), array('bliki/'.$data->_id));
    ?></h2>
<?php
continue;
?>
        <b><?php echo CHtml::encode($data->getAttributeLabel('fecha_creado')); ?>:</b>
        <?php echo $this->formato_fecha($data->fecha_creado); ?> (<?php echo gettype($data->fecha_creado).' '.$data->fecha_creado; ?>)
        <br />

        <b><?php echo CHtml::encode($data->getAttributeLabel('fecha_modificado')); ?>:</b>
        <?php echo $this->formato_fecha($data->fecha_modificado); ?> (<?php echo gettype($data->fecha_modificado).' '.$data->fecha_modificado; ?>)
        <br />

        <b><?php echo CHtml::encode($data->getAttributeLabel('resumen')); ?>:</b>
        <?php echo CHtml::encode($data->resumen); ?>
        <br />
    
<?php

    }
}
        
if(isset($dataProvider)) {
    
    foreach($dataProvider->data as $data) {

?>

    
    <h2><?php 
    
//    echo $data->titulo;
    echo CHtml::link(CHtml::encode($data->titulo), array('bliki/'.$data->path));
    ?></h2>

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_creado')); ?>:</b>
	<?php echo $this->formato_fecha($data->fecha_creado); ?> (<?php echo gettype($data->fecha_creado).' '.$data->fecha_creado; ?>)
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fecha_modificado')); ?>:</b>
	<?php echo $this->formato_fecha($data->fecha_modificado); ?> (<?php echo gettype($data->fecha_modificado).' '.$data->fecha_modificado; ?>)
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('resumen')); ?>:</b>
	<?php echo CHtml::encode($data->resumen); ?>
	<br />




<?php

    }

//var_dump($dataProvider->pagination->getPageCount());
//$paginado = new Html_Paginado($dataProvider->pagination->getPageCount(), $dataProvider->pagination->pageVar, null);
//echo $paginado;


    Yii::import('ext.MyLinkPager');
    $this->widget('MyLinkPager', array(
        'pages' => $dataProvider->pagination,
        'header' => '',
    ));
}
