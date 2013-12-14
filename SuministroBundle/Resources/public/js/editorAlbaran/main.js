var $albaran;

delete($.ui.accordion.prototype._keydown);

$(document).ready(function(){
	var $idAlb = $('#albaran-id'), 
		$numIden = $('#albaran-numero'), 
		$idContr = $('#albaran-fecha-realizacion'), 
		$fechaRea = $('#albaran-contrato'), 
		$fechaVen = $('#albaran-fecha-vencimiento'), 
		$est = $('#albaran-estado'), 
		$bAct = $('#albaran-boton-submit'),
		$bNuevo = $('#botonAnadirLibro'), 
		$cLibro = $('#libros-albaran');
	
	$albaran = new Albaran($idAlb, $numIden, $idContr, $fechaRea, $fechaVen, $est, $bAct, $bNuevo, $cLibro);
});