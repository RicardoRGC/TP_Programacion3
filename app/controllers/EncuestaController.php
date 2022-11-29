<?php
require_once './models/Encuesta.php';
require_once './interfaces/IApiUsable.php';


class EncuestaController extends Encuesta implements IApiUsable
{



  public function CargarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();
    $archivo = $request->getUploadedFiles();

    if ($parametros != null && count($parametros) >= 1) {
      try {

        $codigoMesa = $parametros['codigoMesa'];
        $codigoPedido = $parametros['codigoPedido'];
        $puntuacionMesa = $parametros['puntuacionMesa'];
        $puntuacionRestaurante = $parametros['puntuacionRestaurante'];
        $puntuacionMozo = $parametros['puntuacionMozo'];
        $puntuacionCocinero = $parametros['puntuacionCocinero'];
        $detalles = $parametros['detalles'];

        $encuesta = new Encuesta();

        $encuesta->codigoMesa = $codigoMesa;
        $encuesta->codigoPedido = $codigoPedido;
        $encuesta->puntuacionMesa = $puntuacionMesa;
        $encuesta->puntuacionRestaurante = $puntuacionRestaurante;
        $encuesta->puntuacionMozo = $puntuacionMozo;
        $encuesta->puntuacionCocinero = $puntuacionCocinero;
        $encuesta->detalles = $detalles;

        $id = $encuesta->crearEncuesta();


        $payload = json_encode(array("mensaje" => "Creado con exito id: $id,codigoPedido: $codigoPedido "));
      } catch (Exception $e) {

        $payload = json_encode(array('error' => $e->getMessage()));
      }
    } else {
      $payload = json_encode('error no hay datos');
    }


    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  //-----------------------------------------------------------------------------------------------------------
  public function CargarFoto($request, $response, $args)
  {

    $parametros = $request->getParsedBody();
    $archivo = $request->getUploadedFiles();

    if ($parametros != null && count($parametros) >= 1) {
      try {

        $codigoPedido = $parametros['codigoPedido'];

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $usuario = AutentificadorJWT::ObtenerData($token);
        try {
          $foto = $archivo['foto'];
          if (is_null($foto) || $foto->getClientMediaType() == "") {
            throw new Exception("No file");
          }
          $ext = $foto->getClientMediaType();
          var_dump($ext);
          $ext = explode("/", $ext)[1];
          $ruta = "./pedido/"  . $usuario->id . "-" . $codigoPedido . "." . $ext;
          $foto->moveTo($ruta);
        } catch (Exception $e) {
          echo "no se pudo subir la imagen";
          $ruta = "";
        }
        // Creamos el usuario
        $usr = new Encuesta();
        $usr->codigoPedido = $codigoPedido;
        $usr->foto = $ruta;

        // $usr->modificarFotoPedido();

        $payload = json_encode(array("mensaje" => "Se cargo la imagen"));
      } catch (Exception $e) {

        $payload = json_encode(array('error' => $e->getMessage()));
      }
    } else {
      $payload = json_encode('error no hay datos');
    }


    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  //-----------------------------------------------------------------------------------
  //-----------------------------------------------------------------------------------
  public function TraerUno($request, $response, $args)
  {
    // // $nombre = $args['codigoPedido'];
    // $parametros = $request->getQueryParams();
    // $producto = Encuesta::obtenerPedidoCliente($parametros['codigoPedido']);
    // $payload = json_encode($producto);

    // $response->getBody()->write($payload);
    // return $response
    //   ->withHeader(
    //     'Content-Type',
    //     'application/json'
    //   );
  }
  //----------------------------------------------------------------------------------------------------------------------------------
  //-----------------------------------------------------------------------------------
  public function TraerUnoDetalles($request, $response, $args)
  {
    // // $nombre = $args['codigoPedido'];
    // $parametros = $request->getQueryParams();
    // $producto = Encuesta::obtenerPedidoDetalles($parametros['codigoPedido']);
    // $payload = json_encode($producto);

    // $response->getBody()->write($payload);
    // return $response
    //   ->withHeader(
    //     'Content-Type',
    //     'application/json'
    //   );
  }
  //----------------------------------------------------------------------------------------------------------------------------------

  public function TraerTodos($request, $response, $args)
  {
    $lista = Encuesta::obtenerTodos();
    $payload = json_encode(array("ListaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }


  ///MODIFICAR----------------------------------------------------------------------------------
  public function ModificarUno($request, $response, $args)
  {

    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);
    $esValido = false;
    try {
      AutentificadorJWT::verificarToken($token);
      $esValido = true;
    } catch (Exception $e) {
      $payload = json_encode(array('error' => $e->getMessage()));
    }

    if ($esValido) {
      $parametros = $request->getParsedBody();
      if ($parametros != null) {

        var_dump($parametros);

        $nombre = $parametros['nombre'];
        $clave = $parametros['clave'];
        $id = $parametros['id'];

        $usr = new Usuario();
        $usr->usuario = $nombre;
        $usr->clave = $clave;
        $usr->id = $id;

        // $usr->modificarUsuario();

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
      } else {
        $payload = json_encode("error de datos");
      }
    }
    // -----------------------------------------------------------


    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();


    $usuarioId = $parametros['usuarioId'];
    // Usuario::borrarUsuario($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
}
