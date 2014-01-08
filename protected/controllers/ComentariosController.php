<?php

class ComentariosController extends Controller {

	/**
	 * @return array action filters
	 */
	public function filters() {
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}
    
    /**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() {
		return array(
//			array('allow',  // allow all users to perform 'index' and 'view' actions
//				'actions' => array(
//                    'index',
//                    'post',
//                    'comentar',
//                ),
//				'users'=>array('*'),
//			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions' => array(
                    'editar',
                    'status_comentario',
                ),
				'users'=>array('@'),
			),
//    //			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//    //				'actions'=>array('admin','delete'),
//    //				'users'=>array('admin'),
//    //			),
			array('deny',  // deny all users
				'actions' => array(
                    'editar',
                    'status_comentario',
                ),
				'users'=>array('*'),
			),
		);
	}
    
    public function actionIndex($pagina = 1, $id = null) {
        $this->breadcrumbs = array(
            'Bliki' => array('bliki/'),
            'Comentarios',
        );

        $couch_view = 'comments/_view/created';
        if(!Yii::app()->user->isGuest) {
            $couch_view .= '_admin';
        }
        $list = Comment::model()->find($couch_view, array('descending' => 'true'));
var_dump($list);
exit;
        if(array_key_exists('formato', $this->actionParams) && $this->actionParams['formato'] == 'rss') {
            return $this->rss($list);
        }
        $this->render('index', array(
            'comments' => $list,
        ));
	}
    
    protected function rss($list) {
        $dominio = $_SERVER['SERVER_NAME'];//'bng5.net';//
        header('Content-Type: application/xml; charset=utf-8');
        $xmlstr = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
            '<rss version="2.0">'.//"\n".
            '  <channel>'.//"\n".
            '    <title>Comentarios - Bng5.net</title>'.//"\n".
            '    <link>http://'.$dominio.'/comentarios</link>'.//"\n".
            '    <language>es-uy</language>'.//"\n".
            '    <description></description>'.//"\n".
            '  </channel>'.//"\n".
            '</rss>';

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($xmlstr);
        $doc->formatOutput = true;
        
        $channel = $doc->getElementsByTagName('channel')->item(0);
//        $doc->firstChild->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
//        
        foreach($list AS $post) {
//
            $item = $channel->appendChild($doc->createElement('item'));
            $title = $item->appendChild($doc->createElement('title'));
            $title->appendChild($doc->createTextNode($post->author));
            $link = $item->appendChild($doc->createElement('link'));
            $link->appendChild($doc->createTextNode('http://'.$dominio.'/bliki/'.$post->post_id.'#comentario-'.$post->_id));
//            
            $guid = $item->appendChild($doc->createElement('guid'));
            $guid->appendChild($doc->createTextNode("http://{$dominio}/comentarios/{$post->_id}"));
            $guid->setAttribute('isPermaLink', 'true');
//            
            $item->appendChild($doc->createElement('pubDate', date("r", $post->created_at)));
            $description = $item->appendChild($doc->createElement('description'));
            $description->appendChild($doc->createTextNode($post->html));
//            $content = $item->appendChild($doc->createElementNS('http://purl.org/rss/1.0/modules/content/', 'content:encoded'));
//            $content->appendChild($doc->createCDATASection($post->post));
        }
        echo $doc->saveXML();
    }

    public function actionComment($item) {
        $model = Comment::model()->get($item);
        if($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            
        }
        
        $this->render('comment',array(
            'comment' => $model,
        ));
    }
}
