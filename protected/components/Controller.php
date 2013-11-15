<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController {
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
    
	public $pageTitle = array('Bng5');
    
    
    public function formato_fecha($fecha, $formato = TRUE, $hora = TRUE) {
        $texto['meses'] = array(1 => "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
        $texto['dias'] = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
        if(empty($fecha)) {
            $form = "No especificada";
        }
        else {
            $meses = $texto['meses'];
            $dias = $texto['dias'];
            $mk_fecha = $fecha;//@mktime(0, 0, 0, mb_substr($fecha, 5, 2), mb_substr($fecha, 8, 2), mb_substr($fecha, 0, 4));
            if($formato == TRUE) {
                $form = $dias[date('w', $mk_fecha)]." ".date('j', $mk_fecha)." de ".$meses[date('n', $mk_fecha)]." de ".date('Y', $mk_fecha);
            }
            else {
                $form = mb_substr($dias[date('w', $mk_fecha)], 0, 3)." ".date('j', $mk_fecha)."-".mb_substr($meses[date('n', $mk_fecha)], 0, 3)."-".date('Y', $mk_fecha);
            }
            if($hora == TRUE && strlen($fecha) > 10) {
                $mk_fecha += mktime(substr($fecha, 11, 2)-3, substr($fecha, 14, 2), substr($fecha, 17, 2), 0, 0, 0);
                $form .= ", ".date("G:i", $mk_fecha)." hs.";
            }
        }
        return $form;
    }
}