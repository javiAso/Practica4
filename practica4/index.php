<?php
//Declaro las variables que necesito:

$agenda = [];
$mensaje = "";
$numcontactos = 0;

if (filter_input(0, "submit") == "Añadir contacto") {   //Si se viene de añadir un número leo POST:
    $agenda = filter_input(0, "contactos", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    manipulaAgenda($agenda, filter_input(0, "telefono"), filter_input(0, "contacto"), $mensaje);
    if (!empty($agenda)) {  //Miro si esta vacía antes de hacer el count para evitar warnings ;)
        $numcontactos = count($agenda);
    }
}

function manipulaAgenda(&$agenda, $num, $cont, &$mensaje) {

    if ($cont == "") {//Si no introducen noombre
        $mensaje .= "El campo del nombre no puede estar vacío\n";
    } else {//Nombre introducido
        if (!is_numeric($num)) {//El campo de número no contiene un valor numérico
            if ($num == "") {//El numero está vacio
                if (!is_null($agenda[trim($cont)])) {//El contacto no es nulo
                    $mensaje .= "Contacto borrado";
                    unset($agenda[trim($cont)]);
                } else {//El contacto es nulo
                    $mensaje .= "Ese contacto no existe en la agenda.";
                }
            } else {//El campo de número contiene algo que no es un número
                $mensaje .= "El campo del número debe de ser numérico";
            }
        } else {//Todo correcto
            $agenda[trim($cont)] = $num;
            $mensaje .= "Número añadido correctamente";
        }
    }
}

function hiddens($agenda) {//Con esta funcion genero los hiddens necesarios para mandar la info por POST
    if (!empty($agenda)) {
        $cadena = "";
        foreach ($agenda as $contacto => $numero) {
            $cadena .= "<input type='hidden' name='contactos[$contacto]' value='$numero'></input>";
        }
        return $cadena;
    }
}

function generaTabla($agenda) {//Genero la tabla para mostrar los contactos
    $cadena = "";

    if (!empty($agenda)) {

        $cadena .= "  <table>
        <tr>
          <td>Nombre</td>
          <td>Teléfono</td>
        </tr>";
        foreach ($agenda as $contacto => $numero) {
            $cadena .= "<tr><td>$contacto</td><td>$numero</td></tr>";
        }
        $cadena .= "</table>";
        return $cadena;
    }

    return "Agenda sin contactos";
}
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Agenda</title>
        <style type="text/css">
            h1{
                background-color: #5D6D7E;
                color: #FF8C00;
            }
            h2,legend,table{

                color: #36058B;


            }
            fieldset{

                float: left;
                width: 40%;
                background-color: #AEB6BF;
                color: blue;
            }
            div{

                float: left;
                width: 50%;
                margin-left: 5%;
                background-color: #AEB6BF;
                color: #36058B;
            }
            h4{

                color:white;

            }

            input{
                color: #A80700;
                font-size: 1em;

            }

            table{

                border-collapse: collapse;
                margin-left: 5%;
                color: black;
                font-size: 1em;
            }
            tr,td{

                border: solid 1px blue;

            }

        </style>
    </head>
    <body>
        <h1>Tienes <?= $numcontactos ?> contactos guardados en la agenda.</h1>
        <fieldset>
            <legend>Nuevo contacto</legend>
            <form action="index.php" method="POST">

                Nombre  <input type="text" name="contacto"><br>
                Telefono <input type="text" name="telefono"><br>
                <?= hiddens($agenda) ?>
                <input type="submit" name="submit" value="Añadir contacto">
                <input type="submit" name="submit" value="Eliminar contactos" <?php if (empty($agenda)) echo 'disabled' ?>>

            </form>
            <h4><?= $mensaje ?></h4>
        </fieldset>

        <div>
            <h2>LISTADO DE CONTACTOS</h2>
            <hr>
            <?= generaTabla($agenda) ?>
            <hr>

        </div>
        <hr>

    </body>
</html>
