<?php

namespace Model;

class Asistencias extends ActiveRecord{
    public static $tabla = 'asistencia';
    public static $columnasDB = [
        'id',
        'actividad_id',
        'fecha',
        'situacion'
    ];

    public static $idTabla = 'id';
    public $id;
    public $actividad_id;
    public $fecha;
    public $situacion;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->actividad_id = $args['actividad_id'] ?? null;
        $this->fecha = $args['fecha'] ?? '';
        $this->situacion = $args['situacion'] ?? '1';
    }
}