function FacturaControlador(){
	//Atributos
	this.vista = new FacturaVista();
	
	this.cliente = new ClienteControlador(this);
	
	this.numDoc;
	this.fechaEmision;
	this.metodoPago;
	
	this.items;
	this.fechasVencimiento;

	this.ivas = new ContenedorIVA();
	this.total = 0.0;
	
	this.observaciones;
	
	//Variables internas del constructor
	
	//Constructor
	this.vista.init(this);
}

//Funciones de la clase
FacturaControlador.prototype.addIVA = function(tipo, base){
	this.ivas.addIVA(tipo, base);
	
	this.actualizarTotales();
}

FacturaControlador.prototype.removeIVA = function(tipo, base){
	this.ivas.removeIVA(tipo, base);
	
	this.actualizarTotales();
}

FacturaControlador.prototype.actualizarTotales = function(){
	this.vista.actualizarTotales(this.ivas);
}