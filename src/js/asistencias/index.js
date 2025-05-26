import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormAsistencias = document.getElementById('FormAsistencias');
const BtnRegistrarAsistencia = document.getElementById('BtnRegistrarAsistencia');
const BtnLimpiarAsistencia = document.getElementById('BtnLimpiarAsistencia');

const RegistrarAsistencia = async (event) => {
    event.preventDefault();
    BtnRegistrarAsistencia.disabled = true;

    if (!validarFormulario(FormAsistencias, [])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe seleccionar una actividad",
            showConfirmButton: true,
        });
        BtnRegistrarAsistencia.disabled = false;
        return;
    }

    const body = new FormData(FormAsistencias);

    const url = '/parcial1_jjjc/asistencias/guardarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "¡Asistencia Registrada!",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarFormularioAsistencia();
            BuscarAsistencias();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error)
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo registrar la asistencia",
            showConfirmButton: true,
        });
    }
    BtnRegistrarAsistencia.disabled = false;
}

const BuscarAsistencias = async () => {
    const fecha_inicio = document.getElementById('fecha_inicio') ? document.getElementById('fecha_inicio').value : '';
    const fecha_fin = document.getElementById('fecha_fin') ? document.getElementById('fecha_fin').value : '';
    const actividad_filtro = document.getElementById('actividad_filtro') ? document.getElementById('actividad_filtro').value : '';
    
    let url = '/parcial1_jjjc/asistencias/buscarAPI';
    
    const params = new URLSearchParams();
    if (fecha_inicio) params.append('fecha_inicio', fecha_inicio);
    if (fecha_fin) params.append('fecha_fin', fecha_fin);
    if (actividad_filtro) params.append('actividad_id', actividad_filtro);
    
    if (params.toString()) {
        url += '?' + params.toString();
    }

    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos

        if (codigo == 1) {
            const asistenciasPuntuales = data.filter(asistencia => {
                return new Date(asistencia.fecha) <= new Date(asistencia.fecha_actividad);
            });
            
            const asistenciasTarde = data.filter(asistencia => {
                return new Date(asistencia.fecha) > new Date(asistencia.fecha_actividad);
            });

            datatableAsistenciasPuntuales.clear().draw();
            datatableAsistenciasPuntuales.rows.add(asistenciasPuntuales).draw();

            datatableAsistenciasTarde.clear().draw();
            datatableAsistenciasTarde.rows.add(asistenciasTarde).draw();
            
            Toast.fire({
                icon: 'success',
                title: mensaje
            });
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error)
    }
}

const datatableAsistenciasPuntuales = new DataTable('#TablaAsistenciasPuntuales', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    order: [[3, 'desc']], 
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
            orderable: false,
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Actividad', 
            data: 'actividad_nombre', 
            width: '30%' 
        },
        { 
            title: 'Fecha Programada', 
            data: 'fecha_actividad', 
            width: '20%'
        },
        { 
            title: 'Fecha de Llegada', 
            data: 'fecha', 
            width: '20%'
        },
        {
            title: 'Acciones',
            data: 'id',
            width: '25%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center flex-wrap'>
                    <button class='btn btn-danger btn-sm eliminar mx-1 my-1' 
                        data-id="${data}"
                        data-actividad="${row.actividad_nombre}">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </div>`;
            }
        }
    ]
});

const datatableAsistenciasTarde = new DataTable('#TablaAsistenciasTarde', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    order: [[3, 'desc']], 
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
            orderable: false,
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Actividad', 
            data: 'actividad_nombre', 
            width: '30%' 
        },
        { 
            title: 'Fecha Programada', 
            data: 'fecha_actividad', 
            width: '20%'
        },
        { 
            title: 'Fecha de Llegada', 
            data: 'fecha', 
            width: '20%'
        },
        {
            title: 'Acciones',
            data: 'id',
            width: '25%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                <div class='d-flex justify-content-center flex-wrap'>
                    <button class='btn btn-danger btn-sm eliminar mx-1 my-1' 
                        data-id="${data}"
                        data-actividad="${row.actividad_nombre}">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </div>`;
            }
        }
    ]
});

const limpiarFormularioAsistencia = () => {
    FormAsistencias.reset();
}

const eliminarAsistencia = async (event) => {
    const id = event.currentTarget.dataset.id;
    const actividad = event.currentTarget.dataset.actividad;
    
    const resultado = await Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres eliminar la asistencia de "${actividad}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if (!resultado.isConfirmed) {
        return;
    }
    
    const body = new FormData();
    body.append('id', id);

    const url = '/parcial1_jjjc/asistencias/eliminarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Eliminado",
                text: mensaje,
                showConfirmButton: true,
            });
            BuscarAsistencias();
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
    }
}

const btn_filtrar = document.getElementById('btn_filtrar');
if (btn_filtrar) {
    btn_filtrar.addEventListener('click', BuscarAsistencias);
}

BuscarAsistencias();
FormAsistencias.addEventListener('submit', RegistrarAsistencia);
BtnLimpiarAsistencia.addEventListener('click', limpiarFormularioAsistencia);
datatableAsistenciasPuntuales.on('click', '.eliminar', eliminarAsistencia);
datatableAsistenciasTarde.on('click', '.eliminar', eliminarAsistencia);