import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario, Toast } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const FormActividades = document.getElementById('FormActividades');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');

const GuardarActividad = async (event) => {
    event.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(FormActividades, ['id'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(FormActividades);

    const url = '/parcial1_jjjc/actividades/guardarAPI';
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
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarActividades();
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
    BtnGuardar.disabled = false;
}

const BuscarActividades = async () => {
    const fecha_inicio = document.getElementById('fecha_inicio').value;
    const fecha_fin = document.getElementById('fecha_fin').value;
    
    let url = '/parcial1_jjjc/actividades/buscarAPI';
    
    const params = new URLSearchParams();
    if (fecha_inicio) params.append('fecha_inicio', fecha_inicio);
    if (fecha_fin) params.append('fecha_fin', fecha_fin);
    
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
            datatableActividades.clear().draw();
            datatableActividades.rows.add(data).draw();
            
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

const datatableActividades = new DataTable('#TablaActividades', {
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
    order: [[2, 'asc']], 
    columns: [
        {
            title: 'No.',
            data: 'id',
            width: '5%',
            orderable: false,
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Nombre de la Actividad', 
            data: 'nombre', 
            width: '40%' 
        },
        { 
            title: 'Fecha y Hora', 
            data: 'fecha', 
            width: '20%'
        },
        {
            title: 'Acciones',
            data: 'id',
            width: '35%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                const fechaFormateada = new Date(row.fecha).toISOString().slice(0, 16);
                return `
                <div class='d-flex justify-content-center flex-wrap'>
                    <button class='btn btn-warning btn-sm modificar mx-1 my-1' 
                        data-id="${data}" 
                        data-nombre="${row.nombre}"  
                        data-fecha="${fechaFormateada}">
                        <i class='bi bi-pencil-square me-1'></i> Editar
                    </button>
                    <button class='btn btn-danger btn-sm eliminar mx-1 my-1' 
                        data-id="${data}"
                        data-nombre="${row.nombre}">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                </div>`;
            }
        }
    ]
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('id').value = datos.id;
    document.getElementById('nombre').value = datos.nombre;
    document.getElementById('fecha').value = datos.fecha;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    FormActividades.scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

const limpiarTodo = () => {
    FormActividades.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarActividad = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(FormActividades, [''])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe completar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(FormActividades);

    const url = '/parcial1_jjjc/actividades/modificarAPI';
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
                title: "Éxito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarActividades();
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
        console.log(error);
    }
    BtnModificar.disabled = false;
}

const eliminarActividad = async (event) => {
    const id = event.currentTarget.dataset.id;
    const nombre = event.currentTarget.dataset.nombre;
    
    const resultado = await Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres eliminar la actividad "${nombre}"? Esta acción no se puede deshacer.`,
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

    const url = '/parcial1_jjjc/actividades/eliminarAPI';
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
            BuscarActividades();
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
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error",
            text: "Error de conexión al eliminar la actividad",
            showConfirmButton: true,
        });
    }
}

const btn_filtrar_fecha = document.getElementById('btn_filtrar_fecha');
btn_filtrar_fecha.addEventListener('click', BuscarActividades);

BuscarActividades();
FormActividades.addEventListener('submit', GuardarActividad);
datatableActividades.on('click', '.modificar', llenarFormulario);
BtnModificar.addEventListener('click', ModificarActividad);
BtnLimpiar.addEventListener('click', limpiarTodo);
datatableActividades.on('click', '.eliminar', eliminarActividad);