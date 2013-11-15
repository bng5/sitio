
<h1>Herramientas</h1>

<?php

var_dump($tools);

//echo '
//    <ul>';
foreach($tools AS $k => $v) {
    echo '
    <h2><a href="/tools/'.$k.'">'.$v[0].'</a></h2>
<p>Info: <a href="/tools/info/'.$k.'">'.$v[0].'</a></p>        
';
}
//echo '
//    </ul>';

?>
