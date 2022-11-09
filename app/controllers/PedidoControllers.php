<?php
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';


class PedidoController extends Pedido implements IApiUsable
{
  /*
    PEDIDO
    public $foto;
    public $demoraPedido;
    public $estado;
    public $codigoPedido; //5digitos  

    MESA
    public $codigoPedido;
    public $codigoMesa;
    public $estado;

    PRODUCTO
    public $precio;
    public $nombre;
    public $tipo; //comida bebida
    public $codigoPedido;
    public $demora;
    public $estado;
  */


  public function CargarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();
    $archivo = $request->getUploadedFiles();

    if ($parametros != null && count($parametros) == 6) {
      try {
        // var_dump($parametros);
        $nombre = $parametros['nombre'];

        $usuario1 = Pedido::obtenerPedido($nombre);

        if (!$usuario1) {

          $precio = $parametros['precio'];
          $nombre = $parametros['nombre'];
          $tipo = $parametros['tipo'];
          $codigoMesa = $parametros['codigoMesa'];
          $demora = $parametros['demora'];
          $estado = $parametros['estado'];


          // Creamos el usuario
          $usr = new Pedido();
          $usr->precio = $precio;
          $usr->nombre = $nombre;
          $usr->tipo = $tipo;
          $usr->codigoMesa = $codigoMesa;
          $usr->demora = $demora;
          $usr->estado = $estado;

          $id = $usr->crearPedido();

          $payload = json_encode(array("mensaje" => "Pedido creado con exito id: $id "));
        } else {
          $payload = json_encode("Pedido ya existe");
        }
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
  public function TraerUno($request, $response, $args)
  {
    // Buscamos usuario por nombre
    $nombre = $args['nombre'];
    $producto = Pedido::obtenerPedido($nombre);
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
    $lista = Pedido::obtenerTodos();
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
