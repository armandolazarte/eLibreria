function ContenedorIVA(){
	this.ivas = {};
}

ContenedorIVA.prototype.getArrayIVAS = function (){
	return this.ivas;
}

ContenedorIVA.prototype.getIVAControlador = function(tipo){
	var iva = this.ivas[tipo];
	
	if(typeof iva == "undefined"){
		iva = new IVAControlador(tipo);
		
		this.ivas[tipo] = iva;
	}
	
	return iva;
}

ContenedorIVA.prototype.addIVA = function(tipo, base){
	var iva = this.getIVAControlador(tipo);	
	iva.addBase(base);
}

ContenedorIVA.prototype.removeIVA = function(tipo, base){
	var iva = this.getIVAControlador(tipo);
	iva.removeBase(base);
}

ContenedorIVA.prototype.getTotal = function(){
	var t = 0.0;
	
	for(tipo in this.ivas){
		var iC = this.ivas[tipo];
		
		t = t + parseFloat(iC.getTotal());
	}
	
	return t.toFixed(2);
}