function Ejemplar(libro, id){
	this.libro;
	
	this.id;
	
	this.init = function(libro, id){
		this.libro = libro;
		
		if(id === undefined){
			this.id = "ejemplar-temporal-" + Ejemplar.idTemporal;
			Ejemplar.idTemporal += 1;
		}
		else{
			this.id = "ejemplar-" + id;
		}
	}
	
	this.init(libro, id);
	
	this.isAct = function(){
		//return this.estado.hasClass('actualizado');
		return true;
	}
}

Ejemplar.idTemporal = 1;