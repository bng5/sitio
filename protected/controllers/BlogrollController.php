<?php

class BlogrollController extends Controller {

    public function actionIndex($formato = 'xhtml', $feed = null) {
        
        array_unshift($this->pageTitle, 'Blogroll');
        $mensaje = false;
        if($feed) {
            $feed_identificador = $feed;
            $feed = Feed::model()->find('link RLIKE :link', array(
                ':link' => sprintf('^https?:\/\/%s/?$', $feed),
            ));
            if(!$feed) {
                header("Pragma: no-cache", true, 404);
                $mensaje = 'No se encontró el feed <em>'.$feed_identificador.'</em>.';
                array_unshift($this->pageTitle, 'Error 404');
            }
        }

        $feeds = Feed::model()->public()->findAll();
        $posts = FeedItem::model()->newest($feed)->findAll();

        if($formato == 'opml') {
            $this->opml($feeds);
            return;
        }
        elseif($formato == 'rss') {
            $this->rss($posts);
            return;
        }
        else {
            if($feed) {
                array_unshift($this->pageTitle, $feed->title);
                $this->breadcrumbs = array(
                    'Blogroll' => array('/blogroll'),
                    $feed->title,
                );
            }
            else {
                $this->breadcrumbs = array(
                    'Blogroll',
                );
            }
        }

        $this->render('index', array(
            'feed' => $feed,
            'feeds' => $feeds,
            'posts' => $posts,
            'mensaje' => $mensaje,
        ));
	}
    
    protected function opml($feeds) {
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;
        $doc->appendChild($doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="/xsl/opml.xsl"'));
        $root = $doc->appendChild($doc->createElement('opml'));
        $root->setAttribute('version', '1.0');
        $head = $root->appendChild($doc->createElement('head'));
        $head->appendChild($doc->createElement('title', 'Blogroll en bng5.net'));
        $body = $root->appendChild($doc->createElement('body'));

        foreach($feeds AS $row) {
            $outline = $body->appendChild($doc->createElement('outline'));
            $outline->setAttribute('title', $row->title);
            $outline->setAttribute('text', $row->description);
            $outline->setAttribute('type', ($row->type == Feed::TYPE_RSS ? 'rss' : 'pie'));
            $outline->setAttribute('xmlUrl', $row->url);
            $outline->setAttribute('htmlUrl', $row->link);
        }
        header("Content-Type: application/xml; charset=UTF-8");
        echo $doc->saveXML();
    }

    protected function rss($items) {
        $dominio = $_SERVER['HTTP_HOST'];
        header('Content-Type: application/xml; charset=utf-8');

        $xmlstr = '<?xml version="1.0" encoding="UTF-8"?>'."\n".
            '<rss version="2.0">'.
            '  <channel>'.
            '    <title>Blogroll en Bng5.net</title>'.
            '    <link>http://'.$dominio.'/</link>'.
            '    <language>es-uy</language>'.
            '    <description></description>'.
            '  </channel>'.
            '</rss>';

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->loadXML($xmlstr);
        $doc->formatOutput = true;
        
        $channel = $doc->getElementsByTagName('channel')->item(0);        
        
        foreach($items AS $post) {

            $item = $channel->appendChild($doc->createElement('item'));
            $title = $item->appendChild($doc->createElement('title'));
            $title->appendChild($doc->createTextNode($post->title));
            
            $author = $item->appendChild($doc->createElement('author'));
            $author->appendChild($doc->createTextNode($post->feed->title));
            
            $link = $item->appendChild($doc->createElement('link'));
            $link->appendChild($doc->createTextNode($post->link));
            
            $guid = $item->appendChild($doc->createElement('guid'));
            $guid->appendChild($doc->createTextNode($post->guid));
            $guid->setAttribute('isPermaLink', ($post->guid_isPermaLink ? 'true' : 'false'));
            
            $item->appendChild($doc->createElement('pubDate', date("r", $post->pubDate)));
            $description = $item->appendChild($doc->createElement('description'));
            $description->appendChild($doc->createTextNode($post->description));

        }
        echo $doc->saveXML();
	}
}