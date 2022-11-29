<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class GuardarCsvMiddlewares
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);

        $pedidos = Pedido::obtenerTodos();
        // var_dump($pedidos);
        $fp = fopen('fichero.csv', 'w');
        fwrite($fp, "id,idUsuario,nombre,tipo,fechaIngreso" . PHP_EOL);
        foreach ($pedidos as $key => $value) {
            $cant = fwrite($fp, "$value->codigoPedido, $value->codigoMesa , $value->estado " . PHP_EOL);
            // var_dump($value);
        }




        fclose($fp);



        $mensaje = "despues";
        $respuestas = ['respuesta' => $mensaje];
        // $existingContent = (string) $response->getBody();


        $response->getBody()->write(json_encode($respuestas, true));

        return $response;
    }
}