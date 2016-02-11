function ClienteVista(){
	this.cliente;
		
	this.selectorCliente;
	
	this.contenedor;
	this.razonSocial;
	this.nif;
	this.razonComercial;
	this.dir;
	this.cp;
	this.tel;
	this.email;
	this.perCont;
	
	this.botonGuardarCliente;
	this.estadoCliente;	
}

ClienteVista.prototype.init = function(c){
	this.cliente = c;

	this.contenedor = $('#fCli');
	this.selectorCliente = $('#fCliSel');
	this.razonSocial = $('#fCliRSocial');
	this.nif = $('#fCliNIF');
	this.razonComercial = $('#fCliRComercial');
	this.dir = $('#fCliDir');
	this.cp = $('#fCliCP');
	this.tel = $('#fCliTel');
	this.email = $('#fCliEmail');
	this.perCont = $('#fCliPerCont');
	this.botonGuardarCliente = $('#fCliGuardarCliente');
	this.estadoCliente = $('#fCliEstado');
	
	this.selectorCliente.change(this.cliente, this.cliente.cambiarCliente);

	this.inicializarInput(this.razonSocial);
	this.inicializarInput(this.nif);
	this.inicializarInput(this.razonComercial);
	this.inicializarInput(this.dir);
	this.inicializarInput(this.cp);
	this.inicializarInput(this.tel);
	this.inicializarInput(this.email);
	this.inicializarInput(this.perCont);
	
	this.setListaClientes(this.cliente.listaClientes);
}

ClienteVista.prototype.inicializarInput = function(i){
	i.data('textInd', i.val());
	i.css('color', '#d9d9d9');
	
	i.change(function(e){
		var $i = $(this);
		
		if($i.val() == ""){
			$i.val($i.data('textInd'));
			$i.css('color', '#d9d9d9');
		}
	});
	
	i.click(function(e){
		var $i = $(this);
		
		if($i.val() == $i.data('textInd')){
			$i.val('');
			$i.css('color', 'inherit');
		}
	});
	
	i.focusout(function(e){
		var $i = $(this);
		
		if($i.val() == ""){
			$i.val($i.data('textInd'));
			$i.css('color', '#d9d9d9');
		}
	});
}

ClienteVista.prototype.act = function(){
	this.estadoCliente.removeClass('sinActualizar');
	this.estadoCliente.addClass('actualizado');
}

ClienteVista.prototype.des = function(){
	this.estadoCliente.removeClass('actualizado');
	this.estadoCliente.addClass('sinActualizar');
}

ClienteVista.prototype.isAct = function(){
	return this.estadoCliente.hasClass('actualizado');
}

ClienteVista.prototype.setCliente = function(cliente){
	this.contenedor.data('id', cliente.id);
	
	this.selectorCliente.val(cliente.id);
	this.nif.val(cliente.nif);
	this.razonSocial.val(cliente.razonSocial);
	this.razonComercial.val(cliente.razonComercial);
	this.dir.val(cliente.dir);
	this.cp.val(cliente.cp);
	this.tel.val(cliente.tel);
	this.email.val(cliente.email);
	this.perCont.val(cliente.perCont);
}

ClienteVista.prototype.getCliente = function(){
	var cliente = {};
	
	cliente.id = this.contenedor.data('id');
	
	cliente.nif = this.nif.val();
	cliente.razonSocial = this.razonSocial.val();	
	cliente.razonComercial = this.razonComercial.val();
	cliente.dir = this.dir.val();
	cliente.cp = this.cp.val();
	cliente.tel = this.tel.val();
	cliente.email = this.email.val();
	cliente.perCont = this.perCont.val();
	
	return cliente;	
}

ClienteVista.prototype.setListaClientes = function(listaClientes){
	var plantilla = this.contenedor.data('plantillasel');
	var pDefault = this.contenedor.data('plantillaseldefault');
	
	this.selectorCliente.empty();
	
	this.selectorCliente.append($(pDefault));
	
	for(i in listaClientes){
		var c = listaClientes[i];
		var p = plantilla;
		
		p = p.replace('[cliente.id]', c.id);
		p = p.replace('[cliente.nombre]', c.nombre);
		
		this.selectorCliente.append($(p));
	}
}