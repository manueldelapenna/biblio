<?php use_helper('Date') ?>

<script>
	$(function() {
		$( "input:button, a, button", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>

<h1>Datos de la Cobranza</h1>

<div id="cobranza">
    <label class="negrita">Socio:</label><label><?php echo ' ';
        echo $cuota->getSocio()->getApellido().', '.$cuota->getSocio()->getNombre(); ?> </label><br/>
    <label class="negrita">Fecha:</label><label><?php echo ' ';
        echo format_datetime($cobranza->getFecha(), 'd', 'es_AR') ?></label><br/>
    <label class="negrita">Cobrador:</label><label><?php echo ' ';
        echo $cobranza->getCobrador()->getApellido().', '.$cobranza->getCobrador()->getNombre(); ?> </label><br/>
    <label class="negrita">Lugar de cobro:</label><label><?php echo ' ';
        echo $cobranza->getLugarCobro()->getNombre();?> </label><br/>
    <label class="negrita">Cuota:</label><label><?php echo ' ';
        echo $cuota->getMes().'/'.$cuota->getAnio(); ?> </label><br/>
</div>


<br/>
<br/>
<div class="botones">

    <?php $socioId = $cuota->getSocioId();
    echo button_to("Volver a socio", "socio/show?id=$socioId")?>

</div>