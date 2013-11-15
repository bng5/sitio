<h1>Admin</h1>

<?php

var_dump(Yii::app()->user,
    Yii::app()->user->id,
    Yii::app()->user->name,
    Yii::app()->user->isGuest,
    Yii::app()->user->returnUrl
);

?>
