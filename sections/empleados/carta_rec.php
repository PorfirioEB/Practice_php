<?php
include("../../db.php");
date_default_timezone_set('America/Mexico_City');

if(isset($_GET['txtID'] )){
    // Si recibe dato, Almacena id,
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
    // Prepara sentencia sql,
    $sentencia=$conexion->prepare("SELECT *,(SELECT nombredelpuesto 
    FROM tbl_puestos 
    WHERE tbl_puestos.id=tbl_empleados.idpuesto limit 1) as puesto FROM tbl_empleados WHERE id=:id");
    // Pasa parametro para edicion,
    $sentencia->bindParam(":id",$txtID);
    //ejecuta edicion
    $sentencia->execute();

    //Llenar registro de nuevos datos
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);


    $primernombre=$registro["primernombre"];
    $segundonombre=$registro["segundonombre"];
    $apellidopaterno=$registro["apellidopaterno"];
    $apellidomaterno=$registro["apellidomaterno"];

    $nombreCompleto=$primernombre." ".$segundonombre." ".$apellidopaterno." ".$apellidomaterno;

    $foto=$registro["foto"];
    $cv=$registro["cv"];
    $idpuesto=$registro["idpuesto"];
    $puesto=$registro["puesto"];
    $fechadeingreso=$registro["fechadeingreso"];

    $fechaInicio= new DateTime($fechadeingreso);
    $fechaFin= new DateTime(date('Y-m-d'));
    $diferencia=date_diff($fechaInicio,$fechaFin);
    $fechaActual = date('d/m/Y');
}
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carta de Recomendación Laboral</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .content {
            text-align: justify; /* Justifica el texto */
        }

        .text-right {
            text-align: right; /* Alinea el texto a la derecha */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Carta de Recomendación Laboral</h1>

        <div class="text-right">
            Xonacatlán, Estado de México a <strong><?php echo $fechaActual; ?></strong>
        </div>

        <br/><br/>
        A quien pueda interesar:
        <br/><br/>
        Reciba un cordial y respetuoso saludo.
        <br/><br/>
        A través de estas líneas deseo hacer de su conocimiento que el/la sr(a) <strong><?php echo $nombreCompleto;?></strong>,
        quien laboró en mi organización durante <strong><?php echo $diferencia->y;?> año(s)</strong>
        es un ciudadano con una conducta intachable. Ha demostrado ser un gran trabajador,
        comprometido, responsable y fiel cumplidor de sus tareas.
        <br/><br/>
        Durante estos años se ha desempeñado como <strong><?php echo $puesto;?></strong>
        y siempre ha manifestado preocupación por mejorar, capacitarse y estar al día con sus conocimientos.
        Es por ello le sugiero considere esta recomendación, con la confianza de que estará siempre a la altura de sus compromisos y responsabilidades.
        <br/><br/>
        Sin más nada a que referirme y, esperando que esta misiva sea tomada en consideración dejo mi número de contacto para cualquier consulta de su interés.
        <br/><br/><br/><br/><br/><br/><br/><br/>
        Atentamente,
        <br/>
        Ing. Carlos González
    </div>
</body>
</html>
<?php
$HTML = ob_get_clean();


require_once("../../libs/autoload.inc.php");
use Dompdf\Dompdf;
$dompdf= new Dompdf();

$opciones= $dompdf->getOptions();
$opciones->set(array("isRemoteEnabled"=>true));

$dompdf->setOptions($opciones);
$dompdf->loadHTML($HTML);
$dompdf->setPaper('letter');
$dompdf->render();
$dompdf->stream("archivo.pdf", array("Attachment"=>false));


?>