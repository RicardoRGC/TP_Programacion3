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

use function DI\add;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/AutentificadorJWT.php';
require_once './db/AccesoDatos.php';
// require_once './middlewares/Logger.php';
require_once './middlewares/SalidaMiddlewares.php';
require_once './middlewares/EntradaMiddlewares.php';
require_once './middlewares/VerificarMiddleware.php';
require_once './middlewares/VerificarSocioMiddleware.php';
require_once './middlewares/VerificarMozosMiddleware.php';
require_once './middlewares/VerificarEncargadosProducPediMiddleware.php';
require_once './middlewares/VerificarCerveceroMiddleware.php';
require_once './middlewares/GuardarCsvMiddlewares.php';

require_once './controllers/EncuestaController.php';
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
    $group->get('[/]', \UsuarioController::class . ':TraerTodos')->add(new VerificarSocioMiddleware());
    $group->post('[/Alta]', \UsuarioController::class . ':CargarUno');
    $group->put('[/modificar]', \UsuarioController::class . ':ModificarUno');
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
  }
);
$app->group(
  '/pedidos',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':TraerTodos');
    $group->get('/codigoPedido', \PedidoController::class . ':TraerUno');
    $group->get('/PrecioFinal', \PedidoController::class . ':TraerPrecioPedido');
    $group->get('/listos', \PedidoController::class . ':TraerPedidosListos');
    $group->get('/mesa', \PedidoController::class . ':TraerPedidoPorMesa');
    $group->post('[/Alta]', \PedidoController::class . ':CargarUno'); //cargar
    $group->post('/cargarFoto', \PedidoController::class . ':CargarFoto'); //cargar
    $group->put('[/modificar]', \PedidoController::class . ':ModificarUno');
    $group->delete('[/]', \PedidoController::class . ':BorrarUno');
  }
)->add(
  new VerificarMozosMiddleware()
);
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
$app->group(
  '/productosPedidos',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoPedidoController::class . ':TraerTodos')->add(new VerificarEncargadosProducPediMiddleware());
    $group->get('/comida', \ProductoPedidoController::class . ':TraerComida')->add(new VerificarEncargadosProducPediMiddleware());
    $group->get('/bebida', \ProductoPedidoController::class . ':TraerBebida')->add(new VerificarCerveceroMiddleware());
    $group->post('[/productoPedido]', \ProductoPedidoController::class . ':CargarUno')->add(new VerificarMozosMiddleware());
    $group->put('[/modificarEstado]', \ProductoPedidoController::class . ':ModificarEstado')
      ->add(new VerificarEncargadosProducPediMiddleware());
    $group->delete('[/]', \ProductoPedidoController::class . ':BorrarUno');
  }
);
//--------------------------------------------------------------------------------


//--------------------------------------------------------------------------------
$app->group(
  '/mesas',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':TraerTodos');
    $group->post('[/Alta]', \MesaController::class . ':CargarUno')->add(new VerificarSocioMiddleware());
    $group->put('[/estadoMesaLibre]', \MesaController::class . ':ModificarEstadoLibre');
    $group->delete('[/]', \MesaController::class . ':BorrarUno')->add(new VerificarSocioMiddleware());
  }
)->add(
  new VerificarMozosMiddleware()
);
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
$app->group(
  '/encuesta',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \EncuestaController::class . ':TraerTodos')->add(new VerificarSocioMiddleware());
    $group->post('[/cargar]', \EncuestaController::class . ':CargarUno');
    $group->put('[/estadoMesaLibre]', \EncuestaController::class . ':ModificarEstadoLibre');
    $group->delete('[/]', \EncuestaController::class . ':BorrarUno');
  }
);
//--------------------------------------------------------------------------------
//--------------------------------------------------------------------------------
$app->group(
  '/productos',
  function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':TraerTodos') /*->add(new VerificarSocioMiddleware())*/;
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/Alta]', \ProductoController::class . ':CargarUno'); //cargar
    $group->put('[/modificar]', \UsuarioController::class . ':ModificarUno');
    $group->delete('[/]', \UsuarioController::class . ':BorrarUno');
  }
)->add(new VerificarMozosMiddleware());
//--------------------------------------------------------------------------------
$app->post('/login', \LoginControllers::class . ':Verificar'); //Clave ,usuario(verificar usuario)
$app->get('/codigoPedido', \PedidoController::class . ':TraerUno');
$app->get(
  '[/]',
  function (Request $request, Response $response) {
    $response->getBody()->write("Pagina RGraf");
    return $response;
  }
);

$app->run();
