$(document).ready(function () {
	cargarSelect("FiltroEstadosLlamada", false, "Seleccione filtro llamada", null, null, "Estados", true);
});

var urlController = urlBase + "php/controller/ControllerElectores.php";

function refreshCalendar() {
    calendar.refetchEvents();
}

var calendar;
document.addEventListener('DOMContentLoaded', function() {
    var initialLocaleCode = 'es';
    var calendarEl = document.getElementById('calendar');
    var e = new Date,
        i = e.getDate(),
        n = e.getMonth(),
        r = e.getFullYear();

    calendar = new FullCalendar.Calendar(calendarEl, {
        customButtons: {
            reload: {
                text: 'Actualizar',
                click: function() {
                    refreshCalendar();
                }
            }
        },
        headerToolbar: {
            start: 'prev,next today',
            center: 'title',
            end: 'reload dayGridMonth timeGridWeek timeGridDay' // listMonth,timeGridDay,timeGridWeek
        },
        initialView: 'dayGridMonth', // dayGridMonth, dayGridWeek, timeGridDay, listWeek
        dayHeaders: true,
        dayHeaderFormat: {
            weekday: 'long'
        },
        locale: initialLocaleCode,
        buttonIcons: true, // show the prev/next text
        weekNumbers: false,
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: function (fetchInfo, successCallback, failureCallback) {
            $.ajax({
                data: {
                    peticion: "initCalendar",
                    estado: document.getElementById("selectFiltroEstadosLlamada").value
                }, //necesario para enviar archivos
                dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
                url: urlController,
                type: "POST",
                beforeSend: function () {
                    $("#loadertext").text('Cargando registro de llamadas...');
                    $(".loader").fadeIn("slow");
                },
                complete: function () {
                    $(".loader").fadeOut("slow");
                },
                success: function (result) {
                    var estado = result.status;
                    switch (estado) {
                        case 0:
                            var arrayEventos = [];
                            successCallback(
                                arrayEventos.map(function (data) {
                                    return {
                                        id: null
                                    };
                                })
                            );
                            break;
                        case 1:
                            // Busqueda exitosa
                            var arrayEventos = result.eventos;
                            successCallback(
                                arrayEventos.map(function (data) {
                                    return {
                                        id: data["id"],
                                        title: data["title"],
                                        start: data["start"],
                                        color: data["color"],
                                        textColor: '#fff'
                                    };
                                })
                            );
                            break;
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                },
            });
        },
        eventSourceSuccess: function (content, response) {
            // console.log(content);
        },
        eventClick: function (eventClickInfo) {
            var id = eventClickInfo.event.id;
            verInfoEvent(id);
        }
    });
    calendar.render();
})

function verInfoEvent(idRegistro) {
	if ($.isNumeric(idRegistro)) {
		$.ajax({
			data: {
				peticion: "verInfoEvent",
				id: idRegistro,
			}, //datos a enviar a la url
			dataType: "json", //Si no se especifica jQuery automaticamente encontrará el tipo basado en el header del archivo llamado (pero toma mas tiempo en cargar, asi que especificalo)
			url: urlController, //url a donde hacemos la peticion
			type: "POST",
			beforeSend: function () {
				$("#overlayText").text("Consultando información...");
				$(".overlayCargue").fadeIn("slow");
			},
			complete: function () {
				$(".overlayCargue").fadeOut("slow");
			},
			success: function (result) {
				var estado = result.status;
				switch (estado) {
					case 1:
                        var htmlForm = `<div class="container row">
                            ${result.informacion}
                        </div>`;
						Swal.fire({
							position: "top",
							title: `<strong>Información de llamada del elector</strong>`,
							html: `${htmlForm}`,
							showCloseButton: true,
							showConfirmButton: false,
							confirmButtonText: "",
							confirmButtonColor: "",
							cancelButtonText: "Cerrar",
							cancelButtonColor: "#dc3545",
							showCancelButton: true,
							backdrop: true,
							// width: 1300,
							allowOutsideClick: false,
							allowEscapeKey: false
						});
						break;
				}
			},
			error: function (xhr) {
				console.log(xhr);
			},
		});
	}
}