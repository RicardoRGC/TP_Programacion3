<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/AutentificadorJWT.php';
require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';
require_once './middlewares/SalidaMiddlewares.php';
require_once './middlewares/EntradaMiddlewares.php';
require_once './middlewares/VerificarMiddleware.php';
require_once './middlewares/VerificarAdminMiddleware.php';

require_once './controllers/ProductoPedidoController.php';
require_once './controllers/ProductoControllers.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/LoginControllers.php';

// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

// Routes
$app->group(
  '/usuarios',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos') /*->add(new VerificarAdminMiddleware())*/;
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/Alta]', \UsuarioController::class . ':CargarUno'); //cargar
    $group->put('[/modificar]', \UsuarioController::class . ':ModificarUno');
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
  }
) /*->add(
 new VerificarMiddleware()
 )*/;
//--------------------------------------------------------------------------------
$app->group(
  '/productos',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos') /*->add(new VerificarAdminMiddleware())*/;
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/Alta]', \ProductoController::class . ':CargarUno'); //cargar
    $group->put('[/modificar]', \UsuarioController::class . ':ModificarUno');
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
  }
) /*->add(
 new VerificarMiddleware()
 )*/;
//--------------------------------------------------------------------------------
$app->group(
  '/mesas',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos') /*->add(new VerificarAdminMiddleware())*/;
    $group->get('/{usuario}', \MesaController::class . ':TraerUno');
    $group->post('[/Alta]', \MesaController::class . ':CargarUno'); //cargar
    $group->put('[/modificar]', \MesaController::class . ':ModificarUno');
    $group->delete('[/]', \MesaController::class . ':BorrarUno');
  }
) /*->add(
 new VerificarMiddleware()
 )*/;
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
$app->group(
  '/pedidos',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos') /*->add(new VerificarAdminMiddleware())*/;
    $group->get('/{usuario}', \PedidoController::class . ':TraerUno');
    $group->post('[/Alta]', \PedidoController::class . ':CargarUno'); //cargar
    $group->put('[/modificar]', \PedidoController::class . ':ModificarUno');
    $group->delete('[/]', \PedidoController::class . ':BorrarUno');
  }
) /*->add(
 new VerificarMiddleware()
 )*/;
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
$app->group(
  '/productosPedidos',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos') /*->add(new VerificarAdminMiddleware())*/;
    $group->get('/{usuario}', \PedidoController::class . ':TraerUno');
    $group->post('[/productoPedido]', \ProductoPedidoController::class . ':CargarUno'); //cargar
    $group->put('[/modificar]', \PedidoController::class . ':ModificarUno');
    $group->delete('[/]', \PedidoController::class . ':BorrarUno');
  }
) /*->add(
 new VerificarMiddleware()
 )*/;
//--------------------------------------------------------------------------------


$app->post('/login', \LoginControllers::class . ':Verificar'); //Clave ,usuario(verificar usuario)




$app->get(
  '[/]',
  function (Request $request, Response $response) {
    $response->getBody()->write("Pagina RGraf");
    return $response;
  }
);

$app->run();