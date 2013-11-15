<?php

class MultilevelpiechartController extends Controller {

    public $layout = '//multilevelpiechart/layout';
            
//	public function __construct($path = null) {
//        parent::__construct($path);
//    }

    public function __construct($path = null) {
        parent::__construct($path);
        array_unshift($this->pageTitle, 'Multi-level pie chart');
    }

    public function actionIndex() {
        $this->breadcrumbs[] = 'Multi-level pie chart';
		$this->render('index');
	}

    public function actionChangelog() {
		$this->render('index');
	}
    public function actionDemo() {
        //$this->pageTitle=Yii::app()->name . ' - Contact Us';
        $this->breadcrumbs = array(
            'Multi-level Pie Chart' => '/multilevelpiechart',
            'Demo',
        );
		$this->render('demo');
	}
    
    public function actionDoc() {

        $this->menu = array(
            array('label'=>'MultiLevelPieChart', 'url'=>array('multilevelpiechart/doc/es/MultiLevelPieChart')),
            array('label'=>'MultiLevelPieChartSector', 'url'=>array('multilevelpiechart/doc/es/MultiLevelPieChartSector')),
        );
        
        preg_match('/^\/multilevelpiechart\/doc(\/(([a-z]{2})(\/([\w]+)?)?)?)?$/', $_SERVER['REQUEST_URI'], $matches);

        $lang = $matches[3] ? $matches[3] : 'es';
        $doc = $matches[5] ? $matches[5] : 'index';

		$this->render("doc/{$lang}/{$doc}");
	}

    public function actionExamples() {
        
        $this->breadcrumbs['Multi-level pie chart'] = '/multilevelpiechart';
        $this->breadcrumbs['Ejemplos'] = '/multilevelpiechart/examples';
        
        $file = $this->actionParams['id'] ? $this->actionParams['id'] : 'index';
        
        $examples = array(
            '01_basic'
        );
        
        // /{$file}
		$this->render("ejemplos", array(
            'examples' => $examples,
            'file' => $file,
        ));
        
    }
}