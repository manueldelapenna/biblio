<?php $socioId = $socio->getId();?>

<script>
	$(function() {
		$( "input:button, a, button", ".botones" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>

<div class="botones">

    <?php echo button_to("Editar", "socio/edit?id=$socioId")?>

</div>

<br/>

<h1>Datos del Socio</h1>

<div id="socio">
    <label class="negrita">Nº Socio:</label><label><?php echo ' ';
        echo $socio->getNumeroSocio(); ?> </label><br/>
    <label class="negrita">Apellido:</label><label><?php echo ' ';
        echo $socio->getApellido(); ?> </label><br/>
    <label class="negrita">Nombre:</label><label><?php echo ' ';
        echo $socio->getNombre();?> </label><br/>
    <label class="negrita">DNI:</label><label><?php echo ' ';
        echo $socio->getDni(); ?> </label><br/>
    <label class="negrita">Domicilio:</label><label><?php echo ' ';
        echo $socio->getDomicilio();?> </label><br/>
    <label class="negrita">Localidad:</label><label><?php echo ' ';
        echo $socio->getLocalidad(); ?> </label><br/>
    <label class="negrita">Codigo Postal:</label><label><?php echo ' ';
        echo $socio->getCodigoPostal();?> </label><br/>
    <label class="negrita">Telefono:</label><label><?php echo ' ';
        echo $socio->getTelefono(); ?> </label><br/>
    <label class="negrita">Celular:</label><label><?php echo ' ';
        echo $socio->getCelular();?> </label><br/>
    <label class="negrita">Mail:</label><label><?php echo ' ';
        echo $socio->getMail(); ?> </label><br/>
    <?php if($socio->getDescuentoFamiliar()){?>
        <label class="negrita">Socio con descuento familiar.</label><br/>
    <?php }else{?>
        <label class="negrita">Socio sin descuento familiar.</label><br/>
    <?php }?>
</div>

<h1>Cobranza</h1>

<?php if (count($cuotasAtrasadas)>0){?>
 <table class="tabla">
        <tr>
            <th>Cuota/s adeudada/s</th>
            <th>Monto</th>
        </tr>
        
        <?php
        $total = 0;
        $primera = true;
        foreach ($cuotasAtrasadas as $cuota){?>
        <tr>
            <td>
                <?php echo $cuota->getMes().'/'.$cuota->getAnio()?>
            </td>
            <td>
                <?php echo '$'.$cuota->getMonto();
                $total=$total+$cuota->getMonto()?>
                
            </td>
            
            <?php if ($primera){
                $primera = false?>
            
                <td>
                    <?php $cuotaId = $cuota->getId();
                    echo link_to('pagar', "cobranza/new?cuotaId=$cuotaId")?>

                </td>
            <?php } ?>
        </tr>
        <?php }?>
        <tr>
            <th>Total deuda</th>
            <th><?php echo '$'.$total?></th>
        </tr>
    </table>
<?php }else{ ?>
		
<label>El socio no posee deuda</label>
<span class="botones">
    <?php echo button_to("Adelantar pago", "cobranza/adelantarCuotas?socioId=$socioId")?>
</span>
<br/>
<br/>



	<?php 
		if ($cantAdelantadas > 0){?>
			<span class="botones">
				<?php echo button_to("Generar recibo cuotas adelantadas", "socio/reciboAdelanto?socioId=$socioId")?>
			</span>
			<br/>
		<?php
		}	  
		

	}

 if (count($cuotasPagas)>0){?>
 <table class="tabla">
        <tr>
            <th>Ultimos pagos</th>
            <th>Monto</th>
            <th></th>
        </tr>
        
        <?php
        $cant = count($cuotasPagas);
        
        if ($cant >= 15){
            $i = 15;
        }else{
            $i = $cant;
        }
        
        $primero = true;
        while ($i > 0){
            $cant--;
            $i--;
            
        ?>
            <tr>
                <td>
                    <?php echo $cuotasPagas[$cant]->getMes().'/'.$cuotasPagas[$cant]->getAnio()?>
                </td>
                <td>
                    <?php echo '$'.$cuotasPagas[$cant]->getMonto();?>

                </td>
                <td>
                    <?php $cuotaId = $cuotasPagas[$cant]->getId();
                    echo link_to('ver',"cobranza/show?cuotaId=$cuotaId");?>

                </td>

                    <?php $cuotaId = $cuotasPagas[$cant]->getId();
                if ($primero){
                        $primero = false;
                ?>
                <td>
                        <?php echo link_to1('quitar pago', "socio/borrarCuota?cuotaId=$cuotaId&socioId=$socioId",array('id' => 'myid', 'confirm' => '¿Está seguro que desea quitar el pago?', 'absolute' => true));
                ?>    
                </td>    
                
                <?php }?>

                

            </tr>
        
        <?php } ?>
        
    </table>
<?php } ?>

<br/>
<br/>

