
$(document).ready(function () {
    $(".select").select2({
        language: "es",
        tags: false,
        allowClear: false,
        width: "100%",
        placeholder: "Seleccione",
        multiple: false,
    });
})

// Funciones para cargar los selects
function cargarSelect(idSelect, multiple, placeholder, id = null, modal = null, peticion = null, all = false, tagify = false, nameVariable = 'arrayTagify') {
	var dropdownParent = modal == null ? $(document.body) : $("#" + modal + ">.modal-dialog>.modal-content");
	peticion = peticion == null ? idSelect : peticion;
	$.ajax({
		data: { select: "select" + peticion, id: id, all: all }, //datos a enviar a la url
		dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
		url: urlBase + "php/controller/ControllerSelect.php", //url a donde hacemos la peticion
		type: "POST",
		beforeSend: function () {
			// Code
		},
		success: function (result) {
			var estado = result.status;
			switch (estado) {
				case "0":
					//--------------------------------
                    if( tagify ){
                        window[nameVariable] = [];
                    }else{
                        $("#select" + idSelect).html("");
                    }
					break;

				case "1":
					//--------------------------------
                    if( tagify ){
                        window[nameVariable] = result.datos;
                    }else{
                        $("#select" + idSelect).html(result.html);
                        $("#select" + idSelect).select2({
                            language: "es",
                            tags: false,
                            allowClear: false,
                            width: "100%",
                            placeholder: placeholder,
                            multiple: multiple,
                            dropdownParent: dropdownParent,
                        });
                    }
					break;
			}
		},
		complete: function () {
			// Code
		},
		error: function (xhr) {
			console.log(xhr);
		},
	});
}

$(document).on("select2:open", () => {
	document.querySelector(".select2-search__field").focus();
});
