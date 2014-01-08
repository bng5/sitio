<?php

/**
 * 
 *
 * @author pablo
 */
class BlikiParser {

    const NS_POST = 'http://bng5.net/ns/post';
    const NS_XML = 'http://www.w3.org/XML/1998/namespace';
    const NS_XHTML = 'http://www.w3.org/1999/xhtml';
    const NS_DOCBOOK = 'http://docbook.org/ns/docbook';
    
    protected $tokens = array();
    protected $toc = array();
    protected $types = array(
         XMLReader::NONE => 'NONE',
         XMLReader::ELEMENT => 'ELEMENT',
         XMLReader::ATTRIBUTE => 'ATTRIBUTE',
         XMLReader::TEXT => 'TEXT',
         XMLReader::CDATA => 'CDATA',
         XMLReader::ENTITY_REF => 'ENTITY_REF',
         XMLReader::ENTITY => 'ENTITY',
         XMLReader::PI => 'PI',
         XMLReader::COMMENT => 'COMMENT',
         XMLReader::DOC => 'DOC',
         XMLReader::DOC_TYPE => 'DOC_TYPE',
         XMLReader::DOC_FRAGMENT => 'DOC_FRAGMENT',
         XMLReader::NOTATION => 'NOTATION',
         XMLReader::WHITESPACE => 'WHITESPACE',
         XMLReader::SIGNIFICANT_WHITESPACE => 'SIGNIFICANT_WHITESPACE',
         XMLReader::END_ELEMENT => 'END_ELEMENT',
         XMLReader::END_ENTITY => 'END_ENTITY',
         XMLReader::XML_DECLARATION => 'XML_DECLARATION',
    );

    public function parse($content) {
        $xml = new XMLReader();
        if(!(@$xml->xml('<?xml version="1.0" encoding="UTF-8"?>
<p:content xmlns="http://www.w3.org/1999/xhtml" xml:lang="es-uy"
      xmlns:p="http://bng5.net/ns/post"
      xmlns:docb="http://docbook.org/ns/docbook">'.$content.'</p:content>'))) {
            throw new CException('Ocurrió un error al cargar el código fuente.');
        }
        
//        $xml->setParserProperty(XMLReader::VALIDATE, true);
//        var_dump($xml->isValid());

        libxml_use_internal_errors(true);
        $xml->read();
        if(!@$xml->expand()) {
            $errores = libxml_get_errors();
            throw new CException($errores[0]->message);
        }
//var_dump($dom);
//exit;
//        $read = @$xml->read();
//        if(!$read) {
//            var_dump(libxml_get_errors());
//            exit;
//        }
        $depth = $xml->depth;
        $xml->read();
        $z = $xml;
        do {
            if($z->nodeType == XMLReader::ELEMENT) {
                if($z->namespaceURI == self::NS_XHTML) {
                    if(preg_match('/h(\d)/', $z->localName, $matches)) {
                        $level = (int) $matches[1];
                        $value = $z->readInnerXML();
                        $name = $this->_toPath($value);
                        array_push($this->tokens, array(
                            'header',
                            'level' => $level,
                            'value' => $value,
                            'name' => $name,
                        ));
                        array_push($this->toc, array($level, $value, $name));
                        $z->next();
                        //$z->read();
                        //$z->read();
                        continue;
                    }
                    
                    $token = array(
                        'element_start',
                        'element' => $z->localName,
                    );
                    
                    if($z->hasAttributes) {
                        if(!array_key_exists('attrs', $token)) {
                            $token['attrs'] = array();
                        }
                        $attributes = $z->expand()->attributes;
                        foreach($attributes as $index => $attr) {
                            $token['attrs'][$index] = $attr->value;
                        }
                    }
                    if($z->localName == 'acronym' || $z->localName == 'abbr') {
                        if($acronym = Acronym::model()->get($z->readString())) {
                            if(!array_key_exists('attrs', $token)) {
                                $token['attrs'] = array();
                            }
                            $token['attrs']['title'] = $acronym->desc;
                        }
                    }
                    array_push($this->tokens, $token);
                }
                elseif($z->namespaceURI == self::NS_POST) {
                    $token = array(
                        $z->localName,
                    );
                    if($z->moveToFirstAttribute()) {
                        do {
                            $token[$z->name] = $z->value;
                        }while($z->moveToNextAttribute());
                    }
                    $z->read();
                    $token['value'] = $z->value;
                    array_push($this->tokens, $token);
                }
                elseif($z->namespaceURI == self::NS_DOCBOOK) {
                    $token = array(
                        'admonition',
                        'type' => $z->localName,
                    );
                    $domNode = $z->expand();
                    $nodeList = $domNode->getElementsByTagName('title');
                    $token['title'] = $nodeList->length ? $nodeList->item(0)->firstChild->textContent : false;
                    $token['value'] = array();
                    foreach($domNode->getElementsByTagName('para') AS $p) {
                        $token['value'][] = $p->hasChildNodes() ? $p->firstChild->textContent : '';
                    }
                    array_push($this->tokens, $token);
                    $z->next();
                }
                else {
                    array_push($this->tokens, array(
                        'text',
                        'value' => htmlspecialchars($z->readOuterXML()),
                    ));
                    $z->next();
                    continue;
                }
            }
            elseif($z->nodeType == XMLReader::END_ELEMENT) {
                if($z->namespaceURI == self::NS_XHTML) {
                    array_push($this->tokens, array(
                        'element_end',
                        'element' => $z->localName,
                    ));
                }
            }
            elseif($z->nodeType == XMLReader::TEXT || $z->nodeType == XMLReader::SIGNIFICANT_WHITESPACE) {
                array_push($this->tokens, array(
                    'text',
                    'value' => $z->value,
                ));
            }
            elseif($z->nodeType == XMLReader::CDATA) {
                array_push($this->tokens, array(
                    'text',
                    'value' => htmlspecialchars($z->value),
                ));
            }
            else {
                array_push($this->tokens, array(
                    'other',
                    $this->types[$z->nodeType],
                    $z->name,
                ));
            }
            $z->read();
        }while($z->depth > $depth);
    }
    
    public function getTokens() {
        return $this->tokens;
    }

    public function getToc() {
        return $this->toc;
    }
   
    public function getTocHtml() {
//        $z = new XMLReader;
//        $z->xml($xml_str);
//        
//        while($z->read() && $z->localName != 'content');
        
        $h = array();
        $doc = new DOMDocument('1.0', 'utf-8');
        $toc = $doc->createElement('ul');
        $toc->setAttribute('id', 'toc');
        $parent = $toc;
        array_push($h, $parent);
        $prev_level = null;
        $cuenta = array(0);
        
        foreach($this->toc AS $header) {
            $level = $header[0];
            if(!$prev_level) {
                $prev_level = $level;
            }
//            if(!array_key_exists($matches[1], $h)) {
            switch(bccomp($level, $prev_level)) {
                case -1:
                    array_pop($h);
                    $parent = array_pop($h);
                    array_pop($cuenta);
                    end($cuenta);
                    break;
                case 1:
                    if($li) {
                        $parent = $li->appendChild($doc->createElement('ul')); 
                        array_push($h, $parent);
                        $cuenta[] = 0;
                        end($cuenta);
                    }
    //                else {
    //                    $li = $parent->appendChild($doc->createElement('ol'));
    //                }
                    break;
            }

            $k = key($cuenta);
            $cuenta[$k]++;
            $li = $parent->appendChild($doc->createElement('li'));            
            $href = $header[2];
            $texto = $header[1];
            $num = $li->appendChild($doc->createElement('span', implode('.', $cuenta)));
            $num->setAttribute('class', 'num');
            $li->appendChild($doc->createTextNode(' '));
            $a = $li->appendChild($doc->createElement('a'));
            $a->appendChild($doc->createTextNode($texto));
            $a->setAttribute('href', "#{$href}");
            $h[$level] = $li;
            $prev_level = $level;
        }
        return $doc->saveXML($toc);
    }
    
    protected function _toPath($title) {
        return strtolower($title);
    }
}
