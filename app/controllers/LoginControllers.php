<?php
// include_once('RegistroLogin.php');
require_once './models/RegistroLogin.php';
require_once './models/ArchivoCSV.php';

class LoginControllers extends Usuario
{
    public function GuardarRegistrosCsv($request, $response, $args)
    {
        $lista = RegistroLogin::obtenerTodos();

        if (!empty($lista)) {

            ArchivoCSV::GuardarCsv($lista);
            $retorno = ArchivoCSV::leerCsv();
            $payload = json_encode(array('RegistroGuardado' => $retorno));
        } else {

            $payload = json_encode(array('OK' => "NO hay nada para guardar"));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
    public function CargarRegistrosCsv($request, $response, $args)
    {
        $retorno = ArchivoCSV::leerCsv();

        $payload = json_encode(array('Registros' => $retorno));

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
    public function CargarRegistrosCsvSubirDB($request, $response, $args)
    {
        $retorno = ArchivoCSV::leerCsv_SubirDB();


        $payload = json_encode(array('Registros' => $retorno));

        $response->getBody()->write($payload);
        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }

    public function Verificar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if ($parametros != null) {
            $nombre = $parametros['nombre'];
            $clave = $parametros['clave'];

            $usuario = Usuario::obtenerUsuario($nombre);

            // var_dump($usuario->fecha_baja);
            if (password_verify($clave, $usuario->clave)) {
                $mensaje = 'Password is valid!';
                if (is_null($usuario->fecha_baja)) {

                    $datos = array('nombre' => $parametros['nombre'], 'tipo' => $usuario->tipo, 'id' => $usuario->id); //crea el token con nombre y tipo
                    //creo el token con los datos del usuario
                    $token = AutentificadorJWT::CrearToken($datos);

                    //crear un registro de logeo 
                    $registro = new RegistroLogin();
                    $registro->idUsuario = $usuario->id;
                    $registro->tipo = $usuario->tipo;
                    $registro->nombre = $usuario->nombre;

                    $registro->crearLogin();


                    $payload = json_encode(array('OK' => $mensaje, 'jwt' => $token, 'tipo' => $usuario->tipo));

                    $response->getBody()->write($payload);
                    return $response
                        ->withHeader(
                            'Content-Type',
                            'application/json'
                        );
                } else {
                    $mensaje = 'Usted No puede Ingresar';
                }
            } else {
                $mensaje = 'Invalid password.';
            }
        } else {
            $mensaje = "Nada q mostrar";
        }

        $payload = json_encode($mensaje);

        $response->getBody()->write($payload);

        return $response
            ->withHeader(
                'Content-Type',
                'application/json'
            );
    }
}