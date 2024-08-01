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
  $permiso = "historico_ninos";
  $sql = mysqli_query($conexion, "SELECT p.*, d.* FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.nombre = '$permiso'");
  $existe = mysqli_fetch_all($sql);
  if (empty($existe) && $id_user != 1) {
    header('Location: permisos.php');
  }
  $query = mysqli_query($conexion, "SELECT d.*, c.idcliente, c.cedula ,c.nombre, c.apellido FROM detalle_pago d INNER JOIN cliente c ON d.id_cliente = c.idcliente");
  include_once "includes/header.php";

  ?>
  <div class="card">
    <div class="card-header">
      Historico del PDNJ
    </div>
    <div class="col-md-12 text-center mt-5">
      <form action="DescargarReporte_x_fecha_ninos.php" method="post" accept-charset="utf-8">
        <div class="row">
          <div class="col">
            <label class="text-dark font-weight-bold">Desde:</label>
            <input type="date" name="fecha_ingreso" class="form-control" placeholder="Fecha de Inicio" value="<?php echo date("Y-m-d"); if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>"required>
          </div>
          <div class="col">
            <label class="text-dark font-weight-bold">Hasta:</label>
            <input type="date" name="fechaFin" class="form-control" placeholder="Fecha Final" value="<?php  echo date( "Y-m-d",mktime(0, 0, 0, date("m"),date("d")+1, date("Y"))); if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>"required>
          </div>
          <div class="col">

            <span class="btn btn-dark mb-2" id="filtro"><i class="fa-solid fa-filter"></i></span>
            <button type="submit" class="btn btn-success mb-2">Descargar Reporte</button>
          </div>
        </div>
      </form>
    </div>

    <div class="col-md-12 text-center mt-5">
      <span id="loaderFiltro"> </span>
    </div>
    <div class="card-body">
      <div class="table-responsive resultadoFiltro">
        <table class="table table-light" id="tabla">
          <thead class="thead-dark">
            <tr>

              <th>#</th>
              <th>Cedula</th>
              <th>Nombre</th>
              <th>Apellido</th>
              <th>Edad</th>
              <th>Representante</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>Diagnostico</th>
              <th>Peso</th>
              <th>Talla</th>
              <th>Temperatura</th>
              <th>Pediatra</th>
              <th>Fecha de Cit</th>
              <th>Accion</th>
            </tr>
          </thead>
          <tbody>




          </tbody>

        </table>

      </div>
    </div>
  </div>
  <!--  Datatables JS-->



  <script src="//code.jquery.com/jquery-3.5.1.js"></script>
  <!-- DataTables JS library -->
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
          $.post("filtro_ninos.php", { f_ingreso, f_fin }, function (data) {
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
  <script>
    $(document).ready(function () {
      var tabla = $("#tabla").DataTable({

        "createdRow": function (row, data, index) {
          //pintar una celda
          if (data[8] == "Atendido") {
            $('td', row).eq(8).css({
              'background-color': '#07850F',
              'color': 'white',


            });


          }
          if (data[8] == "Reprogramado") {
            $('td', row).eq(8).css({
              'background-color': '#FFD133',
              'color': 'white',


            });


          }
          if (data[8] == "No se Presento") {
            $('td', row).eq(8).css({
              'background-color': '#FA0909',
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

  <?php include_once "includes/footer.php"; ?>
  </body>

</html>