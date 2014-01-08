<?php

/**
 *
 * @author pablo
 */
class FeedAtom implements Iterator {
    
    protected $type;
    protected $xml;
    
    private $k;
    
    public function __construct(SimpleXMLElement $sxml) {
        $this->type = Feed::TYPE_ATOM;
        $this->xml = $sxml;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function getTitle() {
        return $this->xml->title;
    }
    
    public function getDescription() {
        return (string) $this->xml->subtitle;
    }

    public function getLink() {
        foreach($this->xml->link AS $link) {
            $attributes = $link->attributes();
            if($attributes['rel'] == 'self') {
                continue;
            }
            return $attributes['href'];
        }
    }

    public function getLastBuildDate() {
        return new DateTime($this->xml->updated);
    }
    
//    public function getIterator() {
//        return new RssItems($this->xml->channel->item);
//    }
    
    
    
    public function rewind() {
        $this->k = 0;
    }

    public function valid() {
        return isset($this->xml->entry[$this->k]);
    }
    
    public function current() {
        return new FeedAtomItem($this->xml->entry[$this->k]);
    }

    public function key() {
        return $this->k;
    }

    public function next() {
        ++$this->k;
    }


}

