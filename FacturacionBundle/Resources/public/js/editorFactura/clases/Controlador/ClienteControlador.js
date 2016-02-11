function ClienteControlador(factura){
	this.factura = factura;
	
	this.vista = new ClienteVista();
	
	this.listaClientes = clientes;
	
	if(typeof idCliente != "undefined"){
		this.id = idCliente;
	}
	
	this.cliente = {};
	
	this.vista.init(this);
	
	this.actualizarCliente();
}

ClienteControlador.prototype.clienteDefault = {
		id: '-1',
		nif: 'NIF / CIF',
		razonSocial: 'Razón social',
		razonComercial: 'Razón comercial',
		dir: 'Dirección',
		cp: 'C.P.',
		tel: 'Tel.',
		email: 'Email',
		perCont: 'Persona de contacto',
}

ClienteControlador.prototype.cambiarCliente = function(e){
	var elm = $(this);
	var cliente = e.data;
	
	cliente.id = elm.val();
	cliente.actualizarCliente();
}

ClienteControlador.prototype.getListaClientes = function(){
	
}

ClienteControlador.prototype.getIdCliente = function(){
	var res = '-1';
	
	if(typeof this.id != "undefined"){
		res = this.id;
	}
	
	return res;
}

ClienteControlador.prototype.actualizarCliente = function(){
	if(this.getIdCliente() == '-1'){
		this.vista.setCliente(this.clienteDefault);
	}
	else{
		$.ajax({
			url: ajax.getCliente,
			method: 'POST',
			data: {
				idCliente: this.getIdCliente()
			},
			context: this,
			success: function(data){
				if(data.success){
					this.cliente = data.cliente;
					this.vista.setCliente(this.cliente);
				}
			}
		});
	}
}

