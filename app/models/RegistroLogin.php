<?php

class RegistroLogin
{
    public $id;
    public $idUsuario;
    public $nombre;
    public $tipo;
    public $fechaIngreso;
    public function crearLogin()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO registros_login (nombre, idUsuario,tipo,fechaIngreso) VALUES (:nombre, :idUsuario, :tipo,:fechaIngreso)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':idUsuario', $this->idUsuario);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        /*
        $fecha = new DateTime();
        echo $fecha->format('Y-m-d H:i:sP') . "\n";
        */
        if (is_null($this->fechaIngreso)) {
            $fecha = new DateTime();
            $consulta->bindValue(':fechaIngreso', $fecha->format('Y-m-d H:i:sP'));

        } else {
            $consulta->bindValue(':fechaIngreso', $this->fechaIngreso);
        }
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, idUsuario,tipo,fechaIngreso FROM registros_login ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'RegistroLogin');
    }
    // public static function obtenerTodosBaja()
    // {
    //     $objAccesoDatos = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, clave FROM usuarios WHERE fecha_baja is not null ");
    //     $consulta->execute();

    //     return $consulta->fetchAll(PDO::FETCH_CLASS, 'RegistroLogin');
    // }

    public static function obtenerUsuario($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, clave ,tipo,fecha_alta FROM usuarios WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('RegistroLogin');
    }

    // public function modificarUsuario()
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, clave = :clave WHERE id = :id");
    //     $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
    //     $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
    //     $consulta->bindValue(':clave', $claveHash);
    //     $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
    //     $consulta->execute();
    // }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fecha_baja = :fecha_baja WHERE id = :id");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        date_default_timezone_set('	America/Argentina/Buenos_Aires');
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':fecha_baja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}