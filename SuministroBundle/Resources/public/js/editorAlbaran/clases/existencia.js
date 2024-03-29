function Existencia(padre, id, precioInit, precioIVAInit, ivaInit, descuentoInit, beneficioInit, vendidoInit, adquiridoInit, localizacionInit){
	this.padre;
	
	this.id;
	this.tituloId;

	this.localizacion;
	this.precio;
	this.precioIVA;
	this.iva;
	this.descuento;
	this.beneficio;
	this.vendido;
	this.adquirido;
	this.formExistencia;
	
	this.estado;
	this.botonActualizar;
	this.botonBorrar;
	
	this.init = function(padre, id, precioInit, precioIVAInit, ivaInit, descuentoInit, beneficioInit, vendidoInit, adquiridoInit, localizacionInit){
		this.padre = padre;
		
		//console.debug(localizacionInit);
		
		if(id === undefined){
			this.tituloId = "Existencia nueva-" + Existencia.idTemporal;
			Existencia.idTemporal += 1;
		}
		else{
			this.id = id;
			this.tituloId = "Existencia-" + id;
		}
				
		$.ajax({
			url: ruta_ajax_plantilla_existencia,
			context: this,
			success: function(data){
				var existencia = this;
				var $plantilla = $(data);
				
				this.divId = $plantilla;
				this.divId.data('existencia', existencia);

				this.localizacion = $plantilla.find('select[name="localizacion"]');
				this.precio = $plantilla.find('input[name="precio"]');
				this.precioIVA = $plantilla.find('input[name="precioIVA"]');
				this.iva = $plantilla.find('select[name="iva"]');
				this.descuento = $plantilla.find('input[name="descuento"]');
				this.beneficio = $plantilla.find('input[name="beneficio"]');
				this.vendido = $plantilla.find('input[name="vendido"]');
				this.adquirido = $plantilla.find('input[name="adquirido"]');
				this.formExistencia = $plantilla.find('.form-datos-existencia');

				if(precioInit){
					this.precio.val(precioInit.val());
				}
				
				if(ivaInit){
					this.iva.val(ivaInit.val());
				}
				
				if(descuentoInit){
					this.descuento.val(descuentoInit.val());
				}
				
				if(beneficioInit){
					this.beneficio.val(beneficioInit.val());
				}
				
				if(vendidoInit){
					this.vendido.prop('checked', vendidoInit.prop('checked'));
				}
				
				if(adquiridoInit){
					this.adquirido.prop('checked', adquiridoInit.prop('checked'));
				}
				
				//console.debug(localizacionInit);
				
				if(localizacionInit){
					this.localizacion.val(localizacionInit.val());
				}

				this.localizacion.change(function(){existencia.des();});
				this.precio.change(function(){existencia.des(); existencia.precioIVA_actualizar();});
				this.precioIVA.change(function(){existencia.des(); existencia.precioIVA_modificarPrecio();});
				this.iva.change(function(){existencia.des();});
				this.descuento.change(function(){existencia.des();});
				this.beneficio.change(function(){existencia.des();});
				this.vendido.change(function(){existencia.des();});
				this.adquirido.change(function(){existencia.des();});
				
				this.estado = $plantilla.find('div.estadoExistencia');
				this.botonActualizar = $plantilla.find('input[name="actualizarExistencia"]');	
				this.botonBorrar = $plantilla.find('.borrarExistencia');	

				this.localizacion.click(function(evento){evento.preventDefault(); return false;});
				
				this.formExistencia.submit(function(evento){evento.preventDefault(); existencia.actualizarInformacion(); return false;});
				this.botonBorrar.click(function(evento){evento.preventDefault(); existencia.borrarExistencia(); return false;});

				this.setExistenciaEnContenedor($plantilla);
				this.des();
				
				if(id !== undefined){
					this.cargarInfoAjaxExistencia();
					
					this.act();
				}
				
				if(localizacionInit){
					this.actualizarInformacion();

					this.act();
				}
			}
		});
	}
	
	this.cargarInfoAjaxExistencia = function(){
		$.ajax({
			url: ruta_ajax_get_datos_existencia,
			context: this,
			data: {
				idExistencia: this.id,
				tipoPadre: this.padre.tipo
			},
			type: "POST",
			success: function(data){
				if(data.estado){
					this.localizacion.val(data.localizacion);
					this.iva.val(data.iva);
					this.precio.val(data.precio);
					this.precioIVA_actualizar();
					this.descuento.val(data.descuento * 100);
					this.beneficio.val(data.beneficio * 100);
					
					if(data.vendido){
						this.vendido.prop('checked', true);
					}
					
					if(data.adquirido){
						this.adquirido.prop('checked', true);
					}
				}
			}
		});
	}
	
	this.setExistenciaEnContenedor = function(cuerpo){
		cuerpo.appendTo(this.padre.contenedorExistencias);
		this.padre.contenedorExistencias.accordion("refresh");
		this.padre.actItem();
	}
	
	this.init(padre, id, precioInit, precioIVAInit, ivaInit, descuentoInit, beneficioInit, vendidoInit, adquiridoInit, localizacionInit);
	
	this.act = function(){
		this.estado.off('click');
		modificar_estado(this.estado, 'actualizado');
		this.padre.actGlobal();
	}
	
	this.des = function(){
		this.estado.on('click', function(evento){
				evento.preventDefault();
				$(this).parent().siblings(".cuerpo-existencia").children("form").submit();
				return false;
			});
		modificar_estado(this.estado, 'sinActualizar');
		this.padre.desGlobal();
	}
	
	this.isAct = function(){
		var res = false;
		
		if(this.estado != undefined){
			res = this.estado.hasClass('actualizado');
		}
		
		return res;
	}
	
	this.actualizarInformacion = function(){
		this.iva.val(this.iva.val().replace(',', '.'));
		this.precio.val(this.precio.val().replace(',', '.'));
		this.precioIVA_actualizar();
		this.descuento.val(this.descuento.val().replace(',', '.'));
		
		$.ajax({
			url: ruta_ajax_registro_existencia,
			type: "POST",
			data: {
				id: this.id,
				albaran: $albaran.idAlbaran.val(),
				tipo: this.padre.tipo,
				idPadre: this.padre.getId(),
				localizacion: this.localizacion.val(),
				precio: this.precio.val(),
				iva: this.iva.val(),
				descuento: this.descuento.val() / 100,
				beneficio: this.beneficio.val() / 100,
				vendido: this.vendido.prop('checked'),
				adquirido: this.adquirido.prop('checked')
			},
			context: this,
			success: function(data){
				if(data.estado){
					this.id = data.idExistencia;
					this.act();
				}
			}
		});
	}
	
	this.precioIVA_modificarPrecio = function(){
		var p = parseFloat(this.precioIVA.val());
		var i = parseFloat(this.iva.val());
		var au = parseFloat(1);
		this.precio.val((p / (au + i)).toFixed(2));
	}
	
	this.precioIVA_actualizar = function(){
		var p = parseFloat(this.precio.val());
		var i = parseFloat(this.iva.val());
		var au = parseFloat(1);
		this.precioIVA.val((p * (au + i)).toFixed(2));
	}
	
	this.borrarExistencia = function(){
		this.des();
		
		if(this.id !== undefined){
			$.ajax({
				url: ruta_ajax_borrar_existencia,
				type: "POST",
				data: {
					id: this.id,
					tipo: this.padre.tipo
				},
				context: this,
				success: function(data){
					if(data.estado){
						this.padre.borrarExistencia(this);
						this.padre.actGlobal();
						this.divId.remove();
					}
				}
			});
		}
		else{
			this.padre.borrarExistencia(this);
			this.divId.remove();
			this.padre.actGlobal();
		}
	}
}

Existencia.idTemporal = 1;