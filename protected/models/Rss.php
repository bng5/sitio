<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Rss
 *
 * @author pablo
 */
class Rss implements Iterator {
    
    protected $type;
    protected $xml;
    
    private $k;
    
    public function __construct(SimpleXMLElement $sxml) {
        $this->type = Feed::TYPE_RSS;
        $this->xml = $sxml;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getTitle() {
        return $this->xml->channel->title;
    }
    
    public function getDescription() {
        return (string) $this->xml->channel->description;
    }

    public function getLink() {
        return $this->xml->channel->link;
    }

    public function getLastBuildDate() {
        return new DateTime($this->xml->channel->lastBuildDate);
    }
    
//    public function getIterator() {
//        return new RssItems($this->xml->channel->item);
//    }
    
    
    
    public function rewind() {
        $this->k = 0;
    }

    public function valid() {
        return isset($this->xml->channel->item[$this->k]);
    }
    
    public function current() {
        return new RssItem($this->xml->channel->item[$this->k]);
    }

    public function key() {
        return $this->k;
    }

    public function next() {
        ++$this->k;
    }


}

