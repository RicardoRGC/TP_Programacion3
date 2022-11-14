<?php


class LoginControllers extends Usuario
{

    public function Verificar($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if ($parametros != null) {
            $nombre = $parametros['nombre'];
            $clave = $parametros['clave'];


            $usuario = Usuario::obtenerUsuario($nombre);

            if (password_verify($clave, $usuario->clave)) {
                $mensaje = 'Password is valid!';

                $datos = array('nombre' => $parametros['nombre'], 'tipo' => $usuario->tipo, 'id' => $usuario->id); //crea el token con nombre y tipo
                //creo el token con los datos del usuario
                $token = AutentificadorJWT::CrearToken($datos);

                $payload = json_encode(array('OK' => $mensaje, 'jwt' => $token, 'tipo' => $usuario->tipo));

                $response->getBody()->write($payload);
                return $response
                    ->withHeader(
                        'Content-Type',
                        'application/json'
                    );
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
