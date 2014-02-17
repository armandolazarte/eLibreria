function Existencia(venta, idExistencia, tipoExistencia){
	this.venta;
	
	this.id;
	this.tipo;
	
	this.html;
	
	this.precio;
	this.iva;
	this.pvp;
	
	this.botonBorrar;
	
	this.descuento;
	this.precioTotal;
	
	this.des = function(){
		this.venta.des();
	}
	
	this.setExistenciaEnContenedor = function(){
		this.html.appendTo(this.venta.venta_contenedorItems);
	}
	
	this.modificarDescuento = function(){
		var d = 1 - parseFloat(this.precioTotal.val() / this.pvp.val());
		
		this.descuento.val(d.toFixed(2));
		
		this.des();
		this.venta.actualizarTotal();
	}
	
	this.modificarPrecioTotal = function(){
		var t = this.pvp.val() * (1 - (this.descuento.val() / 100));
		
		this.precioTotal.val(t.toFixed(2));

		this.des();
		this.venta.actualizarTotal();
	}
	
	this.getPrecioTotal = function(){
		var res = parseFloat(this.precioTotal.val()).toFixed(2);
		
		if(isNaN(res)){
			res = parseFloat(0);
		}
		
		return res;
	}
		
	this.borrar = function(){
		$.ajax({
			url: ruta_ajax_borrar_existencia,
			context: this,
			type: "POST",
			data: {
				id: this.id,
				tipo: this.tipo
			},
			success: function(data){
				if(data.estado){
					this.venta.borrarItem(this);
					this.html.remove();
					
					if(this.venta.items.length <= 0){
						this.venta.deshabilitarContenedorItem();
					}
					
					this.venta.actualizarTotal();
				}
			}
		});
	}
	
	this.init = function(venta, idExistencia, tipoExistencia){
		this.venta = venta;
		
		this.id = idExistencia;
		this.tipo = tipoExistencia;
		
		$.ajax({
			url: ruta_ajax_plantilla_existencia,
			context: this,
			type: "POST",
			data: {
				id: this.id,
				tipo: this.tipo
			},
			success: function(data){
				$html = $(data);
				var $existencia = this;

				this.precio = $html.find('.item_precio').children('input');
				this.iva = $html.find('.item_iva').children('input');
				this.pvp = $html.find('.item_pvp').children('input');
				
				this.descuento = $html.find('.item_descuento').children('input');
				this.precioTotal = $html.find('.item_precio_total').children('input');
				
				this.botonBorrar = $html.find('.borrar_Articulo');

				this.descuento.change(function(){$existencia.modificarPrecioTotal();});
				this.precioTotal.change(function(){$existencia.modificarDescuento();});
				
				this.botonBorrar.click(function(){$existencia.borrar();});
				
				this.html = $html;
				
				this.setExistenciaEnContenedor();
				this.modificarPrecioTotal();
			}
		});
	}
	
	this.init(venta, idExistencia, tipoExistencia);
}