<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="es-uy" lang="es-uy">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <title><?php
$this->pageTitle = (array) $this->pageTitle;
    echo CHtml::encode(implode(' - ', $this->pageTitle)); ?></title>
    <link href="/css/sitio.css" rel="stylesheet" type="text/css" />
	<meta name="language" content="es-uy" />
    <link rel="home" href="http://bng5.net/" />
    <link rel="author" title="Pablo Bangueses" href="http://pablo.bng5.net/" />
    <link rel="alternate" type="application/rss+xml" title="RSS" href="http://bng5.net/feed/rss" charset="utf-8" />
    <script type="text/javascript" src="/js/sitio.js"></script>
    <?php
    
if(!Yii::app()->user->isGuest) {
//    Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/jquery-1.9.1.js');
    echo '
    <link rel="stylesheet" type="text/css" href="/css/admin.css" />
    <script type="text/javascript" src="/js/admin.js"></script>';
}

    // echo Yii::app()->request->baseUrl."/css/screen.css";
/*
  <!-- Meta -->
  <!--
    <meta name="Author" content="Pablo Bangueses" />  
    <meta name="Keywords" lang="es" content="keywords sitio" />
	<meta name="Keywords" lang="en" content="keywords site" />
	<meta name="Description" lang="es" content="Una breve descripción." />
	<meta name="Description" lang="ca" content="Lo mismo, en catalán" />
  -->

  
  <!-- Links -->
  <!-- Alternate
    <link rel="alternate" type="text/html" href="http://ecochimeneas.com" title="Este sitio en español" hreflang="es" />
    <link rel="alternate" type="text/html" href="http://ecochimeneas.es" hreflang="es" />
    <link rel="alternate" type="text/html" href="http://flameofskagen.com" hreflang="en" />
    
    <link rel="start" href="/reviews/" title="Submission Reviews - Editor's Comments">
    <link rel="index" href="http://www.example.com/link-reference" />
    <link rel="contents" href="http://www.example.com/link-reference" />
    <link rel="search" type="application/opensearchdescription+xml" href="/w/opensearch_desc.php" title="Wikipedia (es)" />
    <link rel="glossary" href="http://www.example.com/link-reference" />
    <link rel="help" href="http://www.example.com/link-reference" />
    <link rel="first" href="http://www.example.com/link-reference" />
    <link rel="next" href="http://www.example.com/link-reference" />
    <link rel="prev" href="http://www.example.com/link-reference" />
    <link rel="last" href="http://www.example.com/link-reference" />
    <link rel="up" href="http://www.example.com/link-reference" />
    <link rel="copyright" href="http://www.example.com/link-reference" />
    <link rel="author" href="http://www.example.com/link-reference" />
  -->
 */
    
$breadcrumbs = (isset($this->breadcrumbs) && count($this->breadcrumbs));

?>

</head>
<body>
<div id="header"<?php echo $breadcrumbs ? ' class="breadcrums"' : ''; ?>>
    <div class="content">
        <em><a href="http://bng5.net/" rel="home">Bng5</a></em>
        <?php

        //if(!Yii::app()->user->isGuest) {
//var_dump(Yii::app()->user->isGuest);
            //echo '<div class="usuario">'.Yii::app()->user->name.' '.CHtml::link("Salir", array('site/logout'))."</div>\n";
        //}

//<!-- gcse:searchbox-only></gcse:searchbox-only -->
        ?>
        <div id="buscador">
            <form action="http://bng5.net/busqueda" method="get" id="busqueda">
                <fieldset title="Buscar en este sitio">
                    <legend>Buscar en este sitio</legend>
                    <input type="text" name="q" />
                    <input type="submit" value="Buscar" />
                </fieldset>
            </form>
        </div>
    </div>
</div>



		<?php
        /*
         $this->widget('zii.widgets.CMenu',array(
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'About', 'url'=>array('/site/page', 'view'=>'about')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
				array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
			),
		));
         */

    if($breadcrumbs) {
        $this->widget('zii.widgets.CBreadcrumbs', array(
			'links' => $this->breadcrumbs,
		));
        // <!-- breadcrumbs -->
    }
    
    echo $content;
    
?>

<div id="footer">
    <div class="content">
<!--        <div class="menu">
            <ul>
                <li><a href="/mapa">Mapa del sitio</a></li>
            </ul>
        </div>-->
        <div class="botones">
<?php
//        <a href="/acerca_de_este_sitio/" title="Acerca de este sitio">Acerca de este sitio</a>
//        <a  href="/feed.php" title="Bng5.net - RSS feed"><img src="http://bng5.net/lib/tpl/bliki/images/button-rss.png" width="80" height="15" alt="RSS feed" /></a>
//        <a  href="http://creativecommons.org/licenses/by-sa/3.0/" rel="license" title="CC Attribution-Share Alike 3.0 Unported"><img src="http://bng5.net/lib/images/license/button/cc-by-sa.png" width="80" height="15" alt="" /></a>
?>
        
            <a  href="http://validator.w3.org/check/referer" title="Valid XHTML 1.1">Valid XHTML 1.1</a> - 
            <a  href="/sitio/powered_by">Powered by&#8230;</a>
<?php
//            <!--<a  href="http://www.php.net" title="Powered by PHP"><img src="/img/button-php" width="80" height="15" alt="Powered by PHP" /></a>-->
//      <!-- a  href="http://jigsaw.w3.org/css-validator/check/referer?profile=css3" title="Valid CSS"><img src="/lib/tpl/bliki/images/button-css.png" width="80" height="15" alt="Valid CSS" /></a -->
//        <a  href="http://microformats.org/" title="Microformatted for your pleasure"><img src="http://bng5.net/lib/tpl/bliki/images/button-mf.png" width="80" height="15" alt="Microformatted for your pleasure" /></a>
?>
        </div>
    </div>
</div>
</body>
</html>