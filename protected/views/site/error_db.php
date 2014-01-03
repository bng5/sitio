<?php

/**
 * @param int $code Ej: 500
 * @param string $type CHttpException
 * @param int $errorCode Ej: 1
 * @param string $message Ej: GET /bng5_blikiposts/_design/tags?group=true, status: 404, error: not_found, reason: deleted
 * @param string $file Ej: /home/pablo/public_html/bng5.net/protected/components/Couchdb.php
 * @param int $line Número de línea del archivo $file
 * @param string $trace 
 * @param array $traces 
 */
?>

<h1>Error 500</h1>
<p>Problema con la base de datos.</p>
<!--

Este es el error:
<?php echo $message; ?>

<?php echo $trace; ?>

-->

<?php

//echo '<img src="https://pbs.twimg.com/media/AsLvkPpCEAIbCXG.jpg:medium" alt="" />';


/*
var_dump($this);
object(SiteController)#39 (16) {
  ["layout"]=> string(17) "//layouts/column1"
  ["menu"]=> array(0) {
  }
  ["breadcrumbs"]=> array(0) {
  }
  ["pageTitle"]=> array(1) {
    [0]=> string(4) "Bng5"
  }
  ["defaultAction"]=> string(5) "index"
  ["_id":"CController":private]=> string(4) "site"
  ["_action":"CController":private]=> object(CInlineAction)#40 (4) {
    ["_id":"CAction":private]=> string(5) "error"
    ["_controller":"CAction":private]=> *RECURSION*
    ["_e":"CComponent":private]=> NULL
    ["_m":"CComponent":private]=> NULL
  }
  ["_pageTitle":"CController":private]=> NULL
  ["_cachingStack":"CController":private]=> NULL
  ["_clips":"CController":private]=> NULL
  ["_dynamicOutput":"CController":private]=> NULL
  ["_pageStates":"CController":private]=> NULL
  ["_module":"CController":private]=> NULL
  ["_widgetStack":"CBaseController":private]=> array(0) {
  }
  ["_e":"CComponent":private]=> NULL
  ["_m":"CComponent":private]=> NULL
}
*/

?>
