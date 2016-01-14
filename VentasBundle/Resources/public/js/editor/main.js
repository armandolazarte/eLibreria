var $venta;

$(document).ready(function(){
	var $select_venta_cliente_nombre = $('#select_venta_cliente_nombre'),
		$input_venta_cliente_nombre = $('#input_venta_cliente_nombre'),
		$input_venta_cliente_tel = $('#input_venta_cliente_tel'),
		$input_venta_cliente_movil = $('#input_venta_cliente_movil'),
		$input_venta_cliente_dir = $('#input_venta_cliente_dir'),
		$venta_cliente_estado = $('#venta_cliente_estado'),
		$venta_cliente_boton_actualizar = $('#venta_cliente_boton_actualizar'),
		$venta_info_total = $('#venta_info_total'),
		$venta_info_entregado = $('#venta_info_entregado'),
		$venta_info_vuelta = $('#venta_info_vuelta'),
		$select_metodo_pago = $('#select_metodo_pago'),
		$input_submit_venta = $('#input_submit_venta'),
		$venta_estado = $('#venta_estado'),
		$venta_busqueda_isbnRef = $('#venta_busqueda_isbnRef'),
		$venta_busqueda_titulo = $('#venta_busqueda_titulo'),
		$venta_anadir_concepto = $('#venta_anadir_concepto'),
		$input_submit_anadir_articulo = $('#input_submit_anadir_articulo'),
		$venta_contenedorItems = $('#venta_contenedorItems');
	
	$venta = new Venta(
			$select_venta_cliente_nombre,
			$input_venta_cliente_nombre,
			$input_venta_cliente_tel,
			$input_venta_cliente_movil,
			$input_venta_cliente_dir,
			$venta_cliente_estado,
			$venta_cliente_boton_actualizar,
			$venta_info_total,
			$venta_info_entregado,
			$venta_info_vuelta,
			$select_metodo_pago,
			$input_submit_venta,
			$venta_estado,
			$venta_busqueda_isbnRef,
			$venta_busqueda_titulo,
			$venta_anadir_concepto,
			$input_submit_anadir_articulo,
			$venta_contenedorItems);
});