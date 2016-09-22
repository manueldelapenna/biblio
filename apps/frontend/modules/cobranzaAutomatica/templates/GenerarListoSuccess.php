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

<h1 class="negrita">Generación de cobranza completa</h1>

<label>Si desea generar generar los recibos clickee <?php echo link_to('aquí', 'socioConDeuda/index') ?>.</label>

 