<?php
include("../../db.php");

IF($_POST){
    print_r($_POST);
    print_r($_FILES);

    $primernombre=(isset($_POST["primernombre"])?$_POST["primernombre"]:"");
    $segundonombre=(isset($_POST["segundonombre"])?$_POST["segundonombre"]:"");
    $apellidopaterno=(isset($_POST["apellidopaterno"])?$_POST["apellidopaterno"]:"");
    $apellidomaterno=(isset($_POST["apellidomaterno"])?$_POST["apellidomaterno"]:"");

    $foto=(isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
    $cv=(isset($_FILES["cv"]['name'])?$_FILES["cv"]['name']:"");

    $idpuesto=(isset($_POST["idpuesto"])?$_POST["idpuesto"]:"");
    $fechadeingreso=(isset($_POST["fechadeingreso"])?$_POST["fechadeingreso"]:"");

    $sentencia=$conexion->prepare("INSERT INTO `tbl_empleados` (`id`, `primernombre`, `segundonombre`, `apellidopaterno`, `apellidomaterno`, `foto`, `cv`, `idpuesto`, `fechadeingreso`) 
    VALUES (NULL,:primernombre,:segundonombre,:apellidopaterno,:apellidomaterno,:foto,:cv,:idpuesto,:fechadeingreso);");
   
    $sentencia->bindParam(":primernombre",$primernombre);
    $sentencia->bindParam(":segundonombre",$segundonombre);
    $sentencia->bindParam(":apellidopaterno",$apellidopaterno);
    $sentencia->bindParam(":apellidomaterno",$apellidomaterno);

    // Obtiene el tiempo en que se utiliza el archivo
    $fecha_=new DateTime();
    // Crea un nuevo nombre del archivo mediante el tiempo para que no se sobreescriba con otros
    $nombreArchivo_foto=($foto!='')?$fecha_->getTimestamp()."_".$_FILES["foto"]['name']:"";
    // Crea y utiliza archivo temporal para poder mover ese archivo al nuevo destino que es: 'nombreArchivo'
    $tmp_foto=$_FILES["foto"]['tmp_name'];
    // Crea una condicion para mover el archivo temporal a su nuevo destino 'nombreArchivo' 
    // en caso de qu exista
    if($tmp_foto!=''){
        move_uploaded_file($tmp_foto,"./".$nombreArchivo_foto);
    }
    // Actualiza en la base de datos el nombre del archivo
    $sentencia->bindParam(":foto",$nombreArchivo_foto);


    // Crea un nuevo nombre del archivo mediante el tiempo para que no se sobreescriba con otros
    $nombreArchivo_cv=($cv!='')?$fecha_->getTimestamp()."_".$_FILES["cv"]['name']:"";
    // Crea y utiliza archivo temporal para poder mover ese archivo al nuevo destino que es: 'nombreArchivo'
    $tmp_cv=$_FILES["cv"]['tmp_name'];
    // Crea una condicion para mover el archivo temporal a su nuevo destino 'nombreArchivo' 
    // en caso de qu exista
    if($tmp_cv!=''){
        move_uploaded_file($tmp_cv,"./".$nombreArchivo_cv);
    }
    $sentencia->bindParam(":cv",$nombreArchivo_cv);

    $sentencia->bindParam(":idpuesto",$idpuesto);
    $sentencia->bindParam(":fechadeingreso",$fechadeingreso);

    $sentencia->execute();

    $mensaje = "Registro agregado";
    header("Location: index.php?mensaje=".$mensaje);
}

$sentencia=$conexion->prepare("SELECT * FROM `tbl_puestos`");
$sentencia->execute();
$lista_tbl_puestos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../temps/header.php"); ?>

<br />
<div class="card">
    <div class="card-header">Datos del empleado</div>
    <div class="card-body">

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer nombre</label>
                <input type="text" class="form-control" name="primernombre" id="primernombre" aria-describedby="helpId"
                    placeholder="Primer nombre" />
            </div>

            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo nombre</label>
                <input type="text" class="form-control" name="segundonombre" id="segundonombre"
                    aria-describedby="helpId" placeholder="Segundo nombre" />
            </div>

            <div class="mb-3">
                <label for="apellidopaterno" class="form-label">Apellido paterno</label>
                <input type="text" class="form-control" name="apellidopaterno" id="apellidopaterno"
                    aria-describedby="helpId" placeholder="Apellido paterno" />
            </div>

            <div class="mb-3">
                <label for="apellidomaterno" class="form-label">Apellido materno</label>
                <input type="text" class="form-control" name="apellidomaterno" id="apellidomaterno" aria-describedby="segundoapellido"
                    placeholder="Apellido materno" />
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Foto:</label>
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId"
                    placeholder="Foto" />
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">CV(PDF):</label>
                <input type="file" class="form-control" name="cv" id="cv" placeholder="CV"
                    aria-describedby="fileHelpId" />
            </div>

            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto:</label>
                <select class="form-select form-select-sm" name="idpuesto" id="idpuesto">
                    <?php foreach ($lista_tbl_puestos as $registro) { ?>
                    <option value="<?php echo $registro['id'] ?>">
                    <?php echo $registro['nombredelpuesto'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="fechadeingreso" class="form-label">Fecha de ingreso:</label>
                <input type="date" class="form-control" name="fechadeingreso" id="fechadeingreso"
                    aria-describedby="emailHelpId" placeholder="Fecha de ingreso" />

            </div>

            <button type="submit" class="btn btn-success">
                Agregar registro
            </button>

            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>



        </form>

    </div>
    <div class="card-footer text-muted">Footer</div>
</div>


<?php include("../../temps/footer.php"); ?>