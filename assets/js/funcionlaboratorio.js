document.addEventListener("DOMContentLoaded", function () {
    $('#tbl').DataTable({
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.11/i18n/Spanish.json"
        },
        "order": [
            [0, "desc"]
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
    $("#nom_cliente").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax3.php",
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
            $("#nom_cliente").val(ui.item.label);
            $("#tel_cliente").val(ui.item.telefono);
            $("#dir_cliente").val(ui.item.direccion);
        }
    })
    $("#producto").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax3.php",
                dataType: "json",
                data: {
                    pro: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#id").val(ui.item.id);
            $("#producto").val(ui.item.value);
            $("#precio").val(ui.item.precio);
            $("#cantidad").focus();
        }
    })
    $("#examen").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax3.php",
                dataType: "json",
                data: {
                    qui: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#id").val(ui.item.id);
            $("#examen").val(ui.item.value);
            $("#valor_referencial1").val(ui.item.valor_referencial1);
            $("#valor_referencial2").val(ui.item.valor_referencial2);
        }
    })
    $("#especial").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax3.php",
                dataType: "json",
                data: {
                    espe: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#id").val(ui.item.id);
            $("#especial").val(ui.item.value);
            $("#valor_referencial").val(ui.item.valor_referencial);
            $("#valor_referencialprueba").val(ui.item.valor_referencialprueba);
        }
    })
    $("#inmunoserologia").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "ajax3.php",
                dataType: "json",
                data: {
                    inmu: request.term
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        select: function (event, ui) {
            $("#id").val(ui.item.id);
            $("#inmunoserologia").val(ui.item.value);
            $("#valor_referencialinmu").val(ui.item.valor_referencialinmu);
        }
    })


    $('#btn_resultados').click(function (e) {
        e.preventDefault();
        
        var rows = $('#tblDetalle tr:gt(0)').length; // Ignora la primera fila si es encabezado
        console.log("Número de filas de productos:", rows); // Verificar número de filas
    
        if (rows > 0) {
            var action = 'procesarVenta';
            var id = $('#idcliente').val();
            
            $.ajax({
                url: 'ajax3.php',
                async: true,
                data: {
                    procesarVenta: action,
                    id: id
                },
                success: function (response) {
                    console.log(response);
                    const res = JSON.parse(response);
                    if (response != 'error') {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Resultados Generados',
                            showConfirmButton: false,
                            timer: 2000
                        })
                        setTimeout(() => {
                            console.log(res);
                            resultadoPDF(res.id_cliente, res.id_venta);
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
                    console.error(error);
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
    if (document.getElementById("detalle_hematologia")) {
        listarHema();
    }
    if (document.getElementById("detalle_leuco")) {
        listarLeuco();
    }
    if (document.getElementById("detalle_quimica")) {
        listarQuimi();
    }  
    if (document.getElementById("detalle_orina")) {
        listarOrina();
    }
    if (document.getElementById("detalle_orinamisc")) {
        listarOrinamisc();
    }
    if (document.getElementById("detalle_heces")) {
        listarHeces();
    }
    if (document.getElementById("detalle_hecesmisc")) {
        listarHecesmisc();
    }
    if (document.getElementById("detalle_reticulocitos")) {
        listarReticulocitos();
    }
    if (document.getElementById("detalle_tiempos")) {
        listarTiempos();
    
    }
    if (document.getElementById("detalle_prueba")) {
        listarPrueba();
    }
    if (document.getElementById("detalle_inmunoserologia")) {
        listarInmunoserologia();
    }
    if (document.getElementById("detalle_gruposanguineo")) {
        listarGrupo();
    }
});

function calcularPrecio(e) {
    e.preventDefault();
    const cant = $("#cantidad").val();
    const precio = $('#precio').val();
    const total = cant * precio;
    $('#sub_total').val(total);
    if (e.which == 13) {
        if (cant > 0 && cant != '') {
            const id = $('#id').val();
            registrarDetalle(e, id, cant, precio);
            $('#producto').focus();
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
            url: "ajax3.php",
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
}

function listar() {
    let html = '';
    let detalle = 'detalle';
    $.ajax({
        url: "ajax3.php",
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
                <td width="100">
                <input class="form-control" placeholder="Desc" type="number" onkeyup="calcularDescuento(event, ${row['id']})">
                </td>
                <td>${row['descuento']}</td>
                <td>${row['precio_venta']}</td>
                <td>${row['sub_total']}</td>
                <td><button class="btn btn-danger" type="button" onclick="deleteDetalle(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_venta").innerHTML = html;
            calcular();
        }
    });
}
function listarHema() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleHema: 'detalleHema'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['hemoglobina']}</td>
                <td>${row['hematocritos']}</td>
                <td>${row['cuentas_blancas']}</td>
                <td>${row['plaquetas']}</td>
                <td>${row['vsg']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteHema(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_hematologia").innerHTML = html;
           
        }
    });
}
function listarLeuco() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleLeuco: 'detalleLeuco'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['seg']}</td>
                <td>${row['linf']}</td>
                <td>${row['eosin']}</td>
                <td>${row['monoc']}</td>
                <td>${row['basof']}</td>
                <td>${row['otros']}</td>
                <td>${row['total']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteLeu(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_leuco").innerHTML = html;
           
        }
    });
}
function listarQuimi() {
    let html = '';

    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleQuimica: 'detalleQuimica'
        },
        success: function (response) {
            if (response.error) {
                console.error(response.error);
                return;
            }
            response.forEach(row => {
                html += `<tr>
                    <td>${row['id']}</td>
                    <td>${row['examen']}</td>
                    <td>${row['valor_unidad']}</td>
                    <td>${row['valor_referencial1']}</td>
                    <td>${row['valor_referencial2']}</td>
                    <td><button class="btn btn-danger" type="button" onclick="deleteQuimi(${row['id']})">
                    <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_quimica").innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX: ", error);
        }
    });
}
function listarOrina() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleOrina: 'detalleOrina'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['aspecto']}</td>
                <td>${row['densidad']}</td>
                <td>${row['ph']}</td>
                <td>${row['olor']}</td>
                <td>${row['color']}</td>
                <td>${row['nitritos']}</td>
                <td>${row['proteinas']}</td>
                <td>${row['glucosa']}</td>
                <td>${row['cetonas']}</td>
                <td>${row['urobilinogeno']}</td>
                <td>${row['bilirrubina']}</td>
                <td>${row['hemoglobina']}</td>
                <td>${row['pigmen_biliares']}</td>
                <td>${row['sales_biliares']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteOrina(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_orina").innerHTML = html;
           
        }
    });
}
function listarOrinamisc() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleExamisc: 'detalleExamisc'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['celulas_ep_planas']}</td>
                <td>${row['bacterias']}</td>
                <td>${row['leucocitos']}</td>
                <td>${row['hematies']}</td>
                <td>${row['mucina']}</td>
                <td>${row['celulas_renales']}</td>
                <td>${row['cristales']}</td>
                <td>${row['otros']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteMisc(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_orinamisc").innerHTML = html;
           
        }
    });
}
function listarHeces() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleHeces: 'detalleHeces'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['aspecto']}</td>
                <td>${row['color']}</td>
                <td>${row['olor']}</td>
                <td>${row['consistencia']}</td>
                <td>${row['reaccion']}</td>
                <td>${row['moco']}</td>
                <td>${row['sangre']}</td>
                <td>${row['ra']}</td>
                <td>${row['ph']}</td>
                <td>${row['azucares']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteHeces(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_heces").innerHTML = html;
           
        }
    });
}
function listarHecesmisc() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleHecesmisc: 'detalleHecesmisc'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['protozoarios']}</td>
                <td>${row['helmintos']}</td>
                <td>${row['otros']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteHecesmisc(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_hecesmisc").innerHTML = html;
           
        }
    });
}
function listarReticulocitos() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleReticulocitos: 'detalleReticulocitos'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['reticulocitos']}</td>
                <td>${row['valor_unidad']}</td>
                <td>${row['valor_referencial']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteReticulocitos(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_reticulocitos").innerHTML = html;
           
        }
    });
}
function listarTiempos() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleTiempos: 'detalleTiempos'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['tp']}</td>
                <td>${row['tpt']}</td>
                <td>${row['inr']}</td>
                <td>${row['fibrinogeno']}</td>
				<td><button class="btn btn-danger" type="button" onclick="deleteTiempos(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_tiempos").innerHTML = html;
           
        }
    });
}
function listarPrueba() {
    let html = '';

    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detallePruebaespecial: 'detallePruebaespecial'
        },
        success: function (response) {
            if (response.error) {
                console.error(response.error);
                return;
            }
            response.forEach(row => {
                html += `<tr>
                    <td>${row['id']}</td>
                    <td>${row['examen']}</td>
                    <td>${row['valor_unidad']}</td>
                    <td>${row['valor_referencial']}</td>
                    <td>${row['valor_referencial2']}</td>
                    <td><button class="btn btn-danger" type="button" onclick="deletePrueba(${row['id']})">
                    <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_prueba").innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX: ", error);
        }
    });
}
function listarInmunoserologia() {
    let html = '';

    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleInmunoserologia: 'detalleInmunoserologia'
        },
        success: function (response) {
            if (response.error) {
                console.error(response.error);
                return;
            }
            response.forEach(row => {
                html += `<tr>
                    <td>${row['id']}</td>
                    <td>${row['examen']}</td>
                    <td>${row['valor_unidad']}</td>
                    <td>${row['valor_referencial']}</td>
                    <td><button class="btn btn-danger" type="button" onclick="deleteInmuno(${row['id']})">
                    <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_inmunoserologia").innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX: ", error);
        }
    });
}
function listarGrupo() {
    let html = '';
    $.ajax({
        url: "ajax3.php",
        dataType: "json",
        data: {
            detalleGruposanguineo: 'detalleGruposanguineo'
        },
        success: function (response) {

            response.forEach(row => {
                html += `<tr>
                <td>${row['id']}</td>
                <td>${row['gruposanguineo']}</td>
                <td>${row['factor']}</td>
            	<td><button class="btn btn-danger" type="button" onclick="deleteTiempos(${row['id']})">
                <i class="fas fa-trash-alt"></i></button></td>
                </tr>`;
            });
            document.querySelector("#detalle_gruposanguineo").innerHTML = html;
           
        }
    });
}
function registrarDetalle(e, id, cant, precio) {
    if (document.getElementById('producto').value != '') {
        if (id != null) {
            let action = 'regDetalle';
            $.ajax({
                url: "ajax3.php",
                type: 'POST',
                dataType: "json",
                data: {
                    id: id,
                    cant: cant,
                    regDetalle: action,
                    precio: precio
                },
                success: function (response) {

                    if (response == 'registrado') {
                        $('#cantidad').val('');
                        $('#precio').val('');
                        $("#producto").val('');
                        $("#sub_total").val('');
                        $("#producto").focus();
                        listar();
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Producto Ingresado',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    } else if (response == 'actualizado') {
                        $('#cantidad').val('');
                        $('#precio').val('');
                        $("#producto").val('');
                        $("#producto").focus();
                        listar();
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Producto Actualizado',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Error al ingresar el producto',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                }
            });
        }
    }
}

function deleteDetalle(id) {
    let detalle = 'Eliminar'
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_detalle: detalle
        },
        success: function (response) {

            if (response == 'restado') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Producto Descontado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#producto").value = '';
                document.querySelector("#producto").focus();
                listar();
            } else if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Producto Eliminado',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.querySelector("#producto").value = '';
                document.querySelector("#producto").focus();
                listar();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar el producto',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function deleteHema(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_hema: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarHema();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function deleteLeu(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_leu: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarLeuco();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function deleteQuimi(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_quimi: true
        },
        success: function (response) {
            if (response.trim() == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarQuimi();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    text: response,
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}
function deleteOrina(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_orina: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarOrina();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}function deleteMisc(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_examisc: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarOrinamisc();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}function deleteHeces(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_heces: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarHeces();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });

}function deleteHecesmisc(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_hecesmisc: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarHecesmisc();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}function deleteReticulocitos(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_reticulocitos: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarReticulocitos();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}function deleteTiempos(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_tiempos: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarTiempos();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });

}function deletePrueba(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_prueba: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarPrueba();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });
}function deleteGrupo(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_gruposanguineo: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarGrupo();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        }
    });

}function deleteInmuno(id) {
    $.ajax({
        url: "ajax3.php",
        data: {
            id: id,
            delete_inmunoserologia: true
        },
        success: function (response) {

             if (response == 'ok') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Eliminados',
                    showConfirmButton: false,
                    timer: 2000
                })
                listarInmunoserologia();
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al eliminar los Resultados',
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

    // recorremos cada una de las filas
    filas.forEach(function (e) {

        // obtenemos las columnas de cada fila
        var columnas = e.querySelectorAll("td");

        // obtenemos los valores de la cantidad y importe
        var importe = parseFloat(columnas[6].textContent);

        total += importe;
    });

    // mostramos la suma total
    var filas = document.querySelectorAll("#tblDetalle tfoot tr td");
    filas[1].textContent = total.toFixed(2);
}
function  resultadoPDF(cliente, id_venta) {
    url = 'pdf/documento.php?cl='  + cliente + '&v=' + id_venta;
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
            url: "ajax3.php",
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
        url: 'ajax3.php',
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
        url: 'ajax3.php',
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
        url: 'ajax3.php',
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
            $('#cantidad').val(datos.existencia);
            $('#id').val(datos.codproducto);
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
function crearHema(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            hemoglobina:e.target.hemoglobina.value,
            hematocritos:e.target.hematocritos.value,
            cuentas_blancas:e.target.cuentas_blancas.value || 0,
            plaquetas:e.target.plaquetas.value || 0,
            vsg:e.target.vsg.value,
            crearHema:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#hemoglobina').val('');
                $('#hematocritos').val('');
                $("#cuentas_blancas").val('');
                $("#plaquetas").val('');
                $("#vsg").val('');
                listarHema();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#hemoglobina').val('');
                $('#hematocritos').val('');
                $('#cuentas_blancas').val('')
                $("#plaquetas").val('');
                $("#vsg").focus();
                listarHema();
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
function crearQuimi(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            id:e.target.id.value,
            examen:e.target.examen.value,
            valor_unidad:e.target.valor_unidad.value,
            valor_referencial1:e.target.valor_referencial1.value || 0,
            valor_referencial2:e.target.valor_referencial2.value || 0,
            crearQuimi:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#examen').val('');
                $('#valor_unidad').val('');
                $("#valor_referencial1").val('')
                $("#valor_referencial2").focus();
                listarQuimi();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#examen').val('');
                $('#valor_unidad').val('');
                $("#valor_referencial1").val('')
                $("#valor_referencial2").focus();
                listarQuimi();
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

function crearOrina(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            aspecto:e.target.aspecto.value,
            densidad:e.target.densidad.value,
            ph:e.target.ph.value,
            olor:e.target.olor.value,
            color:e.target.color.value || 0,
            nitritos:e.target.nitritos.value || 0,
            proteinas:e.target.proteinas.value,
            glucosa:e.target.glucosa.value,
            cetonas:e.target.cetonas.value,
            urobilinogeno:e.target.urobilinogeno.value,
            bilirrubina:e.target.bilirrubina.value,
            hemoglobina:e.target.hemoglobina.value,
            pigmen_biliares:e.target.pigmen_biliares.value,
            sales_biliares:e.target.sales_biliares.value,
            crearOrina:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#aspecto').val('');
                $('#densidad').val('');
                $("#ph").val('');
                $("#olor").val('');
                $("#color").val('');
                $("#nitritos").val('');
                $("#proteinas").val('');
                $("#glucosa").val('');
                $("#cetonas").val('');
                $("#urobilinogeno").val('');
                $("#bilirrubina").val('');
                $("#hemoglobina").val('');
                $("#pigmen_biliares").val('');
                $("#sales_biliares").val('');
                listarOrina();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#aspecto').val('');
                $('#densidad').val('');
                $("#ph").val('');
                $("#olor").val('');
                $("#color").val('');
                $("#nitritos").val('');
                $("#proteinas").val('');
                $("#glucosa").val('');
                $("#cetonas").val('');
                $("#urobilinogeno").val('');
                $("#bilirrubina").val('');
                $("#hemoglobina").val('');
                $("#pigmen_biliares").val('');
                $("#sales_biliares").val('');
                listarOrina();
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
function crearExamisc(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            celulas_ep_planas:e.target.celulas_ep_planas.value,
            bacterias:e.target.bacterias.value,
            leucocitos:e.target.leucocitos.value,
            hematies:e.target.hematies.value,
            mucina:e.target.mucina.value || 0,
            celulas_renales:e.target.celulas_renales.value || 0,
            cristales:e.target.cristales.value,
            otros:e.target.otros.value,
            crearExamisc:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#celulas_ep_planas').val('');
                $('#bacterias').val('');
                $("#leucocitos").val('');
                $("#hematies").val('');
                $("#mucina").val('');
                $("#celulas_renales").val('');
                $("#cristales").val('');
                $("#otros").val('');
                listarOrinamisc();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#celulas_ep_planas').val('');
                $('#bacterias').val('');
                $("#leucocitos").val('');
                $("#hematies").val('');
                $("#mucina").val('');
                $("#celulas_renales").val('');
                $("#cristales").val('');
                $("#otros").val('');
                listarOrinamisc();
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
function crearHecesmisc(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            protozoarios:e.target.protozoarios.value,
            helmintos:e.target.helmintos.value,
            otros:e.target.otros.value,
            crearHecesmisc:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#protozoarios').val('');
                $('#helmintos').val('');
                $("#otros").val('');
                listarHecesmisc();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#protozoarios').val('');
                $('#helmintos').val('');
                $("#otros").val('');
                listarHecesmisc();
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
function crearHeces(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            aspecto:e.target.aspecto.value,
            color:e.target.color.value,
            olor:e.target.olor.value,
            consistencia:e.target.consistencia.value,
            reaccion:e.target.reaccion.value,
            moco:e.target.moco.value,
            sangre:e.target.sangre.value,
            ra:e.target.ra.value,
            ph:e.target.ph.value,
            azucares:e.target.azucares.value,
            crearHeces:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#aspecto').val('');
                $('#color').val('');
                $("#olor").val('');
                $("#consistencia").val('');
                $("#reaccion").val('');
                $("#moco").val('');
                $("#sangre").val('');
                $("#ra").val('');
                $("#ph").val('');
                $("#azucares").val('');
                listarHeces();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#aspecto').val('');
                $('#color').val('');
                $("#olor").val('');
                $("#consistencia").val('');
                $("#reaccion").val('');
                $("#moco").val('');
                $("#sangre").val('');
                $("#ra").val('');
                $("#ph").val('');
                $("#azucares").val('');
                listarHeces();
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

function crearReticulocitos(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            reticulocitos:e.target.reticulocitos.value,
            valor_unidad:e.target.valor_unidad.value,
            valor_referencial:e.target.valor_referencial.value,
            crearReticulocitos:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#reticulocitos').val('');
                $('#valor_unidad').val('');
                $("#valor_referencial").val('');
                listarReticulocitos();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#reticulocitos').val('');
                $('#valor_unidad').val('');
                $("#valor_referencial").val('');
                listarReticulocitos();
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
function crearTiempos(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            tp:e.target.tp.value,
            tpt:e.target.tpt.value,
            inr:e.target.inr.value,
            fibrinogeno:e.target.fibrinogeno.value,
            crearTiempos:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#tp').val('');
                $('#tpt').val('');
                $("#inr").val('');
                $("#fibrinogeno").val('');
                listarTiempos();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#tp').val('');
                $('#tpt').val('');
                $("#inr").val('');
                $("#fibrinogeno").val('');
                listarTiempos();
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
function crearPrueba(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            id:e.target.id.value,
            especial:e.target.especial.value,
            valor_unidad:e.target.valor_unidad.value,
            valor_referencial:e.target.valor_referencial.value || 0,
            valor_referencialprueba:e.target.valor_referencialprueba.value || 0,
            crearPrueba:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#especial').val('');
                $('#valor_unidad').val('');
                $("#valor_referencial").val('')
                $("#valor_referencialprueba").focus();
                listarPrueba();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#examen').val('');
                $('#valor_unidad').val('');
                $("#valor_referencial").val('')
                $("#valor_referencialprueba").focus();
                listarPrueba();
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
function crearInmunoserologia(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            id:e.target.id.value,
            inmunoserologia:e.target.inmunoserologia.value,
            valor_unidad:e.target.valor_unidad.value,
            valor_referencialinmu:e.target.valor_referencialinmu.value || 0,
            crearInmunoserologia:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#inmunoserologia').val('');
                $('#valor_unidad').val('');
                $("#valor_referencialinmu").focus();
                listarInmunoserologia();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#examen').val('');
                $('#valor_unidad').val('');
                $("#valor_referencialinmu").focus();
                listarInmunoserologia();
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
function crearGruposanguineo(e){
    e.preventDefault();
    $.ajax({
        url: "ajax3.php",
        type: 'POST',
        dataType: "json",
        data: {
            grupo:e.target.grupo.value,
            factor:e.target.factor.value,
            crearGruposanguineo:true
        },
        success: function (response) {
            if (response == 'registrado') {
                $('#grupo').val('');
                $('#factor').val('');
                listarGrupo();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Resultados Ingresados',
                    showConfirmButton: false,
                    timer: 2000
                })
            } else {
                $('#grupo').val('');
                $('#factor').val('');
                listarGrupo();
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


