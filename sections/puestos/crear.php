<?php
include("../../db.php");

IF($_POST){
print_r($_POST);

    // Recolectamos los datos del metodo POST
    $nombredelpuesto=(isset($_POST["nombredelpuesto"])?$_POST["nombredelpuesto"]:"");
    // Preparar la insercion de los datos
    $sentencia=$conexion->prepare("INSERT INTO tbl_puestos(id,nombredelpuesto)
                VALUES (null, :nombredelpuesto)");
    // Asignando los valores que vienen del metodo POST ( Los que vienen del formulario)
    $sentencia->bindParam(":nombredelpuesto",$nombredelpuesto);
    $sentencia->execute();
    $mensaje = "Registro agregado";
    header("Location: index.php?mensaje=".$mensaje);

}

?>

<?php include "../../temps/header.php";?>
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
        <label for="nombredelpuesto" class="form-label">Nombre del puesto:</label>
        <input
            type="text"
            class="form-control"
            name="nombredelpuesto"
            id="nombredelpuesto"
            aria-describedby="helpId"
            placeholder="Nombre del puesto"
        />
    </div>
    
    <button
        type="submit"
        class="btn btn-success"
    >
        Agregar
    </button>
    <a
        name=""
        id=""
        class="btn btn-primary"
        href="index.php"
        role="button"
        >Cancelar</a
    >
    

    </form>

    </div>
    <div class="card-footer text-muted">  </div>
</div>


<?php include "../../temps/footer.php";?>