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
        
        function validar(){
            if (document.formularioValor.valor.value.length==0){
               jAlert("Tiene que escribir un valor de cuota","Error")
               document.formulariovalor.valor.focus()
               return 0;
            }
            var expr = /^[\+\-]?[0-9]*$/;
            if (!expr.test(document.formularioValor.valor.value)){
               jAlert("Tiene que escribir un valor numerico para el valor de cuota","Error")
               document.formulariovalor.valor.focus()
               return 0;
            }
            if (document.formularioValor.valorDescuento.value.length==0){
               jAlert("Tiene que escribir un valor de cuota con descuento","Error")
               document.formulariovalor.valorDescuento.focus()
               return 0;
            }
            var expr = /^[\+\-]?[0-9]*$/;
            if (!expr.test(document.formularioValor.valorDescuento.value)){
               jAlert("Tiene que escribir un valor numerico para la cuota con descuento","Error")
               document.formulariovalor.valorDescuento.focus()
               return 0;
            }
            document.formularioValor.submit(); 
                
        }
</script>

<h1 class="negrita">Ingrese el valor de la cuota <?php echo $cobranzaAutomatica->getMes().'/'.$cobranzaAutomatica->getAnio()?></h1>

<label>Los valores deben ser numéricos, sin signos ni comas, por ejemplo: "15" (sin las comillas).</label>
<br/>
<br/>
<?php $cobranzaAutomaticaId = $cobranzaAutomatica->getId();?>

<form name="formularioValor" method="post" action="<?php echo url_for("cobranzaAutomatica/generarListo")?>">
    <label>Valor cuota:</label><input type="text" name="valor" value="<?php echo $cobranzaAutomatica->getValorCuota()?>"/>
    <label>Valor cuota con descuento familiar:</label><input type="text" name="valorDescuento" value="<?php echo $cobranzaAutomatica->getValorCuotaDescuento()?>"/>
    <input type="text" hidden="hidden" name="cobranzaAutomaticaId" value="<?php echo $cobranzaAutomaticaId?>"/>
    <input type="button" onclick="validar()" value="Generar"/>
    <?php echo button_to('Cancelar', 'cobranzaAutomatica/index')?>
    
</form>

 