<style>
<!--
a:hover{text-decoration:none;}
a{text-decoration:none;}
-->
</style> 
<script>
	$(function() {
		$( "input:button, a, button", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>

<h1 class="negrita">Sistema de Cobranza de la Biblioteca Popular y Centro Cultural José Ingenieros</h1>

 <?php if ($cantCobranzasPorGenerar > 0) {?>
        <a class="negrita" href="<?php echo url_for('cobranzaAutomatica/index')?>"><div class="error">Hay <?php echo $cantCobranzasPorGenerar ?> cobranza/s por generar (click para verlas)</div></a> 
    <?php }else{
       ?> <div class="exito">No existen cobranzas por generar</div>
        
    <?php } ?>