<?php

class Mesa
{

    public $id;
    public $codigoMesa;
    public $nombreCliente;
    public $estado;



    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (nombreCliente ,estado) VALUES (:nombreCliente, :estado)");
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombreCliente ,estado FROM mesas ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
    // public static function obtenerTodosBaja()
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario,  FROM mesas WHERE fechaBaja is not null ");
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    // }

    public static function obtenerMesa($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombreCliente ,estado FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function obtenerCriptoNacionalidad($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,, nombreCliente,  ,estado FROM mesas WHERE estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public function ocuparMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado, nombreCliente = :nombreCliente WHERE id = :id");
        $consulta->bindValue(':estado', 'ocupada');
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarMesa($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
