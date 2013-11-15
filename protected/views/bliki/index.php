<?php

Yii::app()->clientScript->registerLinkTag('alternate', 'application/json', '/bliki.json');

?>

<h1>Bliki</h1>

<?php


class Html_Paginado {// implements Vista_Admin_iComponente

    public static $anteriorLabel, $siguienteLabel;
    public $ruta, $parametros, $getParam;
    private $_params, $_path;
	private $enlaces = array();

    /**
     *
     * @param int $paginas Cantidad de páginas
     * @param string $getParam Nombre del parámetro GET utilizado para indicar la página solicitada
     * @param int $max_paginas_mostrar
     */
    function __construct($paginas, $getParam = 'pagina', $max_paginas_mostrar = null) {//$ruta, $pagina, $paginas, $max_paginas_mostrar = false, $textos = array()) {

		$request = parse_url($_SERVER['REQUEST_URI']);//, PHP_URL_PATH);
		$this->ruta = $request['path'];//parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        parse_str($request['query'], $this->parametros);//$_GET;
		$this->pagina = isset($this->parametros[$getParam]) ? $this->parametros[$getParam] : 1;
		$this->paginas = $paginas;
		$this->getParam = $getParam;
		$this->max_paginas_mostrar = $max_paginas_mostrar ? $max_paginas_mostrar : $paginas;
	}

	private function _enlace($pagina, $etiqueta = false) {
		if(!$etiqueta)
			$etiqueta = $pagina;
        $this->parametros[$this->getParam] = $pagina;
		return '<a href="'.$this->ruta.'?'.http_build_query($this->parametros, '', '&amp;').'">'.$etiqueta.'</a>';
	}
	/*
	function dependenciasJs() {
		return array('otro');
	}

	function dependenciasCss() {
		return array('algo');
	}
	*/

	public function __toString() { //mostrar() {
		$ant = $this->pagina - 1;
		$pos = $this->pagina + 1;
		$this->enlaces[] = '<em>'.$this->pagina.'</em>';
		//$this->enlaces[] = '<a href="'.$this->ruta.'?'.http_build_query($this->parametros, '', '&amp;').'" class="actual">'.$this->pagina.'</a>';
		$c = 1;
		for($i = $this->max_paginas_mostrar; $i > 0; $ant--, $pos++) {
			if($ant <= 0 && $pos > $this->paginas)
				break;
			if($ant > 0) {
				array_unshift($this->enlaces, $this->_enlace($ant));
				$i--;
				$c++;
			}
			if($pos <= $this->paginas) {
				array_push($this->enlaces, $this->_enlace($pos));
				$i--;
				$c++;
			}
		}
		$anterior = '&lt; '.self::$anteriorLabel;
		$siguiente = self::$siguienteLabel.' &gt;';
		//array_unshift($this->enlaces, '<span class="antsig">'.(($this->pagina > 1) ? $this->_enlace(($this->pagina -1), $anterior) : $anterior).'</span>');
		//array_push($this->enlaces, '<span class="antsig">'.(($this->pagina < $this->paginas) ? $this->_enlace(($this->pagina + 1), $siguiente) : " ".$siguiente).'</span>');
		return '<div class="paginado"><span class="anterior'.(($this->pagina > 1) ? '">'.$this->_enlace(($this->pagina -1), $anterior) : ' inactivo">'.$anterior).'</span> '.implode(" ", $this->enlaces).' <span class="siguiente'.(($this->pagina < $this->paginas) ? '">'.$this->_enlace(($this->pagina + 1), $siguiente) : ' inactivo">'.$siguiente).'</span></div>';
		//return $retorno;
	}
}





//$this->widget('zii.widgets.CListView', array(
//	'dataProvider' => $dataProvider,
//	'itemView'=>'_view',
//));

//var_dump($dataProvider->pagination->pageCount);


echo "
<pre>
total_rows - {$model->total_rows}
offset     - {$model->offset}
</pre>
";
//object(stdClass)#35 (3) {
//  ["total_rows"]=> int(1)
//  ["offset"]=> int(0)
//  ["rows"]=> array(1) {
//    [0]=> object(stdClass)#36 (3) {
//      ["id"]=> string(21) "leer_joystick_con_php"
//      ["key"]=> int(1384479459)
//      ["value"]=> object(stdClass)#37 (1) {
//        ["title"]=> string(24) "Leer un joystick con PHP"
//      }
//    }
//  }
//}


foreach($model->rows as $row) {

    $data = $row->value;
?>

<div class="view">
    <h2><?php echo CHtml::link(CHtml::encode($data->title), array($row->id)); ?></h2>
    <?php
    echo CHtml::encode($data->summary);
/*
    <pre>
        <b><?php echo CHtml::encode($data->getAttributeLabel('fecha_creado')); ?>:</b> <?php echo $this->formato_fecha($data->fecha_creado); ?> (<?php echo gettype($data->fecha_creado).' '.$data->fecha_creado; ?>)
        <b><?php echo CHtml::encode($data->getAttributeLabel('fecha_modificado')); ?>:</b> <?php echo $this->formato_fecha($data->fecha_modificado); ?> (<?php echo gettype($data->fecha_modificado).' '.$data->fecha_modificado; ?>)</pre>

 */
?>
</div>

<?php

}
return;

//var_dump($dataProvider->pagination->getPageCount());
//$paginado = new Html_Paginado($dataProvider->pagination->getPageCount(), $dataProvider->pagination->pageVar, null);
//echo $paginado;


Yii::import('ext.MyLinkPager');
$this->widget('MyLinkPager', array(
    'pages' => $dataProvider->pagination,
    'header' => '',
));


$tags = array(
    'mínimo' => 1, 
    'máximo' => 88,
    'php' => 32,
    'xml' => 15,
    'firefox' => 13,
    'css' => 7,
);
Yii::import('ext.TagCloud');
$this->widget('TagCloud', array('tags' => $tags));



?>

    