<?php
require_once './models/ProductoPedido.php';
require_once './interfaces/IApiUsable.php';


class ProductoPedidoController extends ProductoPedido implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    if ($parametros != null && count($parametros) >= 1) {
      try {
        // var_dump($parametros);
        $codigoPedido = $parametros['codigoPedido'];
        $idProducto = $parametros['idProducto'];
        $demora = 'pendiente';
        $estado = 'pendiente';
        $cantidad = $parametros['cantidad'];

        // Creamos el Producto PEDIDO
        $producto = new ProductoPedido();

        $producto->codigoPedido = $codigoPedido;
        $producto->idProducto = $idProducto;
        $producto->cantidad = $cantidad;
        $id = $producto->crearProductoPedido();

        // CARGAR PRECIO EN EL PEDIDO.
        $precio = Producto::obtenerPrecioProducto($idProducto);
        $pedido = Pedido::obtenerPedido($codigoPedido);

        // var_dump($precio->precio);

        $pedido->precioPedido = $pedido->precioPedido + ($precio->precio * $cantidad);
        var_dump($pedido->precioPedido);

        $pedido->modificarPrecioPedido();

        // var_dump($precio);

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
  public function ModificarEstado($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    if ($parametros != null) {
      $id = $parametros['id']; //traer el producto pedido

      $estado = $parametros['estado'];
      $demora = $parametros['demora'];

      $producto = new ProductoPedido();
      $producto->id = $id;
      $producto->estado = $estado;
      $producto->demora = $demora;



      $producto->modificarEstadoProductoPedido();

      // $usr->modificarUsuario();

      $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
    } else {
      $payload = json_encode("error de datos");
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
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
