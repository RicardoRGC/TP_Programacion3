<?php

class ArchivoCSV
{
    public static function GuardarCsv($lista)
    {

        $registros = RegistroLogin::obtenerTodos();
        // var_dump($registros);
        $fp = fopen('Registros.csv', 'w');
        fwrite($fp, "id,nombre,idUsuario,tipo,fechaIngreso" . PHP_EOL);
        foreach ($registros as $key => $value) {
            $cant = fwrite($fp, "$value->id, $value->nombre , $value->idUsuario , $value->tipo , $value->fechaIngreso " . PHP_EOL);
            // var_dump($value);
        }




        fclose($fp);
    }
    static function leerCsv()
    {
        $arrayRegistro = [];

        $fila = 1;
        if (($gestor = fopen("Registros.csv", "r")) !== FALSE) {
            $datos = fgetcsv($gestor, 1000, ",");
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                $numero = count($datos);
                // echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                $fila++;
                // for ($c = 0; $c < $numero; $c++) {

                //     //  echo $datos[$c] . "<br />\n";

                // }
                $registro = new RegistroLogin();

                $registro->id = $datos[0];
                $registro->nombre = $datos[1];
                $registro->idUsuario = $datos[2];
                $registro->tipo = $datos[3];
                $registro->fechaIngreso = $datos[4];


                array_push($arrayRegistro, $registro);
            }



            fclose($gestor);
        }

        return $arrayRegistro;
    }
    static function leerCsv_SubirDB()
    {
        $arrayRegistro = [];

        $fila = 1;
        if (($gestor = fopen("Registros.csv", "r")) !== FALSE) {
            $datos = fgetcsv($gestor, 1000, ",");
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
                $numero = count($datos);
                // echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                $fila++;
                for ($c = 0; $c < $numero; $c++) {

                    //  echo $datos[$c] . "<br />\n";

                }
                $registro = new RegistroLogin();

                $registro->id = $datos[0];
                $registro->nombre = $datos[1];
                $registro->idUsuario = $datos[2];
                $registro->tipo = $datos[3];
                $registro->fechaIngreso = $datos[4];


                $registro->crearLogin();
                array_push($arrayRegistro, $registro);
            }
            fclose($gestor);
        }

        return $arrayRegistro;
    }

}