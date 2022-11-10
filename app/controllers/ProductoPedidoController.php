<?php
require_once './models/ProductoPedido.php';
require_once './interfaces/IApiUsable.php';


class ProductoPedidoController extends ProductoPedido implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    if ($parametros != null && count($parametros) == 6) {
      try {
        // var_dump($parametros);
        $codigoPedido = $parametros['codigoPedido'];
        $precio = $parametros['precio'];
        $idProducto = $parametros['idProducto'];
        $demora = $parametros['demora'];
        $cantidad = $parametros['cantidad'];
        $estado = $parametros['estado'];


        // Creamos el usuario
        $usr = new ProductoPedido();

        $usr->codigoPedido = $codigoPedido;
        $usr->demora = $demora;
        $usr->estado = $estado;
        $usr->idProducto = $idProducto;
        $usr->precio = $precio;
        $usr->cantidad = $cantidad;

        $id = $usr->crearProductoPedido();

        $payload = json_encode(array("mensaje" => "Creado con exito id: $id "));




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
    // Buscamos usuario por nombre
    $nombre = $args['nombre'];
    $producto = ProductoPedido::obtenerPedido($nombre);
    $payload = json_encode($producto);

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  //----------------------------------------------------------------------------------------------------------------------------------
  public function TraerTodos($request, $response, $args)
  {
    $lista = ProductoPedido::obtenerTodos();
    $payload = json_encode(array("listaCripto" => $lista));

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