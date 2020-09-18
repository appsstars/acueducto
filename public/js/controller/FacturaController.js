function getCliente(id, id_medidor){
	$('input[name="id_cliente"]').val(id);
	$('input[name="id_medidor"]').val(id_medidor);
	//alert(id);
	open_modal();
}

function open_modal(){
	$("#form-create").modal('show');
}