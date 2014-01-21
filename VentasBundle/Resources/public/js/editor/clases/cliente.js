function Cliente(
		$select_venta_cliente_nombre,
		$input_venta_cliente_nombre,
		$input_venta_cliente_tel,
		$input_venta_cliente_movil,
		$input_venta_cliente_dir,
		$venta_cliente_estado,
		$venta_cliente_boton_actualizar){
		
	this.select;
	this.nombre;
	this.tel;
	this.movil;
	this.dir;
	this.estado;
	this.boton_actualizar;
	
	//Metodos
	this.des = function(){
		modificar_estado(this.estado, 'sinActualizar');
	}
	
	this.act = function(){
		modificar_estado(this.estado, 'actualizado');
	}
	
	this.isAct = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.actualizar = function(){
		if(!this.isAct()){
			$.ajax({
				url: ruta_cliente_set_nuevo,
				data: {
					id: this.select.val(),
					nombre: this.nombre.val(),
					tel: this.tel.val(),
					movil: this.movil.val(),
					dir: this.dir.val()
				},
				type: "POST",
				context: this,
				success: function(data){
					if(data.estado){
						if(data.nuevo){
							this.select.append('<option value="'+data.id+'">'+this.nombre.val()+'</option>');
							this.select.val(data.id);
						}
						else{
							this.select.find('option[value='+data.id+']').html(this.nombre.val());
						}
						
						this.act();
					}
				}
			});
		}
	}
	
	this.setInfoClienteSelect = function(){
		if(this.select.val() == ""){
			this.nombre.val('');
			this.tel.val('');
			this.movil.val('');
			this.dir.val('');
		}
		else{
			$.ajax({
				url: ruta_cliente_get_info,
				data: {
					id: this.select.val()
				},
				type: "POST",
				context: this,
				success: function(data){
					if(data.estado){
						this.nombre.val(data.nombre);
						this.tel.val(data.tel);
						this.movil.val(data.movil);
						this.dir.val(data.dir);
					}
				}
			});
		}
		
		this.act();
	}
	
	//Contructor
	this.init = function($select_venta_cliente_nombre,$input_venta_cliente_nombre,$input_venta_cliente_tel,$input_venta_cliente_movil,
			$input_venta_cliente_dir,$venta_cliente_estado,$venta_cliente_boton_actualizar){		
		this.select = $select_venta_cliente_nombre;
		this.nombre = $input_venta_cliente_nombre;
		this.tel = $input_venta_cliente_tel;
		this.movil = $input_venta_cliente_movil;
		this.dir = $input_venta_cliente_dir;
		this.estado = $venta_cliente_estado;
		this.boton_actualizar = $venta_cliente_boton_actualizar;
		
		this.act();
		
		var $cliente = this;

		this.select.change(function(evento){evento.preventDefault(); $cliente.setInfoClienteSelect(); return false});
		this.nombre.change(function(evento){evento.preventDefault(); $cliente.des(); return false});
		this.tel.change(function(evento){evento.preventDefault(); $cliente.des(); return false});
		this.movil.change(function(evento){evento.preventDefault(); $cliente.des(); return false});
		this.dir.change(function(evento){evento.preventDefault(); $cliente.des(); return false});
		
		this.boton_actualizar.click(function(evento){evento.preventDefault(); $cliente.actualizar(); return false;})
	}
	
	this.init($select_venta_cliente_nombre,$input_venta_cliente_nombre,$input_venta_cliente_tel,$input_venta_cliente_movil,
			$input_venta_cliente_dir,$venta_cliente_estado,$venta_cliente_boton_actualizar);
}