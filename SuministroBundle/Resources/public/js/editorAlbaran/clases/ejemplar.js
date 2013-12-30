function Ejemplar(libro, id){
	this.libro;
	
	this.id;
	this.divId;
	
	this.localizacion;
	this.precio;
	this.iva;
	this.descuento;
	this.vendido;
	this.adquirido;
	
	this.estado;
	this.botonActualizar;
	
	this.init = function(libro, id){
		this.libro = libro;
		
		if(id === undefined){
			this.id = "Ejemplar-nuevo-" + Ejemplar.idTemporal;
			Ejemplar.idTemporal += 1;
		}
		else{
			this.id = "Ejemplar-" + id;
		}
				
		$.ajax({
			url: ruta_ajax_plantilla_ejemplar,
			context: this,
			success: function(data){
				var ejemplar = this;
				var $plantilla = $(data.replace(/%idEjemplar%/g, this.id));
				
				this.divId = $plantilla;
				this.divId.data('ejemplar', ejemplar);

				this.localizacion = $plantilla.find('select[name="localizacion"]');
				this.precio = $plantilla.find('input[name="precio"]');
				this.iva = $plantilla.find('input[name="iva"]');
				this.descuento = $plantilla.find('input[name="descuento"]');
				this.vendido = $plantilla.find('input[name="vendido"]');
				this.adquirido = $plantilla.find('input[name="adquirido"]');
				
				this.estado = $plantilla.find('div.estadoEjemplar');
				this.botonActualizar = $plantilla.find('input[name="actualizarEjemplar"]');				

				this.setEjemplarEnContenedor($plantilla);
				this.des();
				
				if(id !== undefined){
					this.act();
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
}

Ejemplar.idTemporal = 1;