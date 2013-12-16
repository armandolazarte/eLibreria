var $albaran;

delete($.ui.accordion.prototype._keydown);

$(document).ready(function(){
	var $idAlb = $('#albaran-id'), 
		$formAlb = $('#form-datos-albaran'),
		$numIden = $('#albaran-numero'), 
		$idContr = $('#albaran-contrato'), 
		$fechaRea = $('#albaran-fecha-realizacion'), 
		$fechaVen = $('#albaran-fecha-vencimiento'), 
		$est = $('#albaran-estado'), 
		$bAct = $('#albaran-boton-submit'),
		$bNuevo = $('#botonAnadirLibro'), 
		$cLibro = $('#libros-albaran');
	
	$albaran = new Albaran($idAlb, $formAlb, $numIden, $idContr, $fechaRea, $fechaVen, $est, $bAct, $bNuevo, $cLibro);
});