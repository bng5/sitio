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
class RssItem {
    
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
        return $this->item->link;
    }
    
    public function getDescription() {
        return $this->item->description;
    }

    public function getPubDate() {
        return new DateTime($this->item->pubDate);
    }
    
    public function getGuid() {
        return $this->item->guid;
    }
    
    public function getIsPermalink() {
        $attrs = $this->item->guid->attributes();
        return ($attrs['isPermaLink'] == 'true') ? 1 : 0;
        
    }
    
    public function getContent() {
        
    }
}
