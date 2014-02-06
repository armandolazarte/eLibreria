var $albaran;

delete($.ui.accordion.prototype._keydown);

$(document).ready(function(){
	var $idAlb = $('#albaran-id'), 
		$formAlb = $('#form-datos-albaran'),
		$numIden = $('#albaran-numero'), 
		$idContr = $('#albaran-contrato'), 
		$fechaRea = $('#albaran-fecha-realizacion'), 
		$fechaVen = $('#albaran-fecha-vencimiento'), 
		$estGlobal = $('#albaran-estado-global'), 
		$est = $('#albaran-estado'), 
		$bAct = $('#albaran-boton-submit'),
		$bLibroNuevo = $('#botonAnadirLibro'),
		$bArticuloNuevo = $('#botonAnadirArticulo'), 
		$cItems = $('#items-albaran');
	
	$albaran = new Albaran($idAlb, $formAlb, $numIden, $idContr, $fechaRea, $fechaVen, $estGlobal, $est, $bAct, $bLibroNuevo, $bArticuloNuevo, $cItems);
	
	$(window).on('beforeunload', function(){
		if(!$albaran.isActGlobal()){
			return "El albaran aun tiene datos sin guardar";	
		}
	});
});