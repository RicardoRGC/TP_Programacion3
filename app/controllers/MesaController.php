<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    if ($parametros != null && count($parametros) == 1) {
      try {
        // var_dump($parametros);

        $estado = $parametros['estado'];
        // Creamos el usuario
        $usr = new Mesa();
        $usr->estado = $estado;

        $id = $usr->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito id: $id "));
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
    $usr = $args['usuario'];
    $usuario = Mesa::obtenerMesa($usr);
    $payload = json_encode($usuario);

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::obtenerTodos();
    $payload = json_encode(array("listaMesa" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
  public function TraerMesaMasUtilizada($request, $response, $args)
  {
    $lista = Mesa::obtenerMesaMasUtilizada();
    $payload = json_encode(array("MesaMasUtilizada" => $lista));

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

    $parametros = $request->getParsedBody();

    if ($parametros != null) {


      // $nombreCliente = $parametros['nombreCliente'];
      // $estado = $parametros['estado'];
      $idMesa = $parametros['id'];

      $usr = new Mesa();
      $usr->id = $idMesa;

      $usr->ocuparMesa();

      $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
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
  public function ModificarEstadoLibre($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    if ($parametros != null) {


      // $nombreCliente = $parametros['nombreCliente'];
      // $estado = $parametros['estado'];
      $idMesa = $parametros['idMesa'];

      $usr = new Mesa();
      $usr->id = $idMesa;

      $usr->mesaEstadoLibre();

      $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
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
  public function ModificarEstadoComiendo($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    if ($parametros != null) {


      // $nombreCliente = $parametros['nombreCliente'];
      // $estado = $parametros['estado'];
      $idMesa = $parametros['idMesa'];

      $usr = new Mesa();
      $usr->id = $idMesa;

      $usr->mesaEstadoComiendo();

      $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
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
  public function ModificarEstadoCerrada($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    if ($parametros != null) {


      // $nombreCliente = $parametros['nombreCliente'];
      // $estado = $parametros['estado'];
      $idMesa = $parametros['idMesa'];

      $usr = new Mesa();
      $usr->id = $idMesa;

      $usr->mesaEstadoCerrada();

      $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
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

  public function BorrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();


    $usuarioId = $parametros['usuarioId'];
    Mesa::borrarMesa($usuarioId);

    $payload = json_encode(array("mensaje" => "Mesa borrado con exito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader(
        'Content-Type',
        'application/json'
      );
  }
}
