<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<div id="main" class="column right">
    <div id="sidebar-left" class="sidebar">
        <ul>
            <li><a href="/multilevelpiechart">Intro</a></li>
            <li><a href="/multilevelpiechart/examples">Ejemplos</a></li>
            <li><a href="/multilevelpiechart/doc">Referencia</a></li>
            <li><a href="/multilevelpiechart/demo">Demo</a></li>
        </ul>
    </div>
    <div id="content">
        <!-- h1>Multi-level pie chart</h1 -->
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>