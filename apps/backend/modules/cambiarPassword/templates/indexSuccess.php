<script>
	$(function() {
		$( "input:button, a, button,", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
        
        $(function() {
		$( "input:submit, a, button", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>

<form action="<?php echo url_for('cambiarPassword/index') ?>" method="POST">

    <h1> Cambiar contraseña </h1>

    <?php echo $form ?>

    <br/>
    <br/>
    <div class="botones">
        <input type="submit" value="Guardar nueva contraseña" />
        <?php echo button_to('Cancelar y volver a pantalla de inicio','index/index')?>
    </div>
</form>
<br/>
<br/>

