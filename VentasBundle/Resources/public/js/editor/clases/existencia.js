function Existencia(venta, idExistencia, tipoExistencia){
	this.venta;
	
	this.id;
	this.tipo;
	
	this.html;
	
	this.precio;
	this.iva;
	
	this.descuento;
	this.precioTotal;
	
	this.setExistenciaEnContenedor = function(){
		this.html.appendTo(this.venta.venta_contenedorItems);
		this.venta.venta_contenedorItems.accordion("refresh");
	}
	
	this.modificarDescuento = function(){
		var pt = (-1) * parseFloat(this.precioTotal.val());
		var d = (pt * 100 / parseFloat(this.precio.val())) + 100 + parseFloat(this.iva.val());
		
		this.descuento.val(d.toFixed(2));
		
		this.venta.actualizarTotal();
	}
	
	this.modificarPrecioTotal = function(){
		var i = this.precio.val() * (this.iva.val() / 100);
		var d = this.precio.val() * (this.descuento.val() / 100);
		var t = this.precio.val() - d + i;
		
		this.precioTotal.val(t.toFixed(2));
		
		this.venta.actualizarTotal();
	}
	
	this.getPrecioTotal = function(){
		var res = parseFloat(this.precioTotal.val()).toFixed(2);
		
		if(isNaN(res)){
			res = parseFloat(0);
		}
		
		return res;
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
				
				this.descuento = $html.find('.item_descuento').children('input');
				this.precioTotal = $html.find('.item_precio_total').children('input');

				this.descuento.change(function(){$existencia.modificarPrecioTotal();});
				this.precioTotal.change(function(){$existencia.modificarDescuento();});
				
				this.html = $html;
				
				this.setExistenciaEnContenedor();
			}
		});
	}
	
	this.init(venta, idExistencia, tipoExistencia);
}