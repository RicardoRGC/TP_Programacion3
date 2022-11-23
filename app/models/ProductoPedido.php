<?php

class ProductoPedido
{

    public $id;
    public $codigoPedido;
    public $idProducto;
    public $cantidad;
    public $demora;
    public $estado;
    public $fecha_alta;
    public $fecha_baja;

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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productospedidos.id, productospedidos.codigoPedido,productospedidos.cantidad, productospedidos.estado, productos.nombre, productos.tipo FROM productospedidos inner join productos WHERE productos.id=productospedidos.idProducto");
        $consulta->execute();

        // return $consulta->fetch(PDO::FETCH_OBJ);
        return $consulta->fetchAll(PDO::FETCH_NAMED);
        // return $consulta->fetch(PDO::FETCH_LAZY); 
    }
    public static function obtenerComida()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productospedidos.id, productospedidos.codigoPedido,productospedidos.cantidad, productospedidos.estado, productos.nombre, productos.tipo FROM productospedidos inner join productos WHERE productos.id=productospedidos.idProducto and productos.tipo='comida'");
        $consulta->execute();

        // return $consulta->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_GROUP);
        // return $consulta->fetch(PDO::FETCH_ASSOC);
        return $consulta->fetchAll(PDO::FETCH_NAMED);
    }
    public static function obtenerBebidas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productospedidos.id, productospedidos.codigoPedido,productospedidos.cantidad, productospedidos.estado, productos.nombre, productos.tipo FROM productospedidos inner join productos WHERE productos.id=productospedidos.idProducto and productos.tipo='comida'");
        $consulta->execute();

        // return $consulta->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_GROUP);
        // return $consulta->fetch(PDO::FETCH_ASSOC);
        return $consulta->fetchAll(PDO::FETCH_NAMED);
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
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario,  FROM ProductoPedido WHERE fecha_baja is not null ");
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
    public static function obtenerDemoraProductoPedido($codigoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT MAX(demora) MAXIMA_DEMORA
        FROM productospedidos WHERE productospedidos.codigoPedido= :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_LAZY);
    }


    public static function obtenerEstadoProductosPedidos($codigoPedido) //Busca en todos los pedidos si hay un pendiente
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productospedidos.estado FROM productospedidos WHERE productospedidos.codigoPedido= :codigoPedido");
        $consulta->bindValue(':codigoPedido', $codigoPedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_NAMED);
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

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consult = $objAccesoDatos->prepararConsulta("SELECT productospedidos.id, productospedidos.codigoPedido,productospedidos.cantidad, productospedidos.estado, productos.nombre, productos.tipo FROM productospedidos inner join productos WHERE productospedidos.id = :id ");
        $consult->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consult->execute();

        // return $consulta->fetchAll(PDO::FETCH_COLUMN | PDO::FETCH_GROUP);
        // return $consulta->fetch(PDO::FETCH_ASSOC);
        return $consult->fetchAll();

        // return $consulta->fetch();
    }

    public static function borrarProductoPedido($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE ProductoPedido SET fecha_baja = :fecha_baja WHERE id = :id");
        date_default_timezone_set('	America/Argentina/Buenos_Aires');
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->execute();
    }
}
