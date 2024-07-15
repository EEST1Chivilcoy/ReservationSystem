<footer>
    <div class="contenedor-footer">
        <div class="cont-foo">
            <h4>Telefono</h4>
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
    </div>
    <div class="footer-bottom">
        <h3>&copy; <span id='openModal' style='cursor: pointer; text-decoration: none;'>6to 'C' 2023 - 2024 | EEST N° 1 | Profesor: Sergio Caffaro</span></h3>
    </div>
</footer>

<div id="creditsModal" class="modal" style="display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px;">
        <span class="close" style="color: #aaa; position: absolute; top: 10px; right: 20px; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <h2>Créditos:</h2>
        <p style="color: black;">Este proyecto ha sido posible gracias a la colaboración y el esfuerzo continuo de varios individuos y grupos:</p>
        <ul>
            <li>El Profesor Sergio Cáffaro, quien proporcionó la idea inicial y la guía conceptual del proyecto.</li>
            <li>Los estudiantes de 6to año de 2023, quienes desarrollaron la versión original de la página.</li>
            <li>Los estudiantes de 6to año de 2024, quienes realizaron mejoras y completaron funcionalidades adicionales.</li>
            <li>Bernardo, estudiante de 7mo año, por su valiosa contribución en términos de experiencia y conocimientos adicionales.</li>
        </ul>
        <p style="color: black;">Agradecemos profundamente el compromiso y la dedicación de todos los participantes que hicieron posible este proyecto.</p>
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