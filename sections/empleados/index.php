<?php
include("../../db.php");

if(isset($_GET['txtID'] )){
    // Si recibe dato, Almacena id,
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";

    // Busca archivo relacionado con el empleado
    $sentencia=$conexion->prepare("SELECT foto,cv FROM `tbl_empleados` WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
 
    $sentencia->execute();
    $registro_recuperado=$sentencia->fetch(PDO::FETCH_LAZY);

    // print_r($registro_recuperado);
    
if( isset($registro_recuperado["foto"]) && $registro_recuperado["foto"]!="" ){
    if( file_exists("./".$registro_recuperado["foto"])){
        unlink("./".$registro_recuperado["foto"]);
    }
}
    
if( isset($registro_recuperado["cv"]) && $registro_recuperado["cv"]!="" ){
    if( file_exists("./".$registro_recuperado["cv"])){
        unlink("./".$registro_recuperado["cv"]);
    }
}

    // Prepara sentencia sql,
    $sentencia=$conexion->prepare("DELETE FROM tbl_empleados WHERE id=:id");
    // Pasa parametro para borrado,
    $sentencia->bindParam(":id",$txtID);
    // Elimina.
    $sentencia->execute();
    $mensaje = "Registro eliminado";
    header("Location: index.php?mensaje=".$mensaje);
}

$sentencia=$conexion->prepare("SELECT *,
(SELECT nombredelpuesto FROM tbl_puestos WHERE tbl_puestos.id=tbl_empleados.idpuesto limit 1) as puesto
FROM `tbl_empleados`");
$sentencia->execute();
$lista_tbl_empleados=$sentencia->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include("../../temps/header.php"); ?>

    Empleados
    <div class="card">
        <div class="card-header">
            
            <a name=""
                id=""
                class="btn btn-success"
                href="crear.php"
                role="button"
                >Agregar Registro
            </a>
            
        </div>
        <div class="card-body">
        
        <div
            class="table-responsive-sm"
        >
            <table
                class="table" id="tabla_id" >
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Foto</th>
                        <th scope="col">CV</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Fecha de Ingreso</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>

                <?php foreach ($lista_tbl_empleados as $registro) { ?>

                    <tr class="">
                        <td><?php echo $registro['id']; ?></td>
                        <td scope="row">
                        <?php echo $registro['primernombre']; ?>
                        <?php echo $registro['segundonombre']; ?>
                        <?php echo $registro['apellidopaterno']; ?>
                        <?php echo $registro['apellidomaterno']; ?>
                        </td></td>
                        <td>
                            <img style="max-width: 100px; max-height: 100px;"
                                src="<?php echo $registro['foto']; ?>"
                                class="img-fluid rounded"
                                alt=""
                            />
                        </td>
                        <td>
                            <a href="<?php echo $registro['cv']; ?>">
                            <?php echo $registro['cv']; ?>
                            </a>
                        </td>
                        <td><?php echo $registro['puesto']; ?></td>
                        <td><?php echo $registro['fechadeingreso']; ?></td>
                        <td>
                        <a href="carta_rec.php?txtID=<?php echo $registro['id']; ?>" class="btn btn-primary" role="button">Carta</a>
                        |<a class="btn btn-info" href="editar.php?txtID=<?php echo $registro['id']; ?>" role="button">Editar</a>
                        |<a class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']; ?>);" role="button">Eliminar</a>

                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        

        </div>
    </div>

<?php include("../../temps/footer.php"); ?>