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
    // var_dump($lista);
    $payload = json_encode(array("ProductosPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  public function TraerComida($request, $response, $args)
  {
    $lista = ProductoPedido::obtenerComida();
    // var_dump($lista);
    $payload = json_encode(array("ProductosPedidos" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  public function TraerBebida($request, $response, $args)
  {
    $lista = ProductoPedido::obtenerBebidas();
    // var_dump($lista);
    $payload = json_encode(array("ProductosPedidos" => $lista));

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
      $id = $parametros['id'];

      $estado = $parametros['estado'];

      if ($estado == "preparando" || $estado == "listo") {



        $demora = $parametros['demora'];
        $codigoPedido = $parametros['codigoPedido']; //buscar la demora en PRoductoPEdidos
        $pedido = new Pedido();

        $productoPedido = new ProductoPedido(); //MODIFICO EL ESTADO DEL PEDIDO
        $productoPedido->id = $id;
        $productoPedido->estado = $estado;
        $productoPedido->demora = $demora;
        if ($estado == 'listo')
          $productoPedido->demora = 0;
        $productoPedido->modificarEstadoProductoPedido();

        $demoraPedido = ProductoPedido::obtenerDemoraProductoPedido($codigoPedido);
        $estadoPedidos = ProductoPedido::obtenerEstadoProductosPedidos($codigoPedido);
        // var_dump($estadoPedidos);
        $estado = 'listo';
        foreach ($estadoPedidos as $key => $value) {
          // var_dump($value['estado']);
          if ($value['estado'] == 'pendiente') {
            $estado = $value['estado'];
            break;
          }
          if (($value['estado']) == 'preparando') {
            $estado = $value['estado'];
            break;
          }
        }

        $pedido->estado = $estado;
        $pedido->demoraPedido = $demoraPedido->MAXIMA_DEMORA;
        $pedido->codigoPedido = $codigoPedido;
        var_dump($pedido);
        $pedido->modificarEstadoDemoraPedido();


        // $usr->modificarUsuario();

        $payload = json_encode(array("mensaje" => "modificado con exito"));
      } else {

        $payload = json_encode(array("mensaje" => "estado Incorrecto"));
      }
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
