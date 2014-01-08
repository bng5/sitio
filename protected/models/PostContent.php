<?php

/**
 * 
 * @property string $texto
 */
class PostContent extends CModel {

    const NS_POST = 'http://bng5.net/ns/post';
    const NS_XML = 'http://www.w3.org/XML/1998/namespace';
    const NS_XHTML = 'http://www.w3.org/1999/xhtml';
    const NS_DOCBOOK = 'http://docbook.org/ns/docbook';
    
    public $id;
    public $sourceText;
    public $source;
    public $path;
    public $titulo;
    public $texto;
    public $resumen;
    public $toc_habilitado = true;
    public $comentarios_habilitados = true;
    public $fecha_creado;
    public $fecha_modificado;
    public $tags = array();
    public $keywords = array();
    public $changelog = array();
    public $rev;

    public $toc = array();
    public $tokens = array();
    public $doc;
    
    private $_dbModel;
    
    public function attributeNames() {
        return array(
            'titulo',
            'resumen',
            'texto',
            'tags',
            'source',
            'toc_habilitado',
            'comentarios_habilitados',
        );
    }
    
    public function rules() {
        return array(
            array('path, titulo, texto, toc_habilitado, fecha_creado, fecha_modificado, comentarios_habilitados, tags', 'safe'),
//            array('id, version, description', 'safe'),
        );
    }
    
    public static function load($id, $rev) {
        $content = new self;
        if(!$id || !is_numeric($id)) {
            throw new CException('Ocurrió un error al cargar el archivo fuente.');
            $this->addError('post', 'No se ha indicado un ID válido.');
            return false;
        }
        
        $source = "../data/post/{$id}.{$rev}.xml";
        
        $xml = new XMLReader();
        if(!(@$xml->open($source))) {
            throw new CException('Ocurrió un error al cargar el archivo fuente '.$source);
        }
        while($xml->read()) {
            if($xml->nodeType == XMLReader::ELEMENT && $xml->namespaceURI == self::NS_POST) {
                switch($xml->localName) {
                    case 'post':
                        $content->parseAttributes($xml);
                        break;
                    case 'title':
                        $content->titulo = $xml->readInnerXML();
                        break;
                    case 'tag':
                        array_push($content->tags, $xml->readInnerXML());
                        break;
                    case 'keyword':
                        array_push($content->keywords, $xml->readInnerXML());
                        break;
                    case 'changelog':
                        array_push($content->changelog, $xml->readInnerXML());
                        break;
                    case 'summary':
                        $content->resumen = $xml->readInnerXML();
                        break;
                    case 'content':
                        $content->sourceText = preg_replace('/ xmlns(:[a-z]+)?="[^"]+"/', '', $xml->readInnerXML());
                        $content->parseContent($xml);
                        break;
                }
            }
        }
//var_dump($content->tokens);
        return $content;
    }
    
    public function parseAttributes(XMLReader $xml) {
        $this->toc_habilitado = !($xml->getAttribute('notoc') == 'true');
        $this->comentarios_habilitados = !($xml->getAttribute('nocomments') == 'true');
    }
    
    public function parseContent(XMLReader $z) {
        $types = array(
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
        $depth = $z->depth;
        $z->read();
        do {
            if($z->nodeType == XMLReader::ELEMENT) {
                if($z->namespaceURI == self::NS_XHTML) {
                    if(preg_match('/h(\d)/', $z->localName, $matches)) {
                        $level = $matches[1];
                        $value = $z->readInnerXML();
                        $name = $value;
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
                        if($acronym = Acronym::model()->find('acronym = :acronym', array(':acronym' => $z->readString()))) {
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
                    $types[$z->nodeType],
                    $z->name,
                ));
            }
            
//            array_push($this->tokens, $token);
            
            
//echo "
//nodeType:        {$types[$z->nodeType]}
//namespaceURI:    {$z->namespaceURI}
//localName:       {$z->localName}
//value:           '".htmlspecialchars($z->value)."'
//    
//attributeCount:      {$z->attributeCount}
//hasAttributes:       {$z->hasAttributes}
//hasValue:            {$z->hasValue}
//isDefault:           {$z->isDefault}
//isEmptyElement:      {$z->isEmptyElement}
//name:                {$z->name}
//nodeType:            {$z->nodeType}
//prefix:              {$z->prefix}
//
//----------------------------------------------------------------
//";


            $z->read();

        }while($z->depth > $depth);
//print_r($this->tokens);
//print_r($this->toc);
//echo "
//</pre>
//";
    }
    
    public function parseContent_(XMLReader $z) {

        $depth = $z->depth;
//        if(!$this->id) {
//            throw new Exception('No ID');
//            return false;
//        }        

        
//        while($z->read() && $z->localName != 'content');
        
        $array = array(
            'toc' =>  array(),
        );
        
$li = null;

        $es_titulo = false;
        
        
        
        $h = array();
        $this->doc = new DOMDocument('1.0', 'utf-8');
        $toc = $this->doc->appendChild($this->doc->createElement('ul'));
        $parent = $toc;
        array_push($h, $parent);
        $prev_level = null;
        $cuenta = array(0);
        
        
echo "
<pre>
";
        do {
            $z->read();
var_dump(
        $z->nodeType, 
        $z->localName,
        $z->value);
echo "

############################################

";
continue;
            if($z->nodeType == XMLReader::ELEMENT || $z->nodeType == XMLReader::END_ELEMENT) {
//                if($z->localName == 'content') {
//                    continue;
//                }
                $start = ($z->nodeType == XMLReader::ELEMENT);
                if(preg_match('/h(\d)/', $z->localName, $matches)) {
                    
                    
                    
                    
                    
                    
                    $level = (int) $matches[1];
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
                                $parent = $li->appendChild($this->doc->createElement('ul')); 
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
                    $li = $parent->appendChild($this->doc->createElement('li'));            
                    $href = $z->getAttribute('id');
                    $z->read();
                    $texto = $z->value;
                    $num = $li->appendChild($this->doc->createElement('span', implode('.', $cuenta)));
                    $num->setAttribute('class', 'num');
                    $li->appendChild($this->doc->createTextNode(' '));
                    $a = $li->appendChild($this->doc->createElement('a'));
                    $a->appendChild($this->doc->createTextNode($texto));
                    $a->setAttribute('href', "#{$href}");
                    $h[$level] = $li;
                    $prev_level = $level;
            
            
            
            
            
            
                    if($start) {
                        $es_titulo = true;
                        $titulo = '';
                    }
                    else {
                        $es_titulo = false;
                        array_push($array['toc'], $titulo);
                    }
                }
                if($start) {
                    $instruccion = 'element_start';
                }
                else {
                    $instruccion = 'element_end';
                }
                array_push($this->tokens, array(
                    $instruccion,
                    $z->localName,
                ));
            }
            elseif($z->nodeType == XMLReader::TEXT) {
                if($es_titulo) {
                    $titulo .= $z->value;
                }
                array_push($this->tokens, array(
                    'text',
                    $z->value,
                ));
            }
            


       
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            

        }while($z->depth > $depth);
echo "
</pre>
";
        
        var_dump($this->doc->saveXML());
        return;
    }
    
    public function save($runValidation = true, $attributes = null) {
//        if(parent::save($runValidation, $attributes)) {

            $id = $this->_dbModel->id;
            $rev = $this->_dbModel->rev;

            $doc = new DOMDocument('1.0', 'UTF-8');
            $doc->preserveWhiteSpace = false;
//            $doc->loadXML($xmlstr);
            $doc->formatOutput = true;
            $root = $doc->appendChild($doc->createElementNS(self::NS_POST, 'p:post'));
            $root->setAttributeNS(self::NS_XML, 'xml:lang', 'es-uy');
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns', self::NS_XHTML);
            $root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:docb', self::NS_DOCBOOK);
            $title = $doc->firstChild->appendChild($doc->createElementNS('http://bng5.net/ns/post', 'p:title'));
//            $doc->firstChild->setAttribute('id', $id);
//            $doc->firstChild->setAttribute('rev', $rev);
            if(!$this->toc_habilitado) {
                $doc->firstChild->setAttribute('notoc', 'true');
            }
            if(!$this->comentarios_habilitados) {
                $doc->firstChild->setAttribute('nocomments', 'true');
            }
            $title->appendChild($doc->createTextNode($this->titulo));
            foreach($this->tags AS $tag) {
                $tag = trim($tag);
                if(empty($tag)) {
                    continue;
                }
                $root->appendChild($doc->createElementNS(self::NS_POST, 'p:tag', $tag));
            }
            $summary = $root->appendChild($doc->createElementNS(self::NS_POST, 'p:summary'));
            $summary->appendChild($doc->createTextNode($this->resumen));
//            $content = $doc->firstChild->appendChild($doc->createElementNS('http://bng5.net/ns/post', 'p:content'));
            $fragment = $doc->createDocumentFragment();
            $fragment->appendXML('<p:content xmlns="'.self::NS_XHTML.'" xmlns:docb="'.self::NS_DOCBOOK.'" xmlns:p="'.self::NS_POST.'">'.$this->texto.'</p:content>');
            $fragment->normalize();
            $root->appendChild($fragment);

            if(!$id) {
                throw new CHttpException(500, "No hay ID asociado.");
            }
            $basename = "{$id}.{$rev}.xml";
            if(!($doc->save("../data/post/{$basename}"))) {
                throw new CHttpException(500, "No fue posible guardar el archivo data/post/{$basename}");
            }
        return true;
    }

    public function xmlToMap($xml_str) {

        if(!$this->id) {
            return false;
        }
        $z = new XMLReader;
        $z->xml($xml_str);
//        $z->read();
//        $xml_str = $z->readInnerXML();
        
        
echo "
    

################################################################################


{$xml_str}


################################################################################


";
        
//        while($z->read() && $z->localName != 'content');
        
        $array = array(
            'toc' =>  array(),
            'map' => array(),
        );
        $prev_level = 0;
        $cuenta = array();
        
$li = null;

        $es_titulo = false;
        while($z->read()) {
var_dump(
        $z->nodeType, 
        $z->localName,
        $z->value);
echo "

---------------------------------------

";
            if($z->nodeType == XMLReader::ELEMENT || $z->nodeType == XMLReader::END_ELEMENT) {
                if($z->localName == 'content') {
                    continue;
                }
                $start = ($z->nodeType == XMLReader::ELEMENT);
                if(preg_match('/h(\d)/', $z->localName, $matches)) {
                    if($start) {
                        $es_titulo = true;
                        $titulo = '';
                    }
                    else {
                        $es_titulo = false;
                        array_push($array['toc'], $titulo);
                    }
                }
                if($start) {
                    $instruccion = 'element_start';
                }
                else {
                    $instruccion = 'element_end';
                }
                array_push($array['map'], array(
                    $instruccion,
                    $z->localName,
                ));
//                if(preg_match('/h(\d)/', $z->localName, $matches)) {
////                    array_push($array['toc'], $z->value);
//                    
//                    
//            $level = (int) $matches[1];
//            if(!$prev_level) {
//                $prev_level = $level;
//            }
////            if(!array_key_exists($matches[1], $h)) {
//            switch(bccomp($level, $prev_level)) {
//                case -1:
////                    array_pop($h);
////                    $parent = array_pop($h);
//                    array_pop($cuenta);
//                    end($cuenta);
//                    break;
//                case 1:
//                    if($li) {
//                        $parent = $li->appendChild($doc->createElement('ul')); 
//                        array_push($h, $parent);
//                        $cuenta[] = 0;
//                        end($cuenta);
//                    }
//    //                else {
//    //                    $li = $parent->appendChild($doc->createElement('ol'));
//    //                }
//                    break;
//            }
//
//            $k = key($cuenta);
////            $cuenta[$k]++;
////            $li = $parent->appendChild($doc->createElement('li'));            
//            $href = $z->getAttribute('id');
//            $z->read();
//            $texto = $z->value;
////            $num = $li->appendChild($doc->createElement('span', implode('.', $cuenta)));
////            $num->setAttribute('class', 'num');
////            $li->appendChild($doc->createTextNode(' '));
////            $a = $li->appendChild($doc->createElement('a'));
////            $a->appendChild($doc->createTextNode($texto));
////            $a->setAttribute('href', "#{$href}");
////            $h[$level] = $li;
//            $prev_level = $level;
//
//                }
            }
            elseif($z->nodeType == XMLReader::TEXT) {
                if($es_titulo) {
                    $titulo .= $z->value;
                }
                array_push($array['map'], array(
                    'text',
                    $z->value,
                ));
            }
            
//            if($z->nodeType != XMLReader::ELEMENT || !preg_match('/h(\d)/', $z->localName, $matches)) {
//                continue;
//            }

        }
        var_dump($array);
        return;
    }

    public function createToc($xml_str, $doc = null) {

        if(!$this->id) {
            return false;
        }
        $z = new XMLReader;
        $z->xml($xml_str);
        
        while($z->read() && $z->localName != 'content');
        
        $h = array();
        //$toc = $doc;// ? $doc : new DOMDocument('1.0', 'utf-8');
        $toc = $doc->createElement('ul');
        $parent = $toc;
        array_push($h, $parent);
        $prev_level = null;
        $cuenta = array(0);
        
        while($z->read()) {
            if($z->nodeType != XMLReader::ELEMENT || !preg_match('/h(\d)/', $z->localName, $matches)) {
                continue;
            }
            $level = (int) $matches[1];
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
            $href = $z->getAttribute('id');
            $z->read();
            $texto = $z->value;
            $num = $li->appendChild($doc->createElement('span', implode('.', $cuenta)));
            $num->setAttribute('class', 'num');
            $li->appendChild($doc->createTextNode(' '));
            $a = $li->appendChild($doc->createElement('a'));
            $a->appendChild($doc->createTextNode($texto));
            $a->setAttribute('href', "#{$href}");
            $h[$level] = $li;
            $prev_level = $level;
        }
        return $toc;//$h[2];
    }
    
    public function setDbModel($model) {
        $this->_dbModel = $model;
        $this->id = $model->id;
        $this->rev = $model->rev;
        return $this;
    }

    public function getId() {
        return $this->_dbModel->id;
    }

    public function getIsNewRecord() {
        return $this->_dbModel->isNewRecord;
    }

    public function __toString() {
        return $this->texto;
    }
}
