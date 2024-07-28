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

        // Decodifica la respuesta JSON
        $data = json_decode($response, true);

        // Añade los commits obtenidos al array total de commits
        $commits = array_merge($commits, $data);

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
        <h3>&copy; <span id='openModal' style='cursor: pointer; text-decoration: none;'>6to 'C' 2023 - 2024 | EEST N° 1 | Profesor: Sergio Caffaro</span></h3>
    </div>
</footer>


<div id="creditsModal" class="modal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.8);">
    <div class="modal-content" style="background-color: #333; color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px;">
        <span class="close" style="color: #fefefe; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <h2>Créditos:</h2>
        <p>Este proyecto ha sido posible gracias a la colaboración y el esfuerzo continuo de varios individuos y grupos:</p>
        <ul>
            <li>El Profesor Sergio Cáffaro, quien proporcionó la idea inicial y la guía conceptual del proyecto.</li>
            <li>Los estudiantes de 6to año de 2023, quienes desarrollaron la versión original de la página.</li>
            <li>Los estudiantes de 6to año de 2024, quienes realizaron mejoras y completaron funcionalidades adicionales.</li>
            <li>Bernardo Gonzalez, estudiante de 7mo año del 2024, por su valiosa contribución en términos de experiencia y conocimientos adicionales.</li>
        </ul>
        <p>Agradecemos profundamente el compromiso y la dedicación de todos los participantes que hicieron posible este proyecto.</p>
    </div>
</div>


<script>
    var modal = document.getElementById("creditsModal");
    var btn = document.getElementById("openModal");
    var span = document.getElementsByClassName("close")[0];

    btn.onclick = function() {
        modal.style.display = "block";
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>