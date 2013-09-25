<h1>Siglas y abreviaturas</h1>

<dl>
<?php
// CSS dt:target
foreach($acronyms AS $acronym) {
    echo "
        <dt id=\"{$acronym->acronym}\"><!-- {$acronym->id} -->{$acronym->acronym}</dt>
        <dd>{$acronym->desc}</dl>";
}

?>
</dl>
