<?php
require_once './models/Cuenta.php';
require_once './interfaces/IApiUsable.php';


class CuentaController extends Cuenta implements IApiUsable
{



  public function CargarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();
    $archivo = $request->getUploadedFiles();


    if ($parametros != null && count($parametros) >= 1) {
      try {

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $codigoPedido = substr(str_shuffle($permitted_chars), 0, 5);

        $codigoMesa = $parametros['codigoMesa'];
        $nombreCliente = $parametros['nombreCliente'];

        //VERIFICAR DATOS EN LA MESA
        $mesa = Mesa::obtenerMesa($codigoMesa);
        if ($mesa->estado == 'ocupada')
          throw  new Exception("Mesa ocupada");
        $mesa->nombreCliente = $nombreCliente;
        $mesa->ocuparMesa();



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
          $ruta = "./pedido/" . $codigoMesa . "-" . $codigoPedido . "." . $ext;
          $foto->moveTo($ruta);
        } catch (Exception $e) {
          echo "no se pudo subir la imagen";
          $ruta = "";
        }
        // Creamos el usuario
        $usr = new Cuenta();
        $usr->codigoMesa = $codigoMesa;
        $usr->codigoPedido = $codigoPedido;
        $usr->demoraPedido = null;
        $usr->estado = 'pendiente';
        $usr->idUsuario = $usuario->id;
        $usr->foto = $ruta;

        $id = $usr->crearPedido();

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
        $usr = new Cuenta();
        $usr->codigoPedido = $codigoPedido;
        $usr->foto = $ruta;

        $usr->modificarFotoPedido();

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
    // $nombre = $args['codigoPedido'];
    $parametros = $request->getQueryParams();
    $producto = Cuenta::obtenerPedidoCliente($parametros['codigoPedido']);
    $payload = json_encode($producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  //----------------------------------------------------------------------------------------------------------------------------------
  //-----------------------------------------------------------------------------------
  public function TraerUnoDetalles($request, $response, $args)
  {
    // $nombre = $args['codigoPedido'];
    $parametros = $request->getQueryParams();
    $producto = Cuenta::obtenerPedidoDetalles($parametros['codigoPedido']);
    $payload = json_encode($producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  //----------------------------------------------------------------------------------------------------------------------------------
  public function TraerPrecioPedido($request, $response, $args)
  {

    $parametros = $request->getQueryParams();

    $codigoPedido = $parametros['codigoPedido'];

    $lista = Cuenta::obtenerPrecioPedido($codigoPedido);
    $payload = json_encode(array("PrecioPedido" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  public function TraerTodos($request, $response, $args)
  {
    $lista = Cuenta::obtenerTodos();
    $payload = json_encode(array("ListaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  public function TraerPedidosListos($request, $response, $args)
  {
    $lista = Cuenta::obtenerPedidosListos();
    $payload = json_encode(array("ListaPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  public function TraerTodospedidos($request, $response, $args)
  {
    $lista = Cuenta::obtenerPedidos();
    $payload = json_encode(array("listaPedidos" => $lista));

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
