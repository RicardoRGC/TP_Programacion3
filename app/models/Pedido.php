<?php

class Pedido
{

    public $codigoPedido; //5digitos
    public $codigoMesa;
    public $demoraPedido;
    public $estado;
    public $idUsuario;
    public $foto;
    public $precioPedido;



    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigoPedido,codigoMesa, idUsuario,demoraPedido,estado,foto,precioPedido) VALUES (:codigoPedido,:codigoMesa,:idUsuario,:demoraPedido,:estado, :foto, :precioPedido)");
        $consulta->bindValue(':codigoMesa', $this->codigoMesa, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido, PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $this->idUsuario, PDO::PARAM_INT);
        $consulta->bindValue(':demoraPedido', $this->demoraPedido);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':foto', $this->foto);
        $consulta->bindValue(':precioPedido', $this->precioPedido);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT   codigoPedido ,codigoMesa, idUsuario,demoraPedido,estado,foto,precioPedido FROM pedidos ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT  id as codigoPedido ,codigoMesa, idUsuario,demoraPedido,estado,foto,precioPedido FROM pedidos ");
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

    public static function obtenerPedido($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,precioPedido, codigoPedido, foto ,demoraPedido,codigoMesa,idUsuario FROM pedidos WHERE codigoPedido = :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    public static function obtenerPedidoNacionalidad($demoraPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,precioPedido, codigoMesa, foto ,demoraPedido FROM pedidos WHERE demoraPedido = :demoraPedido");
        $consulta->bindValue(':demoraPedido', $demoraPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET usuario = :usuario, foto = :foto WHERE id = :id");
        $fotoHash = password_hash($this->foto, PASSWORD_DEFAULT);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $fotoHash);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    public function modificarPrecioPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET precioPedido = :precioPedido WHERE id = :id");
        $consulta->bindValue(':precioPedido', $this->precioPedido);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
