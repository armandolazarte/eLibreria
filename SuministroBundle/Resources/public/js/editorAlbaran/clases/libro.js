function Libro(albaran, isbn){
	this.albaran;
	
	this.id;
	
	this.isbn;
	this.titulo;
	this.editorial;
	
	this.estado;
	
	this.isActualizado = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.init = function(albaran, isbn){
		this.albaran = albaran;
		
		if(isbn === undefined){
			this.id = Libro.idTemporal;
			Libro.idTemporal += 1;
		}
		else{
			this.id = isbn.val();
		}
	}
	
	this.init(albaran, isbn);
}

Libro.idTemporal = 1;