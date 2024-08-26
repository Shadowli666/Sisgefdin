<?php
session_start();

$idcita = $_POST['idcita']; // Se asegura de que el ID de la cita se pase desde el formulario

// Guardar los datos de Hematología en la sesión usando $idcita como clave

    <div id="datosGuardados">
        <?php
        
        if (isset($_SESSION['hematologia'][$idcita])) {
            echo "<h3>Datos de Hematología</h3>";
            echo "Hemoglobina: " . htmlspecialchars($_SESSION['hematologia'][$idcita]['hemoglobina']) . "<br>";
            echo "Hematocritos: " . htmlspecialchars($_SESSION['hematologia'][$idcita]['hematocritos']) . "<br>";
            echo "Cuentas Blancas: " . htmlspecialchars($_SESSION['hematologia'][$idcita]['cuentas_blancas']) . "<br>";
            echo "Plaquetas: " . htmlspecialchars($_SESSION['hematologia'][$idcita]['plaquetas']) . "<br>";
            echo "VSG: " . htmlspecialchars($_SESSION['hematologia'][$idcita]['vsg']) . "<br><br>";
        }

        if (isset($_SESSION['leucocitos'][$idcita])) {
            echo "<h3>Datos de Fórmula Leucocitaria</h3>";
            echo "SEG: " . htmlspecialchars($_SESSION['leucocitos'][$idcita]['seg']) . "<br>";
            echo "LINF: " . htmlspecialchars($_SESSION['leucocitos'][$idcita]['linf']) . "<br>";
            echo "EOSIN: " . htmlspecialchars($_SESSION['leucocitos'][$idcita]['eosin']) . "<br>";
            echo "MONOC: " . htmlspecialchars($_SESSION['leucocitos'][$idcita]['monoc']) . "<br>";
            echo "BASOF: " . htmlspecialchars($_SESSION['leucocitos'][$idcita]['basof']) . "<br>";
            echo "OTROS: " . htmlspecialchars($_SESSION['leucocitos'][$idcita]['otros']) . "<br>";
            echo "TOTAL: " . htmlspecialchars($_SESSION['leucocitos'][$idcita]['total']) . "<br>";
        }
        ?>
    </div>
    
</body>
</html>
?>