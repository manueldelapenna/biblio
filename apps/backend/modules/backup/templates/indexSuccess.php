<script>
	$(function() {
		$( "input:button, a, button", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>

<div class="botones">

    <?php echo button_to('Resguardar datos','backup/generarBackup')?> <label>(Realiza el resguardo de todos los datos del sistema)</label><br/><br/>
    <?php echo button_to('Restaurar  datos  ','backup/restaurarDatos')?> <label>(Lleva al sistema a un estado anteriormente resguardado)</label>
</div>
