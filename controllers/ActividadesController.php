<?php

namespace Controllers;

use Exception; 
use Model\Actividades; 
use Model\ActiveRecord;
use MVC\Router;

class ActividadesController extends ActiveRecord {
    public static function renderizarPagina(Router $router){
        $router->render('actividades/index', []);   
    }

    public static function guardarAPI(){
        header('Content-Type: application/json; charset=utf-8');

        $_POST['nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['nombre']))));
        if(empty($_POST['nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la actividad es obligatorio'
            ]);
            return;
        }

        $_POST['fecha'] = date('Y-m-d H:i:s', strtotime($_POST['fecha']));
        if(empty($_POST['fecha'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La fecha es obligatoria'
            ]);
            return;
        }

        try {
            $nombreActividad = $_POST['nombre'];
            $sql = "SELECT COUNT(*) as total FROM actividades WHERE LOWER(nombre) = LOWER('$nombreActividad') AND situacion = '1'";
            $resultado = self::fetchArray($sql);
            
            if ($resultado[0]['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe una actividad con el nombre "' . $nombreActividad . '". Por favor ingresa un nombre diferente.'
                ]);
                return;
            }

            $actividad = new Actividades([
                'nombre' => $_POST['nombre'],
                'fecha' => $_POST['fecha'],
                'situacion' => '1'
            ]);

            $crear = $actividad->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La actividad ha sido registrada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["situacion = '1'"];

            if ($fecha_inicio) {
                $condiciones[] = "fecha >= '{$fecha_inicio} 00:00:00'";
            }

            if ($fecha_fin) {
                $condiciones[] = "fecha <= '{$fecha_fin} 23:59:59'";
            }

            $where = implode(" AND ", $condiciones);

            $sql = "SELECT * FROM actividades WHERE $where ORDER BY fecha ASC";
            
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Actividades obtenidas correctamente',
                'data' => $data
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las actividades',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');

        $id = $_POST['id'];

        if(empty($_POST['nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la actividad es obligatorio'
            ]);
            return;
        }

        if(empty($_POST['fecha'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La fecha es obligatoria'
            ]);
            return;
        }

        $_POST['fecha'] = date('Y-m-d H:i:s', strtotime($_POST['fecha']));

        try {
            $actividad = Actividades::find($id);
            
            if(!$actividad) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Actividad no encontrada'
                ]);
                return;
            }

            $nombreActividad = $_POST['nombre'];
            $sql = "SELECT COUNT(*) as total FROM actividades WHERE LOWER(nombre) = LOWER('$nombreActividad') AND situacion = '1' AND id != $id";
            $resultado = self::fetchArray($sql);
            
            if ($resultado[0]['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe otra actividad con el nombre "' . $nombreActividad . '". Por favor ingresa un nombre diferente.'
                ]);
                return;
            }
            
            $actividad->sincronizar([
                'nombre' => $_POST['nombre'],
                'fecha' => $_POST['fecha']
            ]);
            
            $actividad->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La actividad ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function eliminarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');

        if(empty($_POST['id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la actividad es obligatorio'
            ]);
            return;
        }

        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        if(!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la actividad debe ser un número válido'
            ]);
            return;
        }

        try {
            $actividad = Actividades::find($id);
            
            if(!$actividad) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Actividad no encontrada'
                ]);
                return;
            }
            
            $actividad->sincronizar([
                'situacion' => '0'
            ]);
            
            $resultado = $actividad->actualizar();
            
            if($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'La actividad ha sido desactivada correctamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo desactivar la actividad'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al desactivar la actividad',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}