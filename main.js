let selectMarca = $('#marca');
let selectModelo = $('#modelo');
let optionsms = $('#option-sms');
let count = 0;

// Mostrando por defecto un mensaje en el select de modelos
optionsms.text('Selecciona');

// Detectando cuando se cambia el input de marca
selectMarca.on('input', function(){
     const marca = selectMarca.val();
     // Desabilitando el select mientras cargan los datos 
     selectModelo.prop('disabled', true);
     // Eliminando los options actuales para que no se dupliquen
     eliminarOptionsAntiguos();
     // Mandando peticion para saber que marca seleccionó
     $.ajax({
          url: 'options-select.php',
          data: { marca },
          type: 'GET',
          dataType: 'json',
          success : function(response){
               //Volviedo a habilitar el select
               selectModelo.prop('disabled', false);
               const options = response.options;
               // Cambiando la variable que cuenta los options
               count = options.length+2;
               // Creando las opciones segun la marca seleccionada
               options.map((item, index) => {
                    // Creando el elemento option y asignandole datos
                    const option = document.createElement("option");
                    option.textContent = item.label;
                    option.value = item.value;
                    option.id = "op" + index;
                    return (
                         document.getElementById('modelo').insertAdjacentElement("afterend", option)
                    );
               });
               // Desabilitando el option 'selecciona'
               optionsms.prop('disabled', true);
          }
     })
})

function eliminarOptionsAntiguos(){
     for(let i = 0; i<count; i++){
          const idOption = `op${i}`;
          document.getElementById(idOption).remove();
     }
}