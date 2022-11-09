<?php

class Pedido
{ //2-(POST)Alta cripto moneda( precio, nombre, foto, nacionalidad)->solo admin/(JWT)

    public $nombre;
    public $foto;
    public $demoraPedido;
    public $estado;
    public $codigoPedido; //5digitos


    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO criptos (precio,nombre, foto,nacionalidad) VALUES (:precio,:nombre, :foto, :nacionalidad)");
        $consulta->bindValue(':precio', $this->precio);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, precio,nombre, foto ,nacionalidad FROM criptos ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerTodosBaja()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, usuario, foto FROM usuarios WHERE fechaBaja is not null ");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPedido($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,precio, nombre, foto ,nacionalidad FROM criptos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    public static function obtenerPedidoNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id,precio, nombre, foto ,nacionalidad FROM criptos WHERE nacionalidad = :nacionalidad");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET usuario = :usuario, foto = :foto WHERE id = :id");
        $fotoHash = password_hash($this->foto, PASSWORD_DEFAULT);
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $fotoHash);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
