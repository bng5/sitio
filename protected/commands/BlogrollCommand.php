<?php

if (!defined('STDIN')) {
    define('STDIN', fopen('php://stdin', 'r'));
}
if (!defined('STDOUT')) {
    define('STDOUT', fopen('php://stdout', 'w'));
}
if (!defined('STDERR')) {
    define('STDERR', fopen('php://stderr', 'w'));
}

class BlogrollCommand extends CConsoleCommand {

    public function actionIndex() {
        $feeds = Feed::model()->find('enabled');
        var_dump($feeds);
    }
    
    public function actionUpdate() {
        
        fwrite(STDOUT, __METHOD__.' '.date(DATE_ATOM)."\n");
        Yii::import('application.components.http.client');
        
        $client = new Http_Client('Bng5Blogroll (http://bng5.net/blogroll)', array(
            'accept' => 'Accept: application/rss+xml,application/atom+xml,application/xml;q=0.9,text/xml;q=0.8',));
        
        $feeds = Feed::model()->find('feeds/_view/enabled');
        foreach($feeds as $feed) {

//            $feed = Feed::model()->setAttributes($feed->value, false);
            fwrite(STDOUT, "\n");
            echo "{$feed->url}\n";
            $headers = array();
            if($feed->HttpLastModified) {
                $headers['If-Modified-Since'] = 'If-Modified-Since: '.date('r', $feed->HttpLastModified);
            }
            if($feed->HttpETag) {
                $headers['If-None-Match'] = "If-None-Match: {$feed->HttpETag}";
            }

            $response = $client->get($feed->url, $headers);

            fwrite(STDOUT, "  Cabeceras Petición:\n");
            foreach($headers AS $k => $v) {
                fwrite(STDOUT, "    ".str_pad("{$k}:", 26)." {$v}\n");
            }

            fwrite(STDOUT, "  Cabeceras Respuesta:\n");
            fwrite(STDOUT, "    HTTP Status:               {$response->status}\n");
            foreach($response->getAllHeaders() AS $k => $v) {
                fwrite(STDOUT, "    ".str_pad("{$k}:", 26)." {$v}\n");
            }
            
            if($response->status == 304) {
                $feed->lastRequest = time();
                $feed->save();
                continue;
            }
            if($httpLastModified = $response->getHeader('last-modified')) {
                $httpLastModified = new DateTime($httpLastModified);
                $feed->HttpLastModified = $httpLastModified->format('U');
            }
            if($httpETag = $response->getHeader('etag')) {
                $feed->HttpETag = $httpETag;
            }
            $concreteFeed = Feed::getFeedType($response);
            if(!$concreteFeed) {
                fwrite(STDERR, "No se pudo obtener una representación XML.\n");
                continue;
            }
            
            switch($concreteFeed->getType()) {
                case Feed::TYPE_RSS:
                    fwrite(STDOUT, "Tipo: RSS\n");
                    break;
                case Feed::TYPE_ATOM:
                    fwrite(STDOUT, "Tipo: Atom\n");
                    break;
            }
            
            $lastBuildDate = $concreteFeed->getLastBuildDate()->format('U');
            
            if($feed->lastBuildDate == $lastBuildDate) {
                fwrite(STDOUT, "Coincide lastBuildDate. Continua.\n");
                continue;
            }
            $feed->lastBuildDate = $concreteFeed->getLastBuildDate()->format('U');
            
            $feed->type = $concreteFeed->getType();
            $feed->title = $concreteFeed->getTitle();
            $feed->link = $concreteFeed->getLink();
            $feed->lastRequest = time();
            $feed->description = $concreteFeed->getDescription();
//            $feed->save();
            
            foreach($concreteFeed AS $item) {
//var_dump($item);
                $guid = (string) $item->getGuid();
                $feedItem = null;
                try {
                    $feedItem = FeedItem::model()->get(urlencode($guid));
                } catch(CouchdbException $exc) {
                    echo $exc->getCode()."\n";
                }

                if($feedItem) {
                    fwrite(STDOUT, "Ya existe GUID. Continua.\n");
                    continue;
                }
                
                $feedItem = new FeedItem;
                $feedItem->_id = urlencode($guid);
                $feedItem->feed_id = $feed->_id;
                $feedItem->title = (string) $item->getTitle();
                $feedItem->link = (string) $item->getLink();
                $feedItem->description = $item->getDescription();
                $feedItem->pubDate = (int) $item->getPubDate()->format('U');
                $feedItem->guid = $guid;
                $feedItem->guid_isPermaLink = $item->getIsPermalink();
//                $feedItem->content_encoded = $item->getContent();
var_dump($feedItem);
                $feedItem->save();
            }
            $this->borrarAntiguos($feed->_id);
        }
        fwrite(STDOUT, "\n----\n\n");
    }
    
    protected function borrarAntiguos($feed_id) {
        
        $antiguos = FeedItem::model()->find('items/_view/by_feed', array(
            'descending' => 'true',
            'startkey' => "[\"{$feed_id}\", 2147483647]",
            'endkey' => "[\"{$feed_id}\", 0]",
            'skip' => 10,
        ));
        foreach($antiguos AS $item) {
            $item->delete();
        }
    }
}
