<?php

namespace Controllers;

use Exception;
use Model\Asistencias;
use Model\Actividades;
use Model\ActiveRecord;
use MVC\Router;

class AsistenciasController extends ActiveRecord {
    
    public static function renderizarPagina(Router $router){
        $actividades = Actividades::all();
        $router->render('asistencias/index', [
            'actividades' => $actividades
        ]);   
    }

    public static function guardarAPI(){
        header('Content-Type: application/json; charset=utf-8');

        if(empty($_POST['actividad_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una actividad'
            ]);
            return;
        }

        $_POST['actividad_id'] = filter_var($_POST['actividad_id'], FILTER_VALIDATE_INT);
        if(!$_POST['actividad_id']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una actividad válida'
            ]);
            return;
        }

        try {
            date_default_timezone_set('America/Guatemala');
            
            $sql = "SELECT COUNT(*) as total FROM asistencia WHERE actividad_id = " . $_POST['actividad_id'] . " AND situacion = '1'";
            $resultado = self::fetchArray($sql);
            
            if ($resultado[0]['total'] > 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe una asistencia registrada para esta actividad'
                ]);
                return;
            }

            $fechaHoraActual = date('Y-m-d H:i:s');

            $asistencia = new Asistencias([
                'actividad_id' => $_POST['actividad_id'],
                'fecha' => $fechaHoraActual,
                'situacion' => '1'
            ]);

            $crear = $asistencia->crear();

            $sqlActividad = "SELECT nombre, fecha FROM actividades WHERE id = " . $_POST['actividad_id'];
            $actividad = self::fetchFirst($sqlActividad);

            $fechaFormateada = date('d/m/Y H:i:s', strtotime($fechaHoraActual));
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asistencia registrada correctamente para "' . $actividad['nombre'] . '" el ' . $fechaFormateada . ' (Hora Guatemala)',
                'fecha_marcaje' => $fechaHoraActual,
                'fecha_formateada' => $fechaFormateada
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al registrar asistencia',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        header('Content-Type: application/json; charset=utf-8');

        try {
            date_default_timezone_set('America/Guatemala');
            
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;
            $actividad_id = isset($_GET['actividad_id']) ? $_GET['actividad_id'] : null;

            $condiciones = ["a.situacion = '1'"];

            if ($fecha_inicio) {
                $condiciones[] = "a.fecha >= '{$fecha_inicio} 00:00:00'";
            }

            if ($fecha_fin) {
                $condiciones[] = "a.fecha <= '{$fecha_fin} 23:59:59'";
            }

            if ($actividad_id) {
                $condiciones[] = "a.actividad_id = {$actividad_id}";
            }

            $where = implode(" AND ", $condiciones);

            $sql = "SELECT a.*, 
                    ac.nombre as actividad_nombre, 
                    ac.fecha as fecha_actividad,
                    CASE 
                        WHEN a.fecha <= ac.fecha THEN 'puntual'
                        ELSE 'tarde'
                    END as tipo_asistencia,
                    CASE 
                        WHEN a.fecha <= ac.fecha THEN 'A tiempo'
                        ELSE 'Llego tarde'
                    END as estado_detallado
                    FROM asistencia a
                    JOIN actividades ac ON a.actividad_id = ac.id
                    WHERE $where
                    ORDER BY a.fecha DESC";
            
            $data = self::fetchArray($sql);

            $asistencias_puntuales = [];
            $asistencias_tarde = [];

            foreach ($data as $asistencia) {
                if ($asistencia['tipo_asistencia'] === 'puntual') {
                    $asistencias_puntuales[] = $asistencia;
                } else {
                    $asistencias_tarde[] = $asistencia;
                }
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Asistencias obtenidas correctamente',
                'data' => [
                    'puntuales' => $asistencias_puntuales,
                    'tarde' => $asistencias_tarde,
                    'total' => $data
                ]
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las asistencias',
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
                'mensaje' => 'ID de la asistencia es obligatorio'
            ]);
            return;
        }

        $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
        if(!$id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de la asistencia debe ser un número válido'
            ]);
            return;
        }

        try {
            $asistencia = Asistencias::find($id);
            
            if(!$asistencia) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Asistencia no encontrada'
                ]);
                return;
            }
            
            $asistencia->sincronizar([
                'situacion' => '0'
            ]);
            
            $resultado = $asistencia->actualizar();
            
            if($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'La asistencia ha sido desactivada correctamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo desactivar la asistencia'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al desactivar la asistencia',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}