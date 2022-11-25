<?php

class Pedido
{

    public $codigoPedido; //5digitos
    public $codigoMesa;
    public $demoraPedido;
    public $estado;
    public $idUsuario;
    public $foto;
    public $fecha_alta;
    public $fecha_baja;


    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoPedido,codigoMesa, idUsuario,demoraPedido,estado,foto,fecha_alta) VALUES (:codigoPedido,:codigoMesa,:idUsuario,:demoraPedido,:estado, :foto,:fecha_alta)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':demoraPedido', $this->demoraPedido);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':foto', $this->foto);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_alta', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT   codigoPedido ,codigoMesa, idUsuario,demoraPedido,estado,foto,fecha_alta,fecha_baja FROM pedidos ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerTodosArrayAsoc()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT   codigoPedido ,codigoMesa, idUsuario,demoraPedido,estado,foto FROM pedidos ");
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    public static function obtenerPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT codigoPedido ,codigoMesa, idUsuario,demoraPedido,estado,foto FROM pedidos ");
        $consulta->execute();
        // return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
        return $consulta->fetch(PDO::FETCH_LAZY);
    }
    public static function obtenerTodosBaja()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, foto FROM pedidos WHERE fechaBaja is not null ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPrecioPedido($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT SUM(productospedidos.cantidad * productos.precio) as 'importe' from productospedidos 
        inner join productos on productospedidos.idProducto =productos.id WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();


        return $consulta->fetch(PDO::FETCH_ASSOC);
        // return $consulta->fetchObject('Pedido');
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    public static function obtenerPedidoCliente($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT  codigoPedido,demoraPedido,codigoMesa,idUsuario FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        // return $consulta->fetchObject('Pedido');
        // return $consulta->fetch(PDO::FETCH_LAZY);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    public static function obtenerPedidosListos($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT  codigoPedido,demoraPedido,codigoMesa,idUsuario FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        // return $consulta->fetchObject('Pedido');
        // return $consulta->fetch(PDO::FETCH_LAZY);
        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
    //-----------------------------------------------------------------------------------------------------------------------------------------------
    public static function obtenerPedidoNacionalidad($demoraPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, codigoMesa, foto ,demoraPedido FROM pedidos WHERE demoraPedido = :demoraPedido");
        $consulta->bindValue(':demoraPedido', $demoraPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public function modificarEstadoDemoraPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado, demoraPedido = :demoraPedido WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':demoraPedido', $this->demoraPedido, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->execute();
    }
    public function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET demoraPedido = :demoraPedido WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':demoraPedido', $this->demoraPedido, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->execute();
    }
    public function modificarFotoPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET foto = :foto WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':foto', $this->foto);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_INT);
        $consulta->execute();
    }
    // public function modifica()
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET =  WHERE id = :id");
    //     $consulta->bindValue('', $this-);
    //     $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    //     $consulta->execute();
    // }

    public static function borrarPedido($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fecha_baja = :fecha_baja WHERE id = :id");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        date_default_timezone_set('	America/Argentina/Buenos_Aires');
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
