function Albaran(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, estGlobal, est, bAct, bLibroNuevo, bArticuloNuevo, cItems){
	//Atributos privados
	this.idAlbaran;
	
	this.formularioAlbaran;
	
	this.numeroIdentificacion;
	this.idContratoDistribucion;
	this.fechaRealizacion;
	this.fechaVencimiento;
	
	this.estado;
	this.estadoGlobal;
	this.botonActualizar;
	
	this.totalItems = 0;
	this.itemActual = 0;
	
	this.items = new Array();

	this.botonAnadirLibro;
	this.botonAnadirArticulo;
	this.contenedorItems;
	
	this.actItem = function(){
		this.itemActual += 1;
		
		$("#visor").progressbar({value:this.itemActual});
	}
	
	this.des = function(){
		modificar_estado(this.estado, 'sinActualizar');
		this.desGlobal();
	}
	
	this.desGlobal = function(){
		modificar_estado(this.estadoGlobal, 'sinActualizar');
	}
	
	this.act = function(){
		modificar_estado(this.estado, 'actualizado');
		this.actGlobal();
	}
	
	this.actGlobal = function(){
		if(this.isActGlobal()){
			modificar_estado(this.estadoGlobal, 'actualizado');
		}
	}
	
	this.isAct = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.isActGlobal = function(){
		var res = false;
		
		if(this.isAct()){
			var elementosActualizados = true;
			
			for(var i = 0; i < this.items.length; i++){
				elementosActualizados &= this.items[i].isActGlobal();
			}
			
			if(elementosActualizados){
				res = true;
			}
		}
		
		return res;
	}
	
	this.verContenedorItems = function(){
		this.contenedorItems.accordion({
	        header: "> div > h3",
	        active: false,
	        heightStyle: "content",
	        collapsible: true,
	        icons: { "header": "ui-icon-plus", "headerSelected": "ui-icon-minus" }
	      })
	      .sortable({
	        axis: "y",
	        handle: "h3",
	        stop: function( event, ui ) {
	          ui.item.children( "h3" ).triggerHandler( "focusout" );
	        }
	      });
		
		this.contenedorItems.parent().css('display', 'block');
	}
	
	this.actualizarInformacion = function(){
		if(!this.isAct()){
			$.ajax({
				url: ruta_ajax_registro_albaran,
				context: this,
				type: "POST",
				data: {
					idAlbaran: this.idAlbaran.val(),
					numeroAlbaran: this.numeroIdentificacion.val(),
					contratoId: this.idContratoDistribucion.val(),
					fechaRealizacion: this.fechaRealizacion.val(),
					fechaVencimiento: this.fechaVencimiento.val()
				},
				success: function(data){
					if(data.estado){
						this.idAlbaran.val(data.idAlbaran);
						this.act();
						this.verContenedorItems();
					}
				}
			});
		}		
	}
	
	this.anadirLibroExistente = function(isbn, existenciasArray){
		console.log('nuevo libro ' + new Date());	
		this.items.push(new Libro(this, isbn, existenciasArray));
		console.log(new Date());
	}
	
	this.anadirLibroNuevo = function(){
		this.desGlobal();
		this.items.push(new Libro(this, undefined, new Array()));
	}
	
	this.anadirArticuloExistente = function(ref, existenciasArray){
		this.items.push(new Articulo(this, ref, existenciasArray));
	}
	
	this.anadirArticuloNuevo = function(){
		this.desGlobal();
		this.items.push(new Articulo(this, undefined, new Array()));
	}
	
	this.cargarItemsAlbaranAJAX = function(){
		var albaran = this;
		$.ajax({
			url: ruta_ajax_get_items_albaran,
			data: {
				idAlbaran: this.idAlbaran.val()
			},
			type: "POST",
			success: function(data){	
				var itemsRecibidos = data.items;
								
				for(var refItem in itemsRecibidos){
					albaran.totalItems += 1;
					
					var item = itemsRecibidos[refItem];
										
					if(item.tipo === 'libro'){
						albaran.anadirLibroExistente(refItem, item.existencias);
					}
					else if(item.tipo === 'articulo'){
						albaran.anadirArticuloExistente(refItem, item.existencias);						
					}
				}
				
				$("#visor").progressbar({max: albaran.totalItems});
			}
		});
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
	
	//Contructor
	this.init = function(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, estGlobal, est, bAct, bLibroNuevo, bArticuloNuevo, cItems){		
		this.idAlbaran = idAlb;
		this.formularioAlbaran = formAlb;
		
		this.numeroIdentificacion = numIden;
		this.idContratoDistribucion = idContr;
		this.fechaRealizacion = fechaRea;
		this.fechaVencimiento = fechaVen;
		
		this.estado = est;
		this.estadoGlobal = estGlobal;
		this.botonActualizar = bAct;

		this.botonAnadirLibro = bLibroNuevo;
		this.botonAnadirArticulo = bArticuloNuevo;
		this.contenedorItems = cItems;

		this.numeroIdentificacion.change(function(){$albaran.des();});
		this.idContratoDistribucion.change(function(){$albaran.des();});
		this.fechaRealizacion.change(function(){$albaran.des();});
		this.fechaVencimiento.change(function(){$albaran.des();});
		
		this.formularioAlbaran.submit(function(evento){evento.preventDefault(); $albaran.actualizarInformacion(); $albaran.act(); return false;});

		this.botonAnadirLibro.click(function(evento){evento.preventDefault(); $albaran.anadirLibroNuevo(); return false;});
		this.botonAnadirArticulo.click(function(evento){evento.preventDefault(); $albaran.anadirArticuloNuevo(); return false;});
				
		if(this.idAlbaran.val() != ""){
			//Albaran con datos
			
			this.act();
			this.verContenedorItems();

			this.cargarItemsAlbaranAJAX();
		}
	}
	
	this.init(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, estGlobal, est, bAct, bLibroNuevo, bArticuloNuevo, cItems);
}