<?php
// URL de la API de GitHub para obtener los commits
$base_url = "https://api.github.com/repos/Bernard2806/ReservationSystem/commits";

// Inicia una sesión cURL
$ch = curl_init();

// Función para obtener commits con manejo de paginación
function get_commits($url, $ch)
{
    $commits = [];
    $page = 1;
    $per_page = 100; // Máximo permitido por GitHub

    do {
        $paged_url = $url . "?per_page=$per_page&page=$page";
        curl_setopt($ch, CURLOPT_URL, $paged_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

        // Ejecuta la solicitud y obtiene la respuesta
        $response = curl_exec($ch);

        // Verifica si hubo un error en la solicitud cURL
        if (curl_errno($ch)) {
            echo 'Error en cURL: ' . curl_error($ch);
            break;
        }

        // Decodifica la respuesta JSON
        $data = json_decode($response, true);

        // Verifica si la decodificación fue exitosa
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            echo 'Error al decodificar JSON: ' . json_last_error_msg();
            break;
        }

        // Añade los commits obtenidos al array total de commits
        if (is_array($data)) {
            $commits = array_merge($commits, $data);
        } else {
            // Si $data no es un array, sal del bucle
            echo 'Error: Datos no válidos recibidos de la API.';
            break;
        }

        // Incrementa el número de página
        $page++;
    } while (count($data) == $per_page); // Continúa mientras se obtenga el número máximo de commits por página

    return $commits;
}

// Obtiene todos los commits
$commits = get_commits($base_url, $ch);

// Cierra la sesión cURL
curl_close($ch);

// Cuenta el número de commits
$commit_count = count($commits);

// Calcula la versión en el formato 0.0.1, 0.1.0, 1.0.0, etc.
$major = floor($commit_count / 1000);
$minor = floor(($commit_count % 1000) / 100);
$patch = floor(($commit_count % 100) / 10);
$version = "$major.$minor.$patch";

// Guarda la versión en una variable global para su uso en otras partes del código
$GLOBALS['version'] = $version;
?>


<!-- Footer -->
<footer>
    <div class="contenedor-footer">
        <div class="cont-foo">
            <h4>Teléfono</h4>
            <p>
                <a href="tel:02346431330" style="color: white;">
                    <i class="bi bi-telephone-fill"></i> 2346-431330
                </a>
            </p>
        </div>
        <div class="cont-foo">
            <h4>Localidad</h4>
            <p>Chivilcoy, Buenos Aires</p>
        </div>
        <div class="cont-foo">
            <h4>Versión</h4>
            <p><?php echo $GLOBALS['version']; ?></p>
        </div>
    </div>
    <div class="footer-bottom">
        <h3>&copy; <span id="openModal" style="cursor: pointer; text-decoration: none;">6to 'C' 2023 - 2024 | EEST N° 1 | Profesor: Sergio Caffaro</span></h3>
    </div>
</footer>

<!-- Modal Créditos -->
<div class="modal fade" id="creditsModal" tabindex="-1" aria-labelledby="creditsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="creditsModalLabel">Créditos</h5>
                <button type="button" class="btn btn-link text-white p-0 m-0" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Imagen centrada -->
                <div class="text-center">
                    <img src="https://i.imgur.com/fSjgaVI.jpeg" alt="Logo del Sistema" class="img-fluid mb-4" style="max-width: 200px;">
                </div>
                <p>Este proyecto ha sido posible gracias a la colaboración y el esfuerzo continuo de varios individuos y grupos:</p>
                <ul>
                    <li>El Profesor Sergio Cáffaro, quien proporcionó la idea inicial y la guía conceptual del proyecto.</li>
                    <li>Los estudiantes de 6to año de 2023, quienes desarrollaron la versión original de la página.</li>
                    <li>Los estudiantes de 6to año de 2024, quienes realizaron mejoras y completaron funcionalidades adicionales.</li>
                    <li>Bernardo Gonzalez, estudiante de 7mo año del 2024, por su valiosa contribución en términos de experiencia y conocimientos adicionales.</li>
                </ul>
                <p>Agradecemos profundamente el compromiso y la dedicación de todos los participantes que hicieron posible este proyecto.</p>
                <div class="modal-footer d-flex justify-content-center">
                    <a href="https://github.com/Bernard2806/ReservationSystem" target="_blank" class="btn btn-outline-light">
                        <i class="bi bi-github"></i> Visita nuestro repositorio en GitHub
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Incluir scripts de Bootstrap 5 -->
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    document.getElementById('openModal').addEventListener('click', function() {
        var myModal = new bootstrap.Modal(document.getElementById('creditsModal'));
        myModal.show();
    });
</script>