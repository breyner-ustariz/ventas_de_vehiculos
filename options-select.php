<?php 
     include 'conexion.php';  
     $marca = $_GET['marca'];
     $sql = "SELECT * FROM modelo WHERE fk_marca='$marca'";
     $result = $connection->query($sql);
     $count = mysqli_num_rows(mysqli_query($connection, $sql));
     $data = array();
     while($optionsSelect = $result->fetch_assoc()){
          $newOption = [
               "value" => $optionsSelect['id_modelo'],
               "label" => $optionsSelect['nombre_modelo']
          ];
          array_push($data, $newOption);
     }

     $response = [
          "options"=> $data
     ];
     echo json_encode($response)
?>