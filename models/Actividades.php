<?php

namespace Model;

class Actividades extends ActiveRecord{
    public static $tabla = 'actividades';
    public static $columnasDB = [
        'id',
        'nombre',
        'fecha',
        'situacion'
    ];

    public static $idTabla = 'id';
    public $id;
    public $nombre;
    public $fecha;
    public $situacion;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->situacion = $args['situacion'] ?? '1';
    }
}