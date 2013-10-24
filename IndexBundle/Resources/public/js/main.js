function waitCierre(){
	setTimeout(opacidad, 2500, 'flash');
}

function opacidad(elemento){
	document.getElementById(elemento).style.opacity = 0;
	setTimeout(cerrarInfo, 400, elemento);
}

function cerrarInfo(elemento){
	document.getElementById(elemento).style.display = 'none';
	document.getElementById(elemento).style.opacity = 0;
}

function cierre(event, element)
{
	if(quienPulsa(event, element))
	{
		opacidad(elemento);
	}
}

function quienPulsa(e, element)
{
	e = e || event;
	var target = e.target || e.srcElement;
	if(target.id==element.id)
		return true;
	else
		return false;
}

$('.dateJquery').datepicker({ 
	dateFormat: 'dd-mm-yy',
	beforeShow: function (textbox, instance) {
        instance.dpDiv.css({
                marginLeft: textbox.offsetWidth + 'px'
        });
	}
});
$('.timeJquery').timepicker({
	addSliderAccess: true,
	sliderAccessArgs: { touchonly: false },
	beforeShow: function (textbox, instance) {
        instance.dpDiv.css({
                marginLeft: textbox.offsetWidth + 'px'
        });
	}
});
$('.dateTimeJquery').datetimepicker({
	dateFormat: 'dd-mm-yy',
	addSliderAccess: true,
	sliderAccessArgs: { touchonly: false },
	beforeShow: function (textbox, instance) {
        instance.dpDiv.css({
                marginLeft: textbox.offsetWidth + 'px'
        });
	}
});

if($('.estable').val() == 0){
	$('.mensualidad').parent().hide();
}

$('.estable').on('change', function(){
	$elemento=$(this);
	$mensualidad=$('.mensualidad');
	
	if($elemento.val() == 1){
		$mensualidad.parent().show();
	}
	else{
		$mensualidad.parent().hide();
	}
	
	$mensualidad.val('');
});

$(document).ready(function(){
	$('.cajaEleccionEntidades').prepend('<input type="text" class="busqueda" value=\'Filtrar resultados\' onfocus="this.value=(this.value==\'Filtrar resultados\') ? \'\' : this.value;" onblur="this.value=(this.value==\'\') ? \'Filtrar resultados\' : this.value;">');

	$('.cajaEleccionEntidades').find('.busqueda').on("change keyup paste", function(){
		$busqueda = $(this);
		$valor = $busqueda.val().toLowerCase();

		$busqueda.siblings('label').each(function(){
			$label = $(this);
			$input = $label.prev(':first');

			$label.show();
			$input.show();

			aux = $label.html().toLowerCase().indexOf($valor);

			if(aux < 0){
				$label.hide();
				$input.hide();
			}
		});
	});

	$('#rgm_elibreria_suministro_contratosuministrotype_tipoPago').on('change', function(){
		$precioTotal = $('#rgm_elibreria_suministro_contratosuministrotype_precioTotal');
		$fechaR = $('#rgm_elibreria_suministro_contratosuministrotype_fechaAlta');
		$fechaV = $('#rgm_elibreria_suministro_contratosuministrotype_fechaBaja');
		
		
		if($(this).val() == 1){
			$precioTotal.val('');
			$precioTotal.parent().hide();
			$fechaR.val('');
			$fechaR.parent().hide();
			$fechaV.val('');
			$fechaV.parent().hide();
		}
		else{
			$precioTotal.parent().show();
			$fechaR.parent().show();
			$fechaV.parent().show();
		}
	});
	
	$('#rgm_e_libreria_suministrobundle_crearalbarantype_contrato').on('change', function(){
		$fechaV = $('#rgm_e_libreria_suministrobundle_crearalbarantype_fechaVencimiento');
		
		textoSeleccionado = $('#rgm_e_libreria_suministrobundle_crearalbarantype_contrato option:selected').text();
		
		if(textoSeleccionado.indexOf('En efectivo') != -1){
			$fechaV.val('');
			$fechaV.parent().hide();
		}
		else{
			$fechaV.parent().show();
		}
	});
	
	$('#rgm_elibreria_suministro_albarantype_contrato').on('change', function(){
		$fechaV = $('#rgm_elibreria_suministro_albarantype_fechaVencimiento');
		
		textoSeleccionado = $('#rgm_elibreria_suministro_albarantype_contrato option:selected').text();
		
		if(textoSeleccionado.indexOf('En efectivo') != -1){
			$fechaV.val('');
			$fechaV.parent().hide();
		}
		else{
			$fechaV.parent().show();
		}
	});
});

