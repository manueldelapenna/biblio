<script>
	$(function() {
		$( "input:button, a, button,", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
        
        $(function() {
		$( "input:submit, a, button", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
        (function() {
		$( "input:file, a, button", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>

<h1>Restaurar datos del Sistema</h1>

<p>Este proceso restaurará la base de datos a un estado anteriormente resguardado
   a partir de un archivo de extensión ".sql" anteriormente creado por el
   administrador del sistema, si existieran datos en la base de datos, éstos seran
   eliminados de la misma y reemplazados por los del archivo.</p>

<form action="<?php echo url_for('backup/restaurarDatos') ?>" method="POST" enctype="multipart/form-data">
  <table>
    <?php echo $formulario ?>
    <tr>
      <td colspan="2">
        <br/>
        <br/>
        <div class="botones">
          <input type="submit" value="Restaurar Datos" />
          <?php echo button_to('Cancelar restauración de datos  ','backup/index')?>
        </div>
      </td>
    </tr>
  </table>
</form>


   

