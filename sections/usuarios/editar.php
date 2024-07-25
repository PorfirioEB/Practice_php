<?php
include("../../db.php");

if(isset($_GET['txtID'] )){
    // Si recibe dato, Almacena id,
    $txtID=(isset($_GET['txtID']))?$_GET['txtID']:"";


    // Prepara sentencia sql,
    $sentencia=$conexion->prepare("SELECT * FROM tbl_usuarios WHERE id=:id");
    // Pasa parametro para edicion,
    $sentencia->bindParam(":id",$txtID);
    //ejecuta edicion
    $sentencia->execute();

    //Llenar registro de nuevos datos
    $registro=$sentencia->fetch(PDO::FETCH_LAZY);
    $usuario=$registro["usuario"];
    $password=$registro["password"];
    $correo=$registro["correo"];
}

if($_POST){
    // print_r($_POST);

    // Recolectamos los datos del metodo POST
    $txtID=(isset($_POST["txtID"])?$_POST["txtID"]:"");
    $usuario=(isset($_POST["usuario"])?$_POST["usuario"]:"");
    $password=(isset($_POST["password"])?$_POST["password"]:"");
    $correo=(isset($_POST["correo"])?$_POST["correo"]:"");

    // Preparar la insercion de los datos
    $sentencia=$conexion->prepare("UPDATE tbl_usuarios SET
        usuario=:usuario,
        password=:password,
        correo=:correo
        WHERE id=:id
        ");

    // Asigna valores que tienen uso de :variable
    $sentencia->bindParam(":usuario",$usuario);
    $sentencia->bindParam(":password",$password);
    $sentencia->bindParam(":correo",$correo);
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    $mensaje = "Registro actualizado";
    header("Location: index.php?mensaje=".$mensaje);
}

?>

<?php include("../../temps/header.php"); ?>


<br />

<div class="card">
    <div class="card-header">Datos del usuario</div>
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
        <label for="usuario" class="form-label">Nombre del usuario:</label>
        <input
            type="text"
            value="<?php echo $usuario;?>"
            class="form-control"
            name="usuario"
            id="usuario"
            aria-describedby="helpId"
            placeholder="Nombre del usuario"
        />
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
            type="password"
            value="<?php echo $password;?>"
            class="form-control"
            name="password"
            id="password"
            aria-describedby="helpId"
            placeholder="Escriba su contraseña"
        />
    </div>

    <div class="mb-3">
        <label for="correo" class="form-label">Correo:</label>
        <input
            type="email"
            value="<?php echo $correo;?>"
            class="form-control"
            name="correo"
            id="correo"
            aria-describedby="helpId"
            placeholder="Escriba su correo"
        />
    </div>
    
    

    <button
        type="submit"
        class="btn btn-success"
    >
        Actualizar
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


<?php include("../../temps/footer.php"); ?>