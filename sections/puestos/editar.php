<?php
include("../../db.php");

if(isset($_GET['txtID'] )){
    // Si recibe dato, Almacena id,
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";


    // Prepara sentencia sql,
    $sentencia=$conexion->prepare("SELECT * FROM tbl_puestos WHERE id=:id");
    // Pasa parametro para edicion,
    $sentencia->bindParam(":id",$txtID);
    //ejecuta edicion
    $sentencia->execute();


    //Llenar registro de nuevos datos
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);
    $nombredelpuesto=$registro["nombredelpuesto"];
}
IF($_POST){
    
        //Recolectamos los datos del metodo POST
        $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
        $nombredelpuesto=(isset($_POST["nombredelpuesto"])?$_POST["nombredelpuesto"]:"");

        //Preparar la actualizacion de los datos
        $sentencia=$conexion->prepare("UPDATE tbl_puestos 
        SET nombredelpuesto=:nombredelpuesto
        WHERE id=:id ");
        // Actualizando los valores que van para metodo POST ( Los que se ingresan en el formulario)
        $sentencia->bindParam(":nombredelpuesto",$nombredelpuesto);
        $sentencia->bindParam(":id",$txtID);
        $sentencia->execute();
        $mensaje = "Registro actualizado";
        header("Location: index.php?mensaje=" . urlencode($mensaje));
    }

?>

<?php include("../../temps/header.php"); ?>
<?php if(isset($_GET['mensaje'])) { ?>
<script>
    swal.fire({icon:"success", title:"<?php echo $_GET['mensaje']; ?>"});
</script>
<?php } ?>

<br />

<div class="card">
    <div class="card-header">Puestos</div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">

        <div class="mb-3">
            <label for="txtID" class="form-label">ID:</label>
            <input
                type="text"
                value="<?php echo $txtID;?>"
                class="form-control" readonly
                name="txtID"
                id="txtID"
                aria-describedby="helpId"
                placeholder="ID"
            />
        </div>
            <div class="mb-3">
                <label for="nombredelpuesto" class="form-label">Nombre del puesto:</label>
                <input type="text" value="<?php echo $nombredelpuesto;?>" class="form-control" name="nombredelpuesto" id="nombredelpuesto"
                    aria-describedby="helpId" placeholder="Nombre del puesto" />
            </div>
            <button type="submit" class="btn btn-success">
                Actualizar
            </button>
            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>
        </form>
    </div>
    <div class="card-footer text-muted"> </div>
</div>

<?php include("../../temps/footer.php"); ?>