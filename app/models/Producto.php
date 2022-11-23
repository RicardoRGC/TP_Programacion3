<?php

class Producto
{
    public $id;
    public $nombre;
    public $precio;
    public $tipo;
    public $fecha_alta;
    public $fecha_baja;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, precio,tipo,fecha_alta) VALUES (:nombre, :precio, :tipo,:fecha_alta)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio);
        date_default_timezone_set('	America/Argentina/Buenos_Aires');
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_alta', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio ,tipo FROM productos ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
    // public static function obtenerTodosBaja()
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, precio FROM productos WHERE fechaBaja is not null ");
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    // }

    public static function obtenerProducto($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, precio ,tipo FROM productos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }
    public static function obtenerPrecioProducto($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT precio FROM productos WHERE id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        // return $consulta->fetchObject('Producto');
        return $consulta->fetch(PDO::FETCH_LAZY);
    }

    // public function modificarProducto()
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET usuario = :usuario, precio = :precio WHERE id = :id");
    //     $precioHash = password_hash($this->precio, PASSWORD_DEFAULT);
    //     $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
    //     $consulta->bindValue(':precio', $precioHash);
    //     $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    //     $consulta->execute();
    // }

    public static function borrarProducto($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET fechaBaja = :fechaBaja WHERE id = :id");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        date_default_timezone_set('	America/Argentina/Buenos_Aires');
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
