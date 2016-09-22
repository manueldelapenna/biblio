<style>
<!--
a:hover{text-decoration:none;}
a{text-decoration:none;}
-->
</style> 

<h1 class="negrita">Ingrese la cantidad de cuotas a adelantar</h1>

<form name="formularioCantidad" method="post" action="<?php echo url_for("cobranza/adelantarCuotas")?>">
       
    <?php echo $form?>
    <br/>
    <input type="text" hidden="hidden" name="socioId" value="<?php echo $socioId?>"/>
    
    <br/>
    <input type="submit" value="Adelantar"/>
    <?php echo button_to('Cancelar', "socio/show?id=$socioId")?>
    
</form>


 