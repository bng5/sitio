
<dl>
<?php

foreach($namespaces AS $k => $v) {
    echo "
    <dt><a href=\"/ns/{$k}\">{$v[0]}</a></dt>
    <dd></dd>";
}

?>
</dl>