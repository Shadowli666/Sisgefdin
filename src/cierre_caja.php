<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title></title>
  <!-- DataTables CSS library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" />

  <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">


  <?php
  session_start();
  require_once "../conexion.php";
  $id_user = $_SESSION['idUser'];
  $permiso = "cierre_caja";
  $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
  $existe = mysqli_fetch_all($sql);
  if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
  }
 
  include_once "includes/header.php";

  ?>
  <div class="card">
    <div class="card-header">
      Cierre de Caja
    </div>
    <div class="col-md-12 text-center mt-5">
      <form action="DescargarReporte_x_fecha.php" method="post" accept-charset="utf-8">
        <div class="row">
          <div class="col">
            <label class="text-dark font-weight-bold">Desde:</label>
            <input type="date" name="fecha_ingreso" class="form-control" placeholder="Fecha de Inicio" value="<?php echo date("Y-m-d");?>"required>
          </div>
          <div class="col">
            <label class="text-dark font-weight-bold">Hasta:</label>
            <input type="date" name="fechaFin" class="form-control" placeholder="Fecha Final" value="<?php echo date("Y-m-d");?>" required>
          </div>
          <div class="col">

            <span class="btn btn-dark mb-2" id="filtro"><i class="fa-solid fa-filter"></i></span>
            <button type="submit" class="btn btn-danger mb-2">Descargar Reporte</button>
          </div>
        </div>
      </form>
    </div>

    <div class="col-md-12 text-center mt-5">
      <span id="loaderFiltro"> </span>
    </div>
    <div class="card-body">
      <div class="table-responsive resultadoFiltro">
        <table class="table table-light" id="tbla">
          <thead class="thead-dark">
            <tr>

              <th>#</th>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Metodo de Pago</th>
              <th>Referencia</th>
              <th>Bolivares</th>
              <th>Dolares</th>
              <th>Concepto</th>
              <th>Fecha</th>
              <th>Accion</th>
            </tr>
          </thead>
          <tbody>




          </tbody>

        </table>

      </div>
    </div>
  </div>
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>

  <!--  Datatables JS-->
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>
  <!-- SUM()  Datatables-->
  <script src="https://cdn.datatables.net/plug-ins/1.10.20/api/sum().js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
  <script src="assets/js/material.min.js"></script>
  <script src="//code.jquery.com/jquery-3.5.1.js"></script>
  <!-- DataTables JS library -->
  <script type="text/javascript" src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <!-- DataTables JBootstrap -->
  <script type="text/javascript" src="//cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
  <script>
    $(function () {
      setTimeout(function () {
        $('body').addClass('loaded');
      }, 1000);


      //FILTRANDO REGISTROS
      $("#filtro").on("click", function (e) {
        e.preventDefault();

        loaderF(true);

        var f_ingreso = $('input[name=fecha_ingreso]').val();
        var f_fin = $('input[name=fechaFin]').val();
        console.log(f_ingreso + '' + f_fin);

        if (f_ingreso != "" && f_fin != "") {
          $.post("filtro.php", { f_ingreso, f_fin }, function (data) {
            $("#tbla").hide();
            $(".resultadoFiltro").html(data);
            loaderF(false);
          });
        } else {
          $("#loaderFiltro").html('<p style="color:red;  font-weight:bold;">Debe seleccionar ambas fechas</p>');
        }
      });


      function loaderF(statusLoader) {
        console.log(statusLoader);
        if (statusLoader) {
          $("#loaderFiltro").show();
          $("#loaderFiltro").html('<img class="img-fluid" src="assets/img/cargando.svg" style="left:50%; right: 50%; width:50px;">');
        } else {
          $("#loaderFiltro").hide();
        }
      }
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function ($) {
      var tabla = $("#tbla").DataTable({

        "createdRow": function (row, data, index) {
          //pintar una celda
          if (data[9] == 0) {
            $('td', row).eq(6).css({
              'background-color': '#ff5252',
              'color': 'white',
            });


          }
        }
      });

      //1era forma para sum()
      //var tot = tabla.column(5).data().sum();
      //$("#total").text(tot);
    });
  </script>
  </script>
  <?php include_once "includes/footer.php"; ?>