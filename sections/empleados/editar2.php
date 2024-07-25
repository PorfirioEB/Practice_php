<?php
include("../../db.php");

if(isset($_GET['txtID'] )){
    // Si recibe dato, Almacena id,
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";
    // Prepara sentencia sql,
    $sentencia=$conexion->prepare("SELECT * FROM tbl_empleados WHERE id=:id");
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

    $foto=$registro["foto"];
    $cv=$registro["cv"];

    $idpuesto=$registro["idpuesto"];
    $fechadeingreso=$registro["fechadeingreso"];

    $sentencia=$conexion->prepare("SELECT * FROM `tbl_puestos`");
    $sentencia->execute();
    $lista_tbl_puestos=$sentencia->fetchAll(PDO::FETCH_ASSOC);

}

IF($_POST){

    $txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
    $primernombre=(isset($_POST["primernombre"])?$_POST["primernombre"]:"");
    $segundonombre=(isset($_POST["segundonombre"])?$_POST["segundonombre"]:"");
    $apellidopaterno=(isset($_POST["apellidopaterno"])?$_POST["apellidopaterno"]:"");
    $apellidomaterno=(isset($_POST["apellidomaterno"])?$_POST["apellidomaterno"]:"");

    $idpuesto=(isset($_POST["idpuesto"])?$_POST["idpuesto"]:"");
    $fechadeingreso=(isset($_POST["fechadeingreso"])?$_POST["fechadeingreso"]:"");

    $sentencia=$conexion->prepare("
    UPDATE tbl_empleados SET
    primernombre=:primernombre,
    segundonombre=:segundonombre,
    apellidopaterno=:apellidopaterno,
    apellidomaterno=:apellidomaterno,
    idpuesto=:idpuesto,
    fechadeingreso=:fechadeingreso
    WHERE id=:id
    ");

    $sentencia->bindParam(":primernombre",$primernombre);
    $sentencia->bindParam(":segundonombre",$segundonombre);
    $sentencia->bindParam(":apellidopaterno",$apellidopaterno);
    $sentencia->bindParam(":apellidomaterno",$apellidomaterno);
    $sentencia->bindParam(":idpuesto",$idpuesto);
    $sentencia->bindParam(":fechadeingreso",$fechadeingreso);
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();

    $foto=(isset($_FILES["foto"]['name'])?$_FILES["foto"]['name']:"");
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

        $sentencia=$conexion->prepare("SELECT foto FROM `tbl_empleados` WHERE id=:id");
        $sentencia->bindParam(":id",$txtID);
 
        $sentencia->execute();
        $registro_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);

            // print_r($registro_recuperado);

            if( isset($registro_recuperado["foto"]) && $registro_recuperado["foto"]!="" ){
                if( file_exists("./".$registro_recuperado["foto"])){
                    unlink("./".$registro_recuperado["foto"]);
                }
            }

        // Busca archivo relacionado con el empleado
    $sentencia=$conexion->prepare("UPDATE tbl_empleados set foto=:foto WHERE id=:id");
        $sentencia->bindParam(":foto",$nombreArchivo_foto);
        $sentencia->bindParam(":id",$txtID);
        $sentencia->execute();

    }

    $cv=(isset($_FILES["cv"]['name'])?$_FILES["cv"]['name']:"");

    //header("Location:index.php");
}

?>

<?php include("../../temps/header.php"); ?>

<br />
<div class="card">
    <div class="card-header">Datos del empleado</div>
    <div class="card-body">

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

        <form action="" method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="primernombre" class="form-label">Primer nombre</label>
                <input type="text" value="<?php echo $primernombre;?>" class="form-control" name="primernombre" id="primernombre" aria-describedby="helpId"
                    placeholder="Primer nombre" />
            </div>

            <div class="mb-3">
                <label for="segundonombre" class="form-label">Segundo nombre</label>
                <input type="text" value="<?php echo $segundonombre;?>" class="form-control" name="segundonombre" id="segundonombre"
                    aria-describedby="helpId" placeholder="Segundo nombre" />
            </div>

            <div class="mb-3">
                <label for="apellidopaterno" class="form-label">Apellido paterno</label>
                <input type="text" value="<?php echo $apellidopaterno;?>" class="form-control" name="apellidopaterno" id="apellidopaterno"
                    aria-describedby="helpId" placeholder="Apellido paterno" />
            </div>

            <div class="mb-3">
                <label for="apellidomaterno" class="form-label">Apellido materno</label>
                <input type="text" value="<?php echo $apellidomaterno;?>" class="form-control" name="apellidomaterno" id="apellidomaterno" aria-describedby="segundoapellido"
                    placeholder="Apellido materno" />
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Foto:</label>
                    <br/>
                <img style="max-width: 150px; max-height: 150px;"
                    src="<?php echo $foto;?>"
                    class="img-fluid rounded"
                    alt=""
                />
                    <br/><br/>
                <input type="file" class="form-control" name="foto" id="foto" aria-describedby="helpId"
                    placeholder="Foto" />
            </div>

            <div class="mb-3">
                <label for="cv" class="form-label">CV(PDF):</label>
                    <br/>
                <a href="<?php echo $cv;?>"><?php echo $cv;?></a>
                <input type="file" class="form-control" name="cv" id="cv" placeholder="CV"
                    aria-describedby="fileHelpId" />
            </div>

            <div class="mb-3">
                <label for="idpuesto" class="form-label">Puesto:</label>
                "<?php echo $idpuesto;?>" 
                <select class="form-select form-select-sm" name="idpuesto" id="idpuesto">
                    <?php foreach ($lista_tbl_puestos as $registro) { ?>
                    <option <?php echo ($idpuesto==$registro['id'])?"selected":"";?> value="<?php echo $registro['id'] ?>">
                        <?php echo $registro['nombredelpuesto'] ?>
                    </option>
                <?php } ?>
            </select>
        </div>

            <div class="mb-3">
                <label for="fechadeingreso" class="form-label">Fecha de ingreso:</label>
                <input type="date" value="<?php echo $fechadeingreso;?>" class="form-control" name="fechadeingreso" id="fechadeingreso"
                    aria-describedby="emailHelpId" placeholder="Fecha de ingreso" />

            </div>

            <button type="submit" class="btn btn-success">
                Actualizar registro
            </button>

            <a name="" id="" class="btn btn-primary" href="index.php" role="button">Cancelar</a>



        </form>

    </div>
    <div class="card-footer text-muted">Footer</div>
</div>


<?php include("../../temps/footer.php"); ?>