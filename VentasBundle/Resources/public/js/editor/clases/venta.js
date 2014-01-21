function Venta(
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
		$input_submit_anadir_articulo,
		$venta_contenedorItems){	

	this.cliente;
	this.venta_info_total;
	this.venta_info_entregado;
	this.venta_info_vuelta;
	this.select_metodo_pago;
	this.input_submit_venta;
	this.estado;
	this.venta_busqueda_isbnRef;
	this.venta_busqueda_titulo;
	this.input_submit_anadir_articulo;
	
	this.items = new Array();
	this.venta_contenedorItems;
	
	this.act = function(){
		modificar_estado(this.estado, 'actualizado');
	}
	
	this.des = function(){
		modificar_estado(this.estado, 'sinActualizar');
	}
	
	this.isActualizado = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.habilitarContenedorItem = function(){
		this.venta_contenedorItems.css('display', 'block');
	}
	
	this.modificarValoresInfoVenta = function(){
		var total = this.venta_info_total.html();
		var entregado = this.venta_info_entregado.val();
		
		var vuelta = parseFloat(entregado) - parseFloat(total);
		
		this.venta_info_vuelta.html(vuelta.toFixed(2) + '€');
	}
	
	this.cambioModoPago = function(){
		if(this.select_metodo_pago.val() === 'efectivo'){
			this.venta_info_entregado.prop('disabled', false);
			this.venta_info_entregado.val('0.00');
			this.venta_info_vuelta.html('0.00€');
		}
		else if(this.select_metodo_pago.val() === 'tarjeta'){
			this.venta_info_entregado.prop('disabled', true);
			this.venta_info_entregado.val('----');
			this.venta_info_vuelta.html('----');			
		}
	}
	
	//Contructor
	this.init = function($select_venta_cliente_nombre,$input_venta_cliente_nombre,$input_venta_cliente_tel,$input_venta_cliente_movil,
			$input_venta_cliente_dir,$venta_cliente_estado,$venta_cliente_boton_actualizar,$venta_info_total,$venta_info_entregado,
			$venta_info_vuelta,$select_metodo_pago,$input_submit_venta,$venta_estado,$venta_busqueda_isbnRef,$venta_busqueda_titulo,
			$input_submit_anadir_articulo,$venta_contenedorItems){
		
		this.cliente = new Cliente(
				$select_venta_cliente_nombre,
				$input_venta_cliente_nombre,
				$input_venta_cliente_tel,
				$input_venta_cliente_movil,
				$input_venta_cliente_dir,
				$venta_cliente_estado,
				$venta_cliente_boton_actualizar);
		
		this.venta_info_total = $venta_info_total;
		this.venta_info_entregado = $venta_info_entregado;
		this.venta_info_vuelta = $venta_info_vuelta;
		this.select_metodo_pago = $select_metodo_pago;
		this.input_submit_venta = $input_submit_venta;
		this.estado = $venta_estado;
		this.venta_busqueda_isbnRef = $venta_busqueda_isbnRef;
		this.venta_busqueda_titulo = $venta_busqueda_titulo;
		this.input_submit_anadir_articulo = $input_submit_anadir_articulo;
		this.venta_contenedorItems = $venta_contenedorItems;
		
		var $venta = this;
		
		this.select_metodo_pago.change(function(evento){evento.preventDefault(); $venta.cambioModoPago(); return false;});		
		this.venta_info_entregado.change(function(evento){evento.preventDefault(); $venta.modificarValoresInfoVenta(); return false;});
		
		this.venta_busqueda_isbnRef;
		this.venta_busqueda_titulo;
	}
	
	this.init($select_venta_cliente_nombre,$input_venta_cliente_nombre,$input_venta_cliente_tel,$input_venta_cliente_movil,
			$input_venta_cliente_dir,$venta_cliente_estado,$venta_cliente_boton_actualizar,$venta_info_total,$venta_info_entregado,
			$venta_info_vuelta,$select_metodo_pago,$input_submit_venta,$venta_estado,$venta_busqueda_isbnRef,$venta_busqueda_titulo,
			$input_submit_anadir_articulo,$venta_contenedorItems);
}