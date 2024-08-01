document.addEventListener("DOMContentLoaded", function () {
    $('#tbl').DataTable({
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "order": [
            [0, "async"]
        ]
    });
	
    $(".confirmar").submit(function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Esta seguro de eliminar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI, Eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        })
    })
 $("#ced_cliente").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax2.php",
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#idcliente").val(ui.item.id);
            $("#ced_cliente").val(ui.item.label);
            $("#nom_cliente").val(ui.item.nombre);
            $("#segun_nombre_cliente").val(ui.item.segun_nombre);
            $("#ape_cliente").val(ui.item.apellido);
            $("#segun_apellido_cliente").val(ui.item.segun_apellido);
            $("#eda_cliente").val(ui.item.edad);
            $("#fecha_nac_cliente").val(ui.item.fecha_nac);
            $("#gen_cliente").val(ui.item.genero);
            $("#tel_cliente").val(ui.item.telefono);
            $("#dir_cliente").val(ui.item.direccion);
            $("#par_cliente").val(ui.item.parroquia);
            $("#mun_cliente").val(ui.item.municipio);
            $("#est_cliente").val(ui.item.estado);
        }
    })
    $("#servicio").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax2.php",
                dataType: "json",
                data: {
                    s: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#id").val(ui.item.id);
            $("#sub_totalbs").val(ui.item.cotizacion);
            $("#servicio").val(ui.item.value);
            $("#precio").val(ui.item.precio);
            $("#cantidad").focus();
        }
    })
$("#metodo_pago").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax2.php",
                dataType: "json",
                data: {
                    m: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#id").val(ui.item.id);
            $("#metodo_pago").val(ui.item.metodo_pago);

        }
    })
$("#serv").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax2.php",
                dataType: "json",
                data: {
                    v: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#id").val(ui.item.id);
            $("#serv").val(ui.item.value);
            $("#precio").val(ui.item.precio);
			}
    })
    $('#btn_generar').click(function (e) {
        e.preventDefault();
        var rows = $('#tblDetalle tr').length;

        if (rows > 2) {
            var action = 'procesarVenta';
            var id = $('#idcliente').val();
            $.ajax({
                url: 'ajax2.php',
                async: true,
                data: {
                    procesarVenta: action,
                    id: id
                },
                success: function (response) {

                    const res = JSON.parse(response);
                    if (response != 'error') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Venta Generada',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(() => {
                            generarPDF(res.id_cliente, res.id_venta);
                            crearPDF(res.id_cliente, res.id_pago);
                            location.reload();
                        }, 300);
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error al generar la venta',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                },
                error: function (error) {

                }
            });
        } else {
            Swal.fire({
                position: 'top-end',
                icon: 'warning',
                title: 'No hay producto para generar la venta',
                showConfirmButton: false,
                timer: 2000
            })
        }
    });

    if (document.getElementById("detalle_venta")) {
        listar();
    }

    if (document.getElementById("detalle_pago")) {
        listarPagos();
    }
})

var cotiza=0

function calcularPrecio(e) {
    e.preventDefault();
    cant = $("#cantidad").val();
    precio = $('#precio').val();
    if (cotiza ==0) {
    cotiza = $('#sub_totalbs').val();
    }
    if (cant > 0 && cant !=null) {
        let total = cant * precio;
        let totalbs = cant * precio * cotiza;
        $('#sub_totalbs').val(totalbs);
        $('#sub_total').val(total);
        $('#sub_total_diferido').val(total);
    } else{
        
        $('#sub_totalbs').val(0);
        $('#sub_total').val(0);
        $('#sub_total_diferido').val(0);
    }

    if (e.which == 13) {
        totalbs = $('#sub_totalbs').val();
        if (cant > 0 && cant != '') {
            const id = $('#id').val();
            registrarDetalle(e, id, cant, precio, totalbs, cotiza);
            $('#servicio').focus();
        } else {
            $('#cantidad').focus();
            return false;
        }
    }

}





function calcularDescuento(e, id) {
    if (e.which == 13) {
        let descuento = 'descuento';
        $.ajax({
            url: "ajax2.php",
            type: 'GET',
            dataType: "json",
            data: {
                id: id,
                desc: e.target.value,
                descuento: descuento
            },
            success: function (response) {

                if (response.mensaje == 'descontado') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Descuento Aplicado',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    listar();
                } else {}
            }
        });
    }



function listar() {
    let html = '';
    let detalle = 'detalle';
    $.ajax({
        url: "ajax2.php",
        dataType: "json",
        data: {
            detalle: detalle
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['descripcion']}</td>
                <td>${row['cantidad']}</td>
                <td>${row['cotizacion']}</td>
                <td width="100">
                <input class="form-control"  placeholder="Desc" type="number" onkeyup="calcularDescuento(event, ${row['id']})">
                </td>
                <td>${row['sub_total']}</td>
                <td>${row['sub_totalbs']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteDetalle(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_venta").innerHTML = html;
            calcular();
        }
    });
  }
}
function listarPagos() {
    let html = '';
    $.ajax({
        url: "ajax2.php",
        dataType: "json",
        data: {
            detallePago: 'detallePago'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['metodo_pago']}</td>
                <td>${row['referencia']}</td>
                <td>${row['bolivares']}</td>
                <td>${row['dolares']}</td>
                <td>${row['obser']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deletePago(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_pago").innerHTML = html;
            calcular();
        }
    });
}

function registrarDetalle(_e, id, cant, precio, preciobs, taza) {
    if (document.getElementById('servicio').value != '' && document.getElementById('ced_cliente').value != '') {
        if (id != null) {
            let action = 'regDetalle';
            $.ajax({
                url: "ajax2.php",
                type: 'POST',
                dataType: "json",
                data: {
                    id: id,
                    cant: cant,
                    regDetalle: action,
                    precio: precio,
                    sub_totalbs: preciobs,
                    taza
                },
                success: function (response) {

                    if (response == 'registrado') {
                        $('#cantidad').val('');
                        $('#precio').val('');
                        $("#servicio").val('');
                        $("#sub_total").val('');
                        $("#sub_totalbs").val('');
                        $("#servicio").focus();
                        listar();
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Servicio Ingresado',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    } else if (response == 'actualizado') {
                        $('#cantidad').val('');
                        $('#precio').val('');
                        $('#sub_totalbs').val('');
                        $("#servicio").val('');
                        $("#servicio").focus();
                        listar();
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Servicio Actualizado',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    } else {
                        $('#id').val('');
                        $('#cantidad').val('');
                        $('#precio').val('');
                        $('#sub_totalbs').val('')
                        $("#servicio").val('');
                        $("#servicio").focus();
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: response,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                }
            });
        }
    } else {    
        if (document.getElementById('ced_cliente').value == '' ) {
            Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'No se puede continuar con el campo cedula vacio',
            showConfirmButton: false,
            timer: 2000
        
        });
        
    }}
}

function deleteDetalle(id) {
    let detalle = 'Eliminar'
    $.ajax({
        url: "ajax2.php",
        data: {
            id: id,
            delete_detalle: detalle
        },
        success: function (response) {

            if (response == 'restado') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Servicio Descontado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#servicio").value = '';
                document.querySelector("#servicio").focus();
                listar();
            } else if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Servicio Eliminado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#servicio").value = '';
                document.querySelector("#servicio").focus();
                listar();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar el Servicio',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}

function deletePago(id) {
    $.ajax({
        url: "ajax2.php",
        data: {
            id: id,
            delete_pago: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pago Eliminado',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarPagos();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar el Pago',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}

function calcular() {
    // obtenemos todas las filas del tbody
    var filas = document.querySelectorAll("#tblDetalle tbody tr");

    var total = 0;
    var totalBs = 0;

    // recorremos cada una de las filas
    filas.forEach(function (e) {

        // obtenemos las columnas de cada fila
        var columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        var importe = parseFloat(columnas[5].textContent);
        var importeBs = parseFloat(columnas[6].textContent);
        total += importe;
        totalBs += importeBs
    });

    // mostramos la suma total
    var filas = document.querySelectorAll("#tblDetalle tfoot tr td");
    filas[0].textContent = `Total en $: ${total.toFixed(2)} / Total en Bs: ${totalBs.toFixed(2)}`
}
 
 function calcularPreciodiferido(e) {
    e.preventDefault();
    const total_pagar = $("#total_pagar").val();
    const bolivares = $('#bolivares').val();
    const total = total_pagar - bolivares;
    $('#sub_total_diferido').val(total);
   
    
}
 
function generarPDF(cliente, id_venta) {
    url = 'pdf/generar.php?cl=' + cliente + '&v=' + id_venta;
    window.open(url, '_blank');
}
if (document.getElementById("stockMinimo")) {
    const action = "sales";
    $.ajax({
        url: 'chart.php',
        type: 'POST',
        data: {
            action
        },
        async: true,
        success: function (response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]['descripcion']);
                    cantidad.push(data[i]['existencia']);
                }
                var ctx = document.getElementById("stockMinimo");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: nombre,
                        datasets: [{
                            data: cantidad,
                            backgroundColor: ['#024A86', '#E7D40A', '#581845', '#C82A54', '#EF280F', '#8C4966', '#FF689D', '#E36B2C', '#69C36D', '#23BAC4'],
                        }],
                    },
                });
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}
function crearPDF(cliente, id_pago) {
    url = 'pdf/factura.php?cl=' + cliente + '&v=' + id_pago;
    window.open(url, '_blank');
}
if (document.getElementById("stockMinimo")) {
    const action = "sales";
    $.ajax({
        url: 'chart.php',
        type: 'POST',
        data: {
            action
        },
        async: true,
        success: function (response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]['descripcion']);
                    cantidad.push(data[i]['existencia']);
                }
                var ctx = document.getElementById("stockMinimo");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: nombre,
                        datasets: [{
                            data: cantidad,
                            backgroundColor: ['#024A86', '#E7D40A', '#581845', '#C82A54', '#EF280F', '#8C4966', '#FF689D', '#E36B2C', '#69C36D', '#23BAC4'],
                        }],
                    },
                });
            }
        },
        error: function (error) {
            console.log(error);
        }
    });
}
if (document.getElementById("ProductosVendidos")) {
    const action = "polarChart";
    $.ajax({
        url: 'chart.php',
        type: 'POST',
        async: true,
        data: {
            action
        },
        success: function (response) {
            if (response != 0) {
                var data = JSON.parse(response);
                var nombre = [];
                var cantidad = [];
                for (var i = 0; i < data.length; i++) {
                    nombre.push(data[i]['descripcion']);
                    cantidad.push(data[i]['cantidad']);
                }
                var ctx = document.getElementById("ProductosVendidos");
                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: nombre,
                        datasets: [{
                            data: cantidad,
                            backgroundColor: ['#C82A54', '#EF280F', '#23BAC4', '#8C4966', '#FF689D', '#E7D40A', '#E36B2C', '#69C36D', '#581845', '#024A86'],
                        }],
                    },
                });
            }
        },
        error: function (error) {
            console.log(error);

        }
    });
}

function btnCambiar(e) {
    e.preventDefault();
    const actual = document.getElementById('actual').value;
    const nueva = document.getElementById('nueva').value;
    if (actual == "" || nueva == "") {
        Swal.fire({
            position: 'top-end',
            icon: 'error',
            title: 'Los campos estan vacios',
            showConfirmButton: false,
            timer: 2000
        })
    } else {
        const cambio = 'pass';
        $.ajax({
            url: "ajax.php",
            type: 'POST',
            data: {
                actual: actual,
                nueva: nueva,
                cambio: cambio
            },
            success: function (response) {
                if (response == 'ok') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Contraseña modificado',
                        showConfirmButton: false,
                        timer: 2000
                    })
                    document.querySelector('#frmPass').reset();
                    $("#nuevo_pass").modal("hide");
                } else if (response == 'dif') {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'La contraseña actual incorrecta',
                        showConfirmButton: false,
                        timer: 2000
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error al modificar la contraseña',
                        showConfirmButton: false,
                        timer: 2000
                    })
                }
            }
        });
    }
}

function editarCliente(id) {
    const action = "editarCliente";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarCliente: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nombre').val(datos.nombre);
            $('#telefono').val(datos.telefono);
            $('#direccion').val(datos.direccion);
            $('#id').val(datos.idcliente);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}

function editarUsuario(id) {
    const action = "editarUsuario";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarUsuario: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nombre').val(datos.nombre);
            $('#usuario').val(datos.usuario);
            $('#correo').val(datos.correo);
            $('#id').val(datos.idusuario);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}

function editarProducto(id) {
    const action = "editarProducto";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarProducto: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#codigo').val(datos.codigo);
            $('#producto').val(datos.descripcion);
            $('#precio').val(datos.precio);
            $('#id').val(datos.codproducto);
            $('#tipo').val(datos.id_tipo);
            $('#presentacion').val(datos.id_presentacion);
            $('#laboratorio').val(datos.id_lab);
            $('#vencimiento').val(datos.vencimiento);
            $('#cantidad').val(datos.existencia);
            if (datos.vencimiento != '0000-00-00') {
                $("#accion").prop("checked", true);
            }else{
                $("#accion").prop("checked", false);
            }
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}

function limpiar() {
    $('#formulario')[0].reset();
    $('#id').val('');
    $('#btnAccion').val('Registrar');
}
function editarTipo(id) {
    const action = "editarTipo";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarTipo: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nombre').val(datos.tipo);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarTasa(id) {
    const action = "editarTasa";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarTasa: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#cotizacion').val(datos.cotizacion);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarPresent(id) {
    const action = "editarPresent";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarPresent: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#nombre').val(datos.nombre);
            $('#nombre_corto').val(datos.nombre_corto);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarLab(id) {
    const action = "editarLab";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarLab: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#laboratorio').val(datos.laboratorio);
            $('#direccion').val(datos.direccion);
            $('#id').val(datos.id);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function editarServicio(id) {
    const action = "editarServicio";
    $.ajax({
        url: 'ajax.php',
        type: 'GET',
        async: true,
        data: {
            editarServicio: action,
            id: id
        },
        success: function (response) {
            const datos = JSON.parse(response);
            $('#descripcion').val(datos.descripcion);
            $('#precio').val(datos.precio);
            $('#id').val(datos.idservicios);
            $('#btnAccion').val('Modificar');
        },
        error: function (error) {
            console.log(error);

        }
    });
}
function focusNextElement (e) {
    if (e.which == 13) {
    $("#servicio").focus();
    }};

// function msgtoconsole ($data) { 
//     $output = $data
//     if (is_array($output))
//         $output= implode('.', $output);
    
//     echo "<script>console.log( 'error de depuracion: " .$output. "' ) ; </script>";



// };

function crearPago(e){
    e.preventDefault();
    $.ajax({
        url: "ajax2.php",
        type: 'POST',
        dataType: "json",
        data: {
            metodo_pago:e.target.metodo_pago.value,
            referencia:e.target.referencia.value,
            bolivares:e.target.bolivares.value || 0,
            dolares:e.target.dolares.value || 0,
            obser:e.target.obser.value,
            crearPago:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#metodo_pago').val('');
                $('#referencia').val('');
                $("#bolivares").val('');
                $("#dolares").val('');
                $("#obser").val('');
                listarPagos();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Pago Ingresado',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#metodo_pago').val('');
                $('#referencia').val('');
                $('#bolivares').val('')
                $("#dolares").val('');
                $("#obser").focus();
                listarPagos();
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: response,
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}