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