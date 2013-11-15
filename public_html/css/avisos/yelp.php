
<?php echo $classAvisos ?> {
    font-family:sans-serif;
    margin: 1em;
    border-style: solid;
    border-width: 1px;
    padding-left:70px;
    background-position: 10px 10px;
    background-repeat: no-repeat;
}

div.<?php echo $classCaution ?> {
    background-image: url("../img/avisos/yelp/caution-<?php echo $tam ?>.png");
    background-color: #FFFFCC;
    border-color:#333333;
    color: #333333;
}

div.<?php echo $classImportant ?> {
    background-image: url("../img/avisos/yelp/important-<?php echo $tam ?>.png");
    background-color:#FFEEEE;
    color:#6C0000;
    border-color:#660000;
}

div.<?php echo $classNote ?> {
    background-image: url("../img/avisos/yelp/note-<?php echo $tam ?>.png");
    border-color:#FFC30E;
    background-color: #FFFBB8;
}

div.<?php echo $classTip ?> {
    background-color: #EEEEFF;
    background-image: url("../img/avisos/yelp/tip-<?php echo $tam ?>.png");
    border-color:#0000CC;
    color: #000066;

}

div.<?php echo $classWarning ?> {
    background-color:#FFEEEE;
    background-image: url("../img/avisos/yelp/warning-<?php echo $tam ?>.png");
    color:#660000;
    border-color:#660000;
}
