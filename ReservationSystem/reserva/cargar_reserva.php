<?php
require '../include/VerificacionSesion.php';
$esAdmin = isset($_SESSION['EsAdmin']) && $_SESSION['EsAdmin'] == true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservación</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estilo.css">
    <link rel="icon" href="../img/logo.png" type="image/svg+xml">

    <style>
        label {
            color: white;
        }

        .select-css {
            display: block;
            font-size: 16px;
            font-family: 'Arial', sans-serif;
            font-weight: 400;
            color: #444;
            line-height: 1.3;
            padding: .4em 1.4em .3em .8em;
            width: 400px;
            max-width: 100%;
            box-sizing: border-box;
            margin: 0;
            border: 1px solid #aaa;
            box-shadow: 0 1px 0 1px rgba(0, 0, 0, .03);
            border-radius: .3em;
            -moz-appearance: none;
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'),
                linear-gradient(to bottom, #ffffff 0%, #f7f7f7 100%);
            background-repeat: no-repeat, repeat;
            background-position: right .7em top 50%, 0 0;
            background-size: .65em auto, 100%;
        }

        .select-css::-ms-expand {
            display: none;
        }

        .select-css:hover {
            border-color: #888;
        }

        .select-css:focus {
            border-color: #aaa;
            box-shadow: 0 0 1px 3px rgba(59, 153, 252, .7);
            box-shadow: 0 0 0 3px -moz-mac-focusring;
            color: #222;
            outline: none;
        }

        .select-css option {
            font-weight: normal;
        }
    </style>

</head>

<body>
    <nav class="navbar bg-body-tertiary custom-navbar">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <a class="navbar-brand" href="../index.php">
                    <img src="../img/logo.png" alt="Logo" width="65" height="65" class="d-inline-block align-text-top">
                </a>
                <h1 class="navbar-text text-center font-italic mb-0 custom-title">RESERVACIÓN</h1>
            </div>
        </div>
    </nav>

    <br>
    <br>

    <!-- Formulario -->
    <div class="container" style="margin: bottom 30cm;">
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <form action="cargar_sql.php" method="post" onsubmit="return validarFormulario()">
                    <?php if ($esAdmin): ?>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="adminSwitch">
                            <label class="custom-control-label" for="adminSwitch">¿Es para otra Persona?</label>
                        </div>
                        <div id="adminForm" class="mt-3" style="display: none;">
                            <div class="form-group">
                                <label for="NombreYApellido" style="color:white;" class="font-weight-bold">Ingrese Nombre y Apellido</label>
                                <input type="text" id="NombreYApellido" name="NombreYApellido" placeholder="Ingrese Nombre y Apellido" class="form-control">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <div class="d-flex align-items-end">
                            <div style="flex: 0 0 auto; width: 200px; margin-right: 10px;">
                                <label for="info" style="color:white;" class="font-weight-bold">Ingrese curso</label>
                                <select name="curso" id="curso" class="form-control" onchange="mostrarCampoDivision(this)">
                                    <option value="Reunión">Reunión</option>
                                    <option value="1º">1</option>
                                    <option value="2º">2</option>
                                    <option value="3º">3</option>
                                    <option value="4º">4</option>
                                    <option value="5º">5</option>
                                    <option value="6º">6</option>
                                    <option value="7º">7</option>
                                </select>
                            </div>
                            <div id="campoDivision" style="display: none; flex: 0 0 auto; width: 150px;">
                                <label for="division" style="color:white;" class="font-weight-bold">Ingrese división</label>
                                <input type="text" id="division" name="division" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="materia" style="color:white;" class="font-weight-bold">Ingrese Materia</label>
                        <input type="text" id="materia" name="materia" placeholder="Ingrese materia" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="horario" style="color:white;" class="font-weight-bold">Ingrese horario</label>
                        <input type="time" id="horario" name="horario" class="form-control" onchange="validarHorario()">
                    </div>
                    <div class="form-group">
                        <label for="horario1" style="color:white;" class="font-weight-bold">Fin de uso</label>
                        <input type="time" id="horario1" name="horario1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="fecha" style="color:white;" class="font-weight-bold">Ingrese fecha</label>
                        <input type="date" id="fecha" name="fecha" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="info" style="color:white;" class="font-weight-bold">Ingrese sitio</label>
                        <select name="info" id="info" class="select-css" onchange="mostrarCampoOtro(this); validarHorario()">
                            <option value="Salon de actos">Salón de actos</option>
                            <option value="Comedor">Comedor</option>
                            <option value="Audiovisuales">Audiovisuales</option>
                            <option value="Otro">Otro (Especificar)</option>
                        </select>
                    </div>

                    <div id="campoOtro" style="display: none;">
                        <label for="otro_salon" style="color:white;" class="font-weight-bold">Especificar otro salón</label>
                        <input type="text" name="otro_salon" id="otro_salon" class="form-control">
                    </div>

                    <script>
                        document.getElementById('adminSwitch').addEventListener('change', function() {
                            var adminForm = document.getElementById('adminForm');
                            if (this.checked) {
                                adminForm.style.display = 'block';
                            } else {
                                adminForm.style.display = 'none';
                            }
                        });

                        function validarFormulario() {
                            const horarioValido = validarHorario();
                            return horarioValido && validarHorarios(); // Asumiendo que `validarHorarios` es otra función que ya tienes
                        }

                        function validarHorario() {
                            const sitio = document.getElementById('info').value;
                            const horario = document.getElementById('horario').value;
                            const hora = horario.split(':')[0];
                            const horaMinutos = parseInt(hora);

                            if (sitio === 'Comedor') {
                                if (horaMinutos < 14) {
                                    alert('Si el sitio es Comedor, el horario debe ser posterior a las 2 PM.');
                                    document.getElementById('horario').value = '';
                                    return false;
                                }
                            }
                            return true;
                        }

                        function validarHorarios() {
                            const horaInicio = document.getElementById('horario').value;
                            const horaFin = document.getElementById('horario1').value;

                            if (horaInicio && horaFin) {
                                const [horaInicioH, horaInicioM] = horaInicio.split(':').map(Number);
                                const [horaFinH, horaFinM] = horaFin.split(':').map(Number);

                                if (horaFinH < horaInicioH || (horaFinH === horaInicioH && horaFinM < horaInicioM)) {
                                    alert('La hora de fin no puede ser anterior a la hora de inicio.');
                                    return false;
                                }
                            }
                            return true;
                        }

                        function mostrarCampoOtro(select) {
                            var campoOtro = document.getElementById('campoOtro');
                            if (select.value === 'Otro') {
                                campoOtro.style.display = 'block';
                            } else {
                                campoOtro.style.display = 'none';
                            }
                        }

                        function mostrarCampoDivision(select) {
                            var campoDivision = document.getElementById('campoDivision');
                            if (select.value !== 'Reunión') {
                                campoDivision.style.display = 'block';
                            } else {
                                campoDivision.style.display = 'none';
                            }
                        }

                        document.querySelector('form').addEventListener('submit', function(event) {
                            // Obtener el valor del botón que se ha presionado
                            const submitButtonValue = event.submitter ? event.submitter.value : '';

                            // Validar solo si se presionó el botón para cargar la reserva
                            if (submitButtonValue === '1' && !validarHorarios()) {
                                event.preventDefault();
                            }
                        });

                        document.addEventListener('DOMContentLoaded', function() {
                            var selectCurso = document.getElementById('curso');
                            mostrarCampoDivision(selectCurso);
                            var selectInfo = document.getElementById('info');
                            mostrarCampoOtro(selectInfo);
                        });
                    </script>

                    <div class="form-group">
                        <label for="materiales" style="color:white;" class="font-weight-bold">Ingrese Materiales que va a precisar de EMATP</label>
                        <input type="text" id="materiales" name="materiales" placeholder="Ingrese materiales" class="form-control">
                    </div>
                    <div class="d-grid gap-2 mb-3">
                        <!-- Botones rediseñados -->
                        <button type="submit" class="btn btn-success btn-block" name="boton" value="1">
                            <i class="bi bi-upload me-2"></i> Cargar la reserva
                        </button>
                        <button type="submit" class="btn btn-secondary btn-block mt-2" name="boton" value="0">
                            <i class="bi bi-x-circle me-2"></i> Anular la reserva
                        </button>
                    </div>

                </form>
            </div>
            <div class="col-3"></div>
        </div>
    </div>


    <br>
    <br>

    <?php
    include('../footer.php');
    ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
</body>

</html>