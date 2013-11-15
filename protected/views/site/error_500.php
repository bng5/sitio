<?php

/**
 * @param int $code Ej: 500
 * @param string $type CHttpException
 * @param int $errorCode Ej: 1
 * @param string $message Ej: No fue posible conectar con la base de datos.
 * @param string $file Ej: /home/pablo/public_html/bng5.net/protected/components/Couchdb.php
 * @param int $line Número de línea del archivo $file
 * @param string $trace 
 * @param array $traces 
 */
?>

Error 500

<?php

echo "<p>{$message}</p>\n";

echo '<pre>';
print_r($error);
echo '</pre>';

echo '<pre>';
var_dump($this);
echo '</pre>';

?>
