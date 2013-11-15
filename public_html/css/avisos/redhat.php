<?php

/**
 * https://access.redhat.com/site/documentation/es-ES/Red_Hat_Enterprise_Linux/6/html/Security_Guide/pref-Security_Guide-Preface.html#id2746863
 */
?>
<?php echo $classAvisos ?> {
position: relative;
    background-color: #EEEEEC;
    border: 1px solid #AAAAAA;
    color: #000000;
    margin: 12px 0 1em 0;
    padding: 0;
    page-break-inside: avoid;
}
<?php echo $classAvisos ?> h3 {
    color: #EEEEEC;
    /*display: inline;*/
    font-size: 17px;
    height: 1.4em;
    line-height: 1.4em;
    margin: 0 0 6px 0;
    padding: 0 0 0 62px;
/*    clear: both;
    font-size: 1px;
    line-height: 1px;
    margin: -40px 0 0;
    padding: 0 0 0 58px;
*/
}
<?php echo $classAvisos ?> p {
    padding: 13px 13px 13px 26px;
    margin: 0;
}

div.<?php echo $classImportant ?> {
}
div.<?php echo $classImportant ?> h3 {
    background: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/yellow.png") repeat-x scroll right top #A6710F;
}
div.<?php echo $classImportant ?>:before {
    content: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/important.png");
    padding-left: 5px;
    position: absolute;
    top: -12px;
    left: 1px;
}

div.<?php echo $classNote ?> {
}
div.<?php echo $classNote ?> h3 {
    background: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/green.png") repeat-x scroll right top #597800;
}
div.<?php echo $classNote ?>:before {
    content: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/note.png");
    padding-left: 5px;
    position: absolute;
    top: -12px;
    left: 1px;
}

div.<?php echo $classWarning ?> {
}
div.<?php echo $classWarning ?> h3 {
    background: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/red.png") repeat-x scroll left top #590000;
}
div.<?php echo $classWarning ?>:before {
    content: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/warning.png");
    padding-left: 5px;
    position: absolute;
    top: -12px;
    left: 1px;
}

div.<?php echo $classCaution ?> h3 {
    background: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/red.png") repeat-x scroll left top #590000;
}

div.<?php echo $classTip ?> h3 {
    background: url("https://access.redhat.com/site/documentation/resources/docs/common/es-ES/images/green.png") repeat-x scroll right top #597800;
}
