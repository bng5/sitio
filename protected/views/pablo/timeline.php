<?php

Yii::app()->clientScript->registerCss('timeline',"

ul.years {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
ul.years li {
    float: left;
    width: 8.98%;
}
ul.years li span {
    display: inline-block;
    font-size: 11px;
    margin-top: 8px;
    rotation: 45deg;
        -moz-transform:rotate(45deg);
        -webkit-transform:rotate(45deg);
        -o-transform:rotate(45deg);
        -ms-transform:rotate(45deg);
}

ul.hidden li {
    display: none;
}
ul.hidden li.visible {
    display: block;
}

#timeline div.selectable {
    cursor: pointer;
}

");

Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/jquery-1.8.3.js');
Yii::app()->clientScript->registerScriptFile('/js/timeline', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('timeline', "
$(document).ready(function() {
    $('#lista').timeline();
});
", CClientScript::POS_HEAD);

?>


<!-- div id="timeline" style="height: 30px; overflow: hidden; white-space: nowrap;"></div -->

<div>
    <ul id="lista">
        <li id="globalnet" class="color_c9110C">
            <em>GlobalNET Mobile Solutions</em>
            <img src="/img/logos/globalnet" alt="[Logo]" />
            <span class="from date_2011-10-01"></span><span class="until date_2013-02-28"></span>
            <p>
                Programador

                De financiación privada; De 11 a 50 empleados; Sector de Servicios y tecnología de la información

                Arquitectura del Software, Web services, CMS.
                Web developer
            </p>
        </li>
        <li id="intellicom" class="color_719d43">
            <em>IntelliCom Uruguay</em>
            <img src="/img/logos/intellicom" alt="[Logo]" />
            <span class="from date_2011-04-01"></span><span class="until date_2011-09-30"></span>
            <p>
                Sector de Desarrollo de programación

                marzo de 2011 – octubre de 2011 (8 meses) Uruguay

                Desarrollo web, aplicaciones Facebook, marketing digital.
                Programador, WebMaster, SysAdmin
            </p>
        </li>
        <li id="tochos" class="color_89110C">
            <em>Tochos S.A.</em>
            <img src="/img/logos/tochos" alt="[Logo]" />
            <span class="from date_2008-12-19"></span><span class="until date_2011-02-18"></span>
            <p>
                Tochos S.A.

                De financiación privada; De 1 a 10 empleados; Sector de Bienes inmobiliarios

                2010 – febrero de 2011 (1 año)

                Encargado del desarrollo de soluciones enfocadas en la red Tochos.
                Programador, WebMaster, SysAdmin
            </p>
        </li>
        <li id="etdp" class="color_a9110C">
            <em>El Toro de Picasso</em>
            <span class="from date_2006-11-18"></span><span class="until date_2008-12-18"></span>
            <p>
                El Toro de Picasso

                Asociación; De 1 a 10 empleados; Sector de Desarrollo de programación

                noviembre de 2006 – 2010 (4 años)

                Desarrollo del CMS (versión 2) utilizado en todos los sitios desarrollados por la empresa.
                Encargado de la accesibilidad de los sitios.
                Definición de la API de comunicación entre películas Flash/JavaScript y Flash/servidor.
                Desarrollador Web
            </p>
        </li>
        <li id="willy" class="color_740303">
            <em>El Taller de Willy</em>
            <img src="/img/logos/willy1" alt="[Logo]" /><img src="/img/logos/willy2" alt="[Logo]" />
            <span class="from date_2005-12-17"></span><span class="until date_2006-11-17"></span>
            <p>
                El Taller de Willy

                diciembre de 2005 – noviembre de 2006 (1 año)

                Desarrollo de sitios web y CMS (QuitLine - Laboratório Pfizer).
                CRM's Intranet.
                Comercio electrónico (Pasqualini).
                Sistema de capacitación de empleados (Janssen Cilag).
                Desarrollador Web
            </p>
        </li>
        <li id="precitec" class="color_e9110C">
            <em>PreciTec Informática</em>
            <span class="from date_2003-01-01"></span><span class="until date_2005-12-16"></span>
            <p>
                PreciTec Informática

                2003 – 2005 (2 años)

                Atención a clientes.
                Desarrollo y mantenimiento de portal web, maquetado, desarrollo de CMS y carro de compras.
            </p>
        </li>
    </ul>
</div>