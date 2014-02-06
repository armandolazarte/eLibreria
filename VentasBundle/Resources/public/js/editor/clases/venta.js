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

	this.id;
	
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
	
	this.deshabilitarContenedorItem = function(){
		this.venta_contenedorItems.css('display', 'none');
	}
	
	this.actualizarInfo = function(){
		if(! this.isActualizado() && this.items.length > 0){
			var itemsAjax = new Array();
			
			for(var i = 0; i < this.items.length; i++){
				var it = this.items[i];
				itemsAjax.push({
					id: it.id,
					tipo: it.tipo,
					desc: parseFloat(it.descuento.val())
				});			
			}
			
			$.ajax({
				url: ruta_ajax_registro_venta,
				context: this,
				type: "POST",
				data: {
					idVenta: this.id,
					total: parseFloat(this.venta_info_total.html()),
					clienteId: this.cliente.id,
					metodoPago: this.select_metodo_pago.val(),
					items: itemsAjax
				},
				success: function(data){
					if(data.estado){
						this.id = data.idVenta;
						this.act();
					}
				}
			});
		}
		
		//Abrir ventana con ticket
		if(this.isActualizado()){
			
		}
	}
	
	this.actualizarTotal = function(){
		var total = parseFloat(0);
		
		for(var i = 0; i < this.items.length; i++){			
			total += parseFloat(this.items[i].getPrecioTotal());
		}
		
		this.venta_info_total.html(total.toFixed(2) + '€');
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
	
	this.anadirExistencia = function(idExistencia, tipoExistencia){
		$venta.habilitarContenedorItem();
		this.items.push(new Existencia(this, idExistencia, tipoExistencia));
	}
	
	this.borrarItem = function(item){
		var indexElementoABorrar;
		
		for(var i = 0; i < this.items.length; i++){
			var itemActual = this.items[i];
			
			if(itemActual.id === item.id){
				indexElementoABorrar = i;
				break;
			}
		}
		
		this.items.splice(indexElementoABorrar, 1);
	}
	
	this.buscarIdExistencia = function(idExistencia){
		var res = false;
		
		for(var i = 0; i < this.items.length; i++){
			var item = this.items[i];
			
			if(idExistencia === item.id){
				res = true;
				break;
			}
		}
		
		return res;
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
		
		this.input_submit_venta.click(function(evento){evento.preventDefault(); $venta.actualizarInfo(); return false;});
		
		this.select_metodo_pago.change(function(evento){evento.preventDefault(); $venta.cambioModoPago(); return false;});		
		this.venta_info_entregado.change(function(evento){evento.preventDefault(); $venta.modificarValoresInfoVenta(); return false;});
		
		this.venta_busqueda_isbnRef.autocomplete({
			source: function(request, response){
				$.ajax({
			        url: ruta_ajax_buscar_ref,
			        context: this,
			        data: {
			          ref: request.term
			        },
			        type: "POST",
			        success: function( data ){
			        	
			          response( $.map( data.sugerencias, function(item){
			        	  if($venta.buscarIdExistencia(item.id)){
			        		 return null; 
			        	  }
			        	  else{
			          		return {
				          		label: "[ " + item.tipo + " ] " + item.ref + " - " + item.titulo,
				          		value: "ISBN / REF",
				          		titulo: item.titulo,
				          		id: item.id,
				          		tipo: item.tipo
				          	}; 
			        	  }
			          }));
			        }
			      });
			},
			select: function(evento, ui){
				$venta.venta_busqueda_titulo.val("Titulo");
				$venta.anadirExistencia(ui.item.id,ui.item.tipo);
			}
		});
		
		this.venta_busqueda_titulo.autocomplete({
			source: function(request, response){
				$.ajax({
			        url: ruta_ajax_buscar_titulo,
			        context: this,
			        data: {
			          titulo: request.term
			        },
			        type: "POST",
			        success: function( data ){
			          response( $.map( data.sugerencias, function(item){
			        	  if($venta.buscarIdExistencia(item.id)){
			        		 return null; 
			        	  }
			        	  else{
			          		return {
				          		label: "[ " + item.tipo + " ] " + item.ref + " - " + item.titulo,
				          		value: "Titulo",
				          		ref: item.ref,
				          		id: item.id,
				          		tipo: item.tipo
				          	};    
			        	  }
			          }));
			        }
			      });
			},
			select: function(evento, ui){
				$venta.venta_busqueda_titulo.val("ISBN / REF");
				$venta.anadirExistencia(ui.item.id,ui.item.tipo);
			}
		});
		
		$('.venta').data('venta', $venta);
	}
	
	this.init($select_venta_cliente_nombre,$input_venta_cliente_nombre,$input_venta_cliente_tel,$input_venta_cliente_movil,
			$input_venta_cliente_dir,$venta_cliente_estado,$venta_cliente_boton_actualizar,$venta_info_total,$venta_info_entregado,
			$venta_info_vuelta,$select_metodo_pago,$input_submit_venta,$venta_estado,$venta_busqueda_isbnRef,$venta_busqueda_titulo,
			$input_submit_anadir_articulo,$venta_contenedorItems);
}