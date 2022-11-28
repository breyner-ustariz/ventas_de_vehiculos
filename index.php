<?php
   include 'conexion.php';
   session_start();  
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!--<link rel="stylesheet" href="style.css">-->
   <!-- bootstrap -->

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">


   <title>venta de vehiculos </title>
   <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
</head>
<body>

   <?php  

      if(isset($_POST['guardar'])){
         $marca = $_POST['marca'];
         $modelo = $_POST['modelo'];
         $cantidad = $_POST['cantidad'];
         date_default_timezone_set("America/Bogota");
         $fecha = date('Y-m-d');
         $sql = "SELECT Stock_modelo, precio_unidad FROM modelo WHERE id_modelo='$modelo'";
         $result = $connection->query($sql);
         $data = $result->fetch_assoc();
         $stock = $data['Stock_modelo'];
         if($cantidad > $stock){

            $_SESSION['mensaje'] = "No puedes registrar esta venta por que Solo hay " . $stock . " disponibles.";
             
         }else{
            $_SESSION['exito'] = "Venta registrada exitosamente";
            $precioUnidad = $data['precio_unidad'];
            $totalVenta = ($precioUnidad * $cantidad);
            $stockRestante = ($stock - $cantidad);
            $sqlInsertarVenta = "INSERT INTO ventas (Cantidad_venta, Total_venta, fk_modelo, Fecha_venta)
            VALUES ('$cantidad', '$totalVenta', '$modelo', '$fecha')";
            $insertarVenta = mysqli_query($connection, $sqlInsertarVenta);
            if($insertarVenta){
               $sqlActualizarModelo = "UPDATE modelo SET Stock_modelo='$stockRestante' WHERE id_modelo='$modelo'";
               $actualizarModelo = mysqli_query($connection, $sqlActualizarModelo);
               if($actualizarModelo){
               }else{
                  echo "Ocurrio un error al actualizar el stock del modelo";
               }
            }else{
               echo "Ocurrio un error al insertar la venta....";
            }
         }
      }

   ?>



   <br>
   
   <div class="m-auto p-5">

      <div class="row p-2">
         <h2 class="text-center"> Ventas de Vehiculos </h2>
         <div class="col-md-3 mb-3">
            <form action="index.php" method="post" class="w-100">
            <div class="" >
               <div class="form-group bg-light d-flex flex-column w-100">
               
                  <h5 class="bg-success p-2 text-light text-center"> Nueva Venta </h5>
                  <label for="marca" class="mb-2 text-center h6">Marca</label>
                  
                     <?php 
                     $sql = $connection -> query("SELECT nombre_marca, id_marca from marca");?>
                     <select name="marca" id="marca" class="m-auto form-select  w-75 text-center">
                        <option value="">Selecciona</option>
                     <?php while ($marca = $sql  -> fetch_assoc())
                     {
                     ?>
                     
                     <option value="<?php echo $marca['id_marca'] ?>">
                     <?php echo $marca['nombre_marca']?>
                     </option>
                     <?php } ?>
                  </select>
                  <label for="modelo" class="mb-2 text-center h6">Modelo</label>
                  <select name="modelo" id="modelo" class="form-select w-75 m-auto text-center">
                     <option value="" id="option-sms"></option>
                  </select>
            
                  <label for="cantidad" class="ms-3 h6">
                     cantidad:
                     <input type="number" min="0" name="cantidad" class="ms-2 mt-2 form-control mb-3 w-50  d-inline" >
                  </label>
                  
                  <?php if (isset($_SESSION['exito'])){?>
                  <div class="alert alert-success  mb-2" role="alert">
                   <p class="h6 small"><?php  echo $_SESSION['exito'];?></p>
                  </div>  
                  
                <?php  session_unset(); };?>
            
                  <input type="submit" name="guardar" value="Guardar" class="btn btn-success btn-block">
            
               </div>
            </div>
      
           
            
            </form>
         </div>
         
            <div class="col-md-9">
               <table class="table table-secondary ">
                  <thead>
                     <tr>
                        <th class="col bg-success text-light">Fecha_venta</th>
                        <th class="col bg-success text-light">Marca</th>
                        <th class="col bg-success text-light">Modelo</th>
                        <th class="col bg-success text-light ">Ctd_venta</th>
                        <th class="col bg-success text-light">Precio_unidad</th>
                        <th class="col bg-success text-light">Total_venta</th>
                     </tr>
                  </thead>
         
                  
                  <tbody>
         
                  <?php 
                     $sql = $connection -> query("SELECT * from marca
                     INNER JOIN modelo ON marca.id_marca = modelo.fk_marca
                     INNER JOIN ventas ON ventas.fk_modelo = modelo.id_modelo");
                     while($result = $sql  -> fetch_assoc()){
         
                     $cantidad = $result['Cantidad_venta'];
                     $precioUn = $result['precio_unidad'];
                     $totalVenta = $cantidad * $precioUn;
         
                     ?>
                     <tr>
                        <td><?php echo $result["Fecha_venta"];?></td>
                        <td><?php echo $result["nombre_marca"];?></td>
                        <td><?php echo $result["nombre_modelo"];?></td>
                        <td class="text-center"><?php echo $cantidad;?></td>
                        <td><?php echo $precioUn = number_format($precioUn) . ' $';?></td>
                        <td><?php echo $totalVenta = number_format($totalVenta) . ' $'?></td>
                     </tr>
         
                  <?php } ?>
                     
                  </tbody>
         
               </table>
               <?php if (isset($_SESSION['mensaje'])){?>
                  <div class="alert alert-danger w-75 m-auto" role="alert">
                   <?php  echo $_SESSION['mensaje'];?>
                  </div>  
                  
                <?php  session_unset(); };?>
            </div>
                
         
      </div>
   </div>

  <script src="main.js"></script>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
</body>
</html>