<?php

class ProductoPedido
{

    public $id;
    public $codigoPedido;
    public $idProducto;
    public $cantidad;
    public $demora;
    public $estado;


    public function crearProductoPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productospedidos (idProducto,cantidad,codigoPedido) VALUES (:idProducto, :cantidad,:codigoPedido)");
        $consulta->bindValue(':idProducto', $this->idProducto);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':codigoPedido', $this->codigoPedido);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precio,idProducto ,tipo ,codigoPedido,demora,estado FROM productospedidos ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }
    public static function obtenerSumaPrecios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        // select sum(cantidad) as cantidad_total_productos_vendidos from venta
        $consulta = $objAccesoDatos->prepararConsulta("SELECT sum(cantidad) as cantidad_total_productos_vendidos from venta ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }
    public static function obtenerTodosBaja()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario,  FROM ProductoPedido WHERE fechaBaja is not null ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function obtenerProductoPedido($idProducto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,precio, idProducto,tipo,codigoPedido,demora,estado FROM productospedidos WHERE idProducto = :idProducto");
        $consulta->bindValue(':idProducto', $idProducto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('ProductoPedido');
    }
    public static function obtenerProductoPedidotipo($tipo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,precio, idProducto,  ,tipo FROM productospedidos WHERE tipo = :tipo");
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('ProductoPedido');
    }

    // public function modificarProductoPedido()
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE ProductoPedido SET usuario = :usuario,  = : WHERE id = :id");
    //     $Hash = password_hash($this->, PASSWORD_DEFAULT);
    //     $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
    //     $consulta->bindValue(':', $Hash);
    //     $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    //     $consulta->execute();
    // }
    public function modificarEstadoProductoPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ProductosPedidos SET demora = :demora,estado = :estado WHERE id = :id");
        $consulta->bindValue(':demora', $this->demora);
        $consulta->bindValue(':estado', $this->estado,);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarProductoPedido($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ProductoPedido SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
