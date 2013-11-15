<?php

class FeedController extends Controller {

	public function actionIndex() {
		$this->redirect('/feed/rss');
	}

	public function actionRss() {
        $dominio = 'bng5.net';//$_SERVER['SERVER_NAME'];
        header('Content-Type: application/xml; charset=utf-8');
        $xmlstr = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
            '<rss version="2.0">'.//"\n".
            '  <channel>'.//"\n".
            '    <title>Bng5.net</title>'.//"\n".
            '    <link>http://'.$dominio.'/</link>'.//"\n".
            '    <language>es-uy</language>'.//"\n".
            '    <description></description>'.//"\n".
            '  </channel>'.//"\n".
            '</rss>';

        
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($xmlstr);
        $doc->formatOutput = true;
        
        $channel = $doc->getElementsByTagName('channel')->item(0);
        
        $dataProvider = new CActiveDataProvider('Post', array(
            'criteria'=>array(
                'condition' => 'estado = 1',
//                'order'=>'create_time DESC',
//                'with'=>array('author'),
            ),
            'pagination'=>array(
                'pageSize'=>6,
            ),
        ));
        
        $doc->firstChild->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
        
        foreach($dataProvider->data AS $post) {

            $item = $channel->appendChild($doc->createElement('item'));
            $title = $item->appendChild($doc->createElement('title'));
            $title->appendChild($doc->createTextNode($post->titulo));
            $link = $item->appendChild($doc->createElement('link'));
            $link->appendChild($doc->createTextNode('http://'.$dominio.'/bliki/'.$post->path));
            
            $guid = $item->appendChild($doc->createElement('guid'));
            $guid->appendChild($doc->createTextNode('http://'.$dominio.'/bliki/?id='.$post->id));
            $guid->setAttribute('isPermaLink', 'true');
            
            $item->appendChild($doc->createElement('pubDate', date("r", $post->fecha_creado)));
            $description = $item->appendChild($doc->createElement('description'));
            $description->appendChild($doc->createTextNode($post->resumen));
            $content = $item->appendChild($doc->createElementNS('http://purl.org/rss/1.0/modules/content/', 'content:encoded'));
            $content->appendChild($doc->createCDATASection($post->post));
        }
        echo $doc->saveXML();

        /*
            <item>
              <title></title>
              <link>http://bng5.net/bliki/...</link>
              <pubDate><?php date("r", $fila['creado']) ?></pubDate>
              <description><![CDATA[]]></description>
            </item>
        */
	}
}