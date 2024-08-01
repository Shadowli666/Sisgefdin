<?php
if (empty($_SESSION['active'])) {
    header('Location: .../');

}

?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Panel de Administración</title>
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="../assets/css/material-dashboard.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    
    <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css">
    <script src="../assets/js/all.min.js" crossorigin="anonymous"></script>
    
     
</head>
<body>
    <div class="wrapper ">
        <div class="sidebar" data-color="purple" data-background-color="blue" data-image="../assets/img/sidebar.jpg">
            <div class="logo"><a href="./index.php" class="simple-text logo-normal">
                <p></p>
                     
                </a></div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="clientes.php">
                            <i class=" fas fa-users mr-2 fa-2x"></i>
                            <p> Pacientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="medicamentos.php">
                            <i class=" fas fa-search mr-2 fa-2x"></i>Consulta de Medicamentos
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link d-flex" href="consulta_cita.php">
                            <i class=" fas fa-address-book mr-2 fa-2x"></i>Consulta de Cita
                        </a>
                    </li>
                    <details>
                    <summary><i class="fas fa-tablets mr-2 fa-2x"></i>Medicamentos</summary>
                    
                     <li class="nav-item">
                        <a class="nav-link  d-flex" href="productos.php">
                            <i class="fas fa-tablets mr-2 fa-2x"></i>Medicamentos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="tipo.php">
                            <i class=" fas fa-microscope mr-2 fa-2x"></i>Principio Activo
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="presentacion.php">
                            <i class=" fas fa-bong mr-2 fa-2x"></i> Presentación
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="laboratorio.php">
                            <i class=" fas fa-vial mr-2 fa-2x"></i> Utilidad
                        </a>
                    </li>
                    </details>
                    <details>
                        <summary><i class=" fas fa-dove mr-2 fa-2x"></i>PDNJ</summary>
                   
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="niños.php">
                            <i class=" fas fa-dove mr-2 fa-2x"></i>
                            <p>Registro de paciente</p>
                        </a>
                    </li>
                </li>
                <li class="nav-item">
                            <a class="nav-link d-flex" href="historico_ninos.php">
                            <i class="fa-solid fa-person-circle-check mr-2 fa-2x"></i>
                            <p> Historico de Pacientes PDNJ </p>
                        </a>
                    </li>
                </li>
                <li class="nav-item">
                        <a class="nav-link d-flex" href="consulta_ninos.php">
                            <i class=" fas fa-address-book mr-2 fa-2x"></i>Consulta Pacientes PDNJ
                        </a>
                    </li>
            </details>
					<details>
                    <summary><i class="fas fa-clipboard mr-2 fa-2x"></i>Jornadas</summary>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="vacunacion.php">
                            <i class=" fas fa-syringe mr-2 fa-2x"></i>
                            <p> Vacunación</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="consulta_vacunacion.php">
                            <i class=" fas fa-user-pen mr-2 fa-2x"></i>
                            <p> Consulta de Vacunación</p>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link d-flex" href="jornada.php">
                            <i class=" fas fa-venus-mars mr-2 fa-2x"></i>
                            <p> Censo</p>
                        </a>
                    </li>
					</details>
					</summary>
                    <details>
                    <summary><i class="fas fa-hand-holding-heart mr-2 fa-2x"></i>Donaciones</summary>

                        <li class="nav-item">
                        <a class="nav-link d-flex" href="ventas.php">
                            <i class=" fas fa-hand-holding-medical mr-2 fa-2x"></i>
                            <p> Nueva Salida</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="lista_ventas (2).php">
                            <i class=" fas fa-book-medical mr-2 fa-2x"></i>
                            <p> Historial de Salidas</p>
                        </a>
                    </li>
					
                </details>
                <details>
                 <summary><i class="fas fa-cash-register mr-2 fa-2x"></i>Facturacion</summary>
                       
                        	<li class="nav-item">
                        <a class="nav-link sub-menu-options" href="tasa.php">
                            <i class="fas fa-money-bill mr-2 fa-2x"></i> Tasa del Dia
                            
                        </a>
                            </li>
                             <li class="nav-item">
                        <a class="nav-link d-flex" href="venta.php">
                            <i class=" fas fa-receipt mr-2 fa-2x"></i>
                            <p> Nueva Factura</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex" href="itinerario.php">
                            <i class=" fas fa-calendar-check mr-2 fa-2x"></i>
                            <p> Agenda de Consultas</p>
                        </a>
                    </li>
                </li>
				<li class="nav-item">
                        <a class="nav-link d-flex" href="lista_ventas.php">
                            <i class=" fas fa-book mr-2 fa-2x"></i>
                            <p> Historial de Ventas </p>
                        </a>
                    </li>
					<li class="nav-item">
                        <a class="nav-link d-flex" href="lista_pago.php">
                            <i class=" fas fa-folder-plus mr-2 fa-2x"></i>
                            <p> Historial de Pagos </p>
                        </a>
                    </li>
					
							<li class="nav-item">
                            <a class="nav-link d-flex" href="cierre_caja.php">
							<i class="fa-solid fa-money-bill-trend-up mr-2 fa-2x"></i>
							<p> Cierre de Caja </p>
                        </a>
                    </li>
					<li class="nav-item">
                            <a class="nav-link d-flex" href="servicios.php">
							<i class="fa-solid fa-list mr-2 fa-2x"></i>
							<p> Baremo de Servicios </p>
                        </a>
                    </li>
                </li>
				<li class="nav-item">
                            <a class="nav-link d-flex" href="historico.php">
                            <i class="fa-solid fa-person-circle-check mr-2 fa-2x"></i>
                            <p> Historico de Pacientes </p>
                        </a>
                    </li>
                </li>
                </details>
                <details>
                <summary><i class="fa-solid fa-square-poll-vertical mr-2 fa-2x"></i>Reportes</summary>
				<li class="nav-item">
                            <a class="nav-link d-flex" href="administracion.php">
							<i class="fa-regular fa-address-card mr-2 fa-2x"></i>
							<p> Reporte de Pacientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link d-flex" href="estadisticas.php">
							<i class="fa-solid fa-chart-column mr-2 fa-2x"></i>
							<p>Estadisticas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link d-flex" href="caja.php">
							<i class="fa-solid fa-file-invoice-dollar mr-2 fa-2x"></i>
							<p>Arqueo de Caja</p>
                        </a>
                    </li>
                    </details>
                    <details>
                    <summary><i class="fa-solid fa-flask-vial mr-2 fa-2x"></i>Laboratorio</summary>
				<li class="nav-item">
                            <a class="nav-link d-flex" href="lab.php">
							<i class="fa-regular fa-address-card mr-2 fa-2x"></i>
							<p>Pacientes de Laboratorio</p>
                        </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link d-flex" href="resultados_lab.php">
							<i class="fa-solid fa-file-waveform mr-2 fa-2x"></i>
							<p>Resultados</p>
                        </a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link d-flex" href="caja.php">
							<i class="fa-solid fa-file-invoice-dollar mr-2 fa-2x"></i>
							<p>Arqueo de Caja</p>
                        </a>
                    </li>
                    </details>
                    <details>
                         <summary><i class="fas fa-cogs mr-2 fa-2x"></i>Opciones</summary>
                        <div>
                        <li class="nav-item">
                        <a class="nav-link sub-menu-options" href="config.php">
                            <i class="fas fa-cogs mr-2 fa-2x"></i> Configuración
                            
                        </a>
                            </li>
                            
                            <li class="nav-item">
                        <a class="nav-link d-flex" href="usuarios.php">
                            <i class="fas fa-user mr-2 fa-2x"></i>
                            <p> Usuarios</p>
                        </a>
                    </li>
               
            </div>
    </details>
                            
                            
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="javascript:;">Sistema Administrativo</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end">

                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user mr-2 fa-2x"></i>
                                    <p class="d-lg-none d-md-block">
                                        Cuenta
                                    </p>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#nuevo_pass">Perfil</a>
                                    <div class="dropdown-divider"></div>
                                     <a class="dropdown-item" href="salir.php">Cerrar Sesión</a>
                                    
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <div class="content bg">
                <div class="container-fluid">
                   