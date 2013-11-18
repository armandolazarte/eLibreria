var $div_formulario,
	$idAlbaran,
	$numeroAlbaran,
	$fechaRealizacion,
	$fechaVencimiento,
	$contrato,
	$estado,
	$div_jaula_libros;

delete($.ui.accordion.prototype._keydown);

$(document).ready(function(){
	$div_formulario = $('#form-datos-albaran');
	$div_jaula_libros = $('#jaula-libros');

	$idAlbaran = $div_formulario.find('#albaran-id');
	$numeroAlbaran = $div_formulario.find('#albaran-numero');
	$fechaRealizacion = $div_formulario.find('#albaran-fecha-realizacion');
	$fechaVencimiento = $div_formulario.find('#albaran-fecha-vencimiento');
	$contrato = $div_formulario.find('#albaran-contrato');
	
	$botonActualizar = $div_formulario.find('#actualizar-datos-albaran');
	$estado = $div_formulario.find('.estado');
	
	//Iniciando estado a sinActualizar si es para crear o Actualizado si se edita albaran
	if($idAlbaran.val() != ""){
		modificar_estado($estado, 'actualizado');
	}
	else{
		modificar_estado($estado, 'sinActualizar');
	}

	$numeroAlbaran.change(function(){modificar_estado($estado, 'sinActualizar')});
	$fechaRealizacion.change(function(){modificar_estado($estado, 'sinActualizar')});
	$fechaVencimiento.change(function(){modificar_estado($estado, 'sinActualizar')});
	$contrato.change(function(){modificar_estado($estado, 'sinActualizar')});
	
	$div_formulario.submit(actualizarAlbaran);
});

function funcion_onfocus(elemento, msg){
	return elemento.value=(elemento.value==msg) ? '' : elemento.value;
}

function funcion_onblur(elemento, msg){
	return elemento.value=(elemento.value=='') ? msg : elemento.value;
}

function modificar_estado($elemento, $estado){
	$elemento.removeClass('sinActualizar enProceso actualizado');
	$elemento.addClass($estado);
}

function actualizarAlbaran(event){
	event.preventDefault();
	
	if($estado.hasClass('sinActualizar')){
		$.ajax({
			url: ruta_ajax_registro_albaran,
			type: "POST",
			data: {
				idAlbaran: $idAlbaran.val(),
				numeroAlbaran: $numeroAlbaran.val(),
				contratoId: $contrato.val(),
				fechaRealizacion: $fechaRealizacion.val(),
				fechaVencimiento: $fechaVencimiento.val()
			},
			success: procesarAlbaranRegistrado
		});
	}
}

function procesarAlbaranRegistrado(data){
	if(data.estado){
		$idAlbaran.val(data.idAlbaran);
		
		modificar_estado($estado, 'actualizado');
	}
	else{
		//Errores
	}
}