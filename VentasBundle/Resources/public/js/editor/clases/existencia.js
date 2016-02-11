function Existencia(venta, idExistencia, tipoExistencia, infoExistencia){
	this.venta;
	
	this.id;
	this.tipo;
	
	this.html;
	
	this.titulo;
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
	
	this.modificarPrecioSinIVA = function(){
		var vPVP = parseFloat(this.pvp.val());
		var vIVA = parseFloat(this.iva.val());
		
		var p = vPVP / (1+vIVA);
		
		if(!isNaN(p)){
			this.precio.val(p.toFixed(2));
			this.des();
		}
	}
	
	this.modificarDescuento = function(){
		var d = (1 - parseFloat(this.precioTotal.val() / this.pvp.val())) * 100;
		
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
	
	this.init = function(venta, idExistencia, tipoExistencia, infoExistencia){
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
				this.titulo = $html.find('.item_titulo').children('input');
												
				if(this.tipo == "concepto"){
					this.iva = $html.find('.item_iva').children('select');
					this.iva.change(function(){$existencia.modificarPrecioSinIVA();});
				}
				else{
					this.iva = $html.find('.item_iva').children('input');
				}
				
				this.pvp = $html.find('.item_pvp').children('input');
				
				if(this.tipo == "concepto"){
					this.pvp.change(function(){$existencia.modificarPrecioTotal(); $existencia.modificarPrecioSinIVA();});
				}
				
				this.descuento = $html.find('.item_descuento').children('input');
				this.precioTotal = $html.find('.item_precio_total').children('input');
				
				this.botonBorrar = $html.find('.borrar_Articulo');

				this.descuento.change(function(){$existencia.modificarPrecioTotal();});
				this.precioTotal.change(function(){$existencia.modificarDescuento();});
				
				this.botonBorrar.click(function(){$existencia.borrar();});
				
				this.html = $html;
				
				if(typeof infoExistencia != 'undefined'){			
					if(Object.keys(infoExistencia).length > 0 && this.tipo == "concepto"){
						this.titulo.val(infoExistencia.titulo);
						this.precio.val(infoExistencia.precio);
						this.iva.val(infoExistencia.iva);
						this.pvp.val(infoExistencia.pvp);
						this.descuento.val(infoExistencia.descuento);
						this.precioTotal.val(infoExistencia.precioTotal);
					}
				}
				
				this.setExistenciaEnContenedor();
				this.modificarPrecioTotal();
			}
		});
	}
	
	this.init(venta, idExistencia, tipoExistencia, infoExistencia);
}