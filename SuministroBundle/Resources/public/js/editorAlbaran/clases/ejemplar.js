function Ejemplar(libro, id){
	this.libro;	
	
	this.id;
	this.tituloId;
	this.divId;
	
	this.localizacion;
	this.precio;
	this.iva;
	this.descuento;
	this.vendido;
	this.adquirido;
	
	this.estado;
	this.botonActualizar;
	this.botonBorrar;
	
	this.init = function(libro, id){
		this.libro = libro;
		
		if(id === undefined){
			this.tituloId = "Ejemplar-nuevo-" + Ejemplar.idTemporal;
			Ejemplar.idTemporal += 1;
		}
		else{
			this.id = id;
			this.tituloId = "Ejemplar-" + id;
		}
				
		$.ajax({
			url: ruta_ajax_plantilla_ejemplar,
			context: this,
			success: function(data){
				var ejemplar = this;
				var $plantilla = $(data.replace(/%idEjemplar%/g, this.tituloId));
				
				this.divId = $plantilla;
				this.divId.data('ejemplar', ejemplar);

				this.localizacion = $plantilla.find('select[name="localizacion"]');
				this.precio = $plantilla.find('input[name="precio"]');
				this.iva = $plantilla.find('input[name="iva"]');
				this.descuento = $plantilla.find('input[name="descuento"]');
				this.vendido = $plantilla.find('input[name="vendido"]');
				this.adquirido = $plantilla.find('input[name="adquirido"]');

				this.localizacion.change(function(){ejemplar.des();});
				this.precio.change(function(){ejemplar.des();});
				this.iva.change(function(){ejemplar.des();});
				this.descuento.change(function(){ejemplar.des();});
				this.vendido.change(function(){ejemplar.des();});
				this.adquirido.change(function(){ejemplar.des();});
				
				this.estado = $plantilla.find('div.estadoEjemplar');
				this.botonActualizar = $plantilla.find('input[name="actualizarEjemplar"]');	
				this.botonBorrar = $plantilla.find('.borrarEjemplar');	

				this.localizacion.click(function(evento){evento.preventDefault(); return false;});
				this.botonActualizar.click(function(evento){evento.preventDefault(); ejemplar.actualizarInformacion(); return false;});
				this.botonBorrar.click(function(evento){evento.preventDefault(); ejemplar.borrarEjemplar(); return false;});

				this.setEjemplarEnContenedor($plantilla);
				this.des();
				
				if(id !== undefined){
					this.cargarInfoAjaxEjemplar();
					
					this.act();
				}
			}
		});
	}
	
	this.cargarInfoAjaxEjemplar = function(){
		$.ajax({
			url: ruta_ajax_get_datos_ejemplar,
			context: this,
			data: {
				idEjemplar: this.id
			},
			type: "POST",
			success: function(data){
				if(data.estado){
					this.localizacion.val(data.localizacion);
					this.precio.val(data.precio);
					this.iva.val(data.iva * 100);
					this.descuento.val(data.descuento * 100);
					
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
	
	this.setEjemplarEnContenedor = function(cuerpo){
		cuerpo.appendTo(this.libro.contenedorEjemplares);
		this.libro.contenedorEjemplares.accordion("refresh");
	}
	
	this.init(libro, id);
	
	this.act = function(){
		modificar_estado(this.estado, 'actualizado');
		this.libro.actGlobal();
	}
	
	this.des = function(){
		modificar_estado(this.estado, 'sinActualizar');
		this.libro.desGlobal();
	}
	
	this.isAct = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.actualizarInformacion = function(){
		this.precio.val(this.precio.val().replace(',', '.'));
		this.iva.val(this.iva.val().replace(',', '.'));
		this.descuento.val(this.descuento.val().replace(',', '.'));
		
		$.ajax({
			url: ruta_ajax_registro_ejemplar,
			type: "POST",
			data: {
				id: this.id,
				albaran: $albaran.idAlbaran.val(),
				isbn: this.libro.isbn.val(),
				localizacion: this.localizacion.val(),
				precio: this.precio.val(),
				iva: this.iva.val() / 100,
				descuento: this.descuento.val() / 100,
				vendido: this.vendido.prop('checked'),
				adquirido: this.adquirido.prop('checked')
			},
			context: this,
			success: function(data){
				if(data.estado){
					this.id = data.idEjemplar;
					this.act();
				}
			}
		});
	}
	
	this.borrarEjemplar = function(){
		this.des();
		
		if(this.id !== undefined){
			$.ajax({
				url: ruta_ajax_borrar_ejemplar,
				type: "POST",
				data: {
					id: this.id
				},
				context: this,
				success: function(data){
					if(data.estado){
						this.libro.borrarEjemplar(this);
						this.libro.actGlobal();
						this.divId.remove();
					}
				}
			});
		}
		else{
			this.libro.borrarEjemplar(this);
			this.divId.remove();
			this.libro.actGlobal();
		}
	}
}

Ejemplar.idTemporal = 1;