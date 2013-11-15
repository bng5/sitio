<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RssItems
 *
 * @author pablo
 */
class FeedAtomItem {
    
    protected $item;
    
    
// comments = "http://picandocodigo.net/2013/entradas-rubyconf-uruguay-2013/#comments"
    
    
/**
 * 
 * $guid
 * $pubDate
 * $category
 * $title
 * $description
 * $link
 * $author
 */
    
    public function __construct($item) {
        $this->item = $item;
    }

    public function getTitle() {
        return $this->item->title;
    }
    
    public function getLink() {
        $link = $this->item->link->attributes();
        return $link['href'];
    }
    
    public function getDescription() {
        return null;
        return $this->item->description;
    }

    public function getPubDate() {
        return new DateTime($this->item->updated);
    }
    
    public function getGuid() {
        return $this->item->id;
    }
    
    public function getIsPermalink() {
        return false;
    }
    
    public function getContent() {
        
    }
}
