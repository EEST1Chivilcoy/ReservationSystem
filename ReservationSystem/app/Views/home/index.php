<?php ob_start(); ?>

<section class="mb-12">
    <div class="bg-gradient-to-r from-blue-800 to-indigo-900 rounded-2xl shadow-2xl p-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Sistema de Reserva de Salones</h2>
        <p class="text-lg text-blue-100 max-w-3xl mx-auto">
            Reserve salones para clases, eventos y actividades. SC.
        </p>
        <?php if ($loggedIn): ?>
            <a href="/reservations/create" class="mt-6 inline-block bg-white text-blue-800 hover:bg-blue-50 font-medium px-6 py-3 rounded-lg transition-all shadow-md">
                Crear nueva reserva
            </a>
        <?php else: ?>
            <a href="/login" class="mt-6 inline-block bg-white text-blue-800 hover:bg-blue-50 font-medium px-6 py-3 rounded-lg transition-all shadow-md">
                Iniciar sesión para reservar
            </a>
        <?php endif; ?>
    </div>
</section>

<section class="mb-12">
    <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-2">Salones Disponibles</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tarjeta Audiovisuales -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-xl overflow-hidden card-hover">
            <div class="p-6">
                <h3 class="text-xl font-bold text-white text-blue-500 mb-2">AUDIOVISUALES</h3>
                <p class="text-gray-300">Capacidad: 30 personas.</p>
            </div>
        </div>
        <!-- Tarjeta Comedor -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-xl overflow-hidden card-hover">
             <div class="p-6">
                <h3 class="text-xl font-bold text-white text-green-500 mb-2">COMEDOR</h3>
                <p class="text-gray-300">Capacidad: 50+ personas.</p>
            </div>
        </div>
        <!-- Tarjeta Actos -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-xl overflow-hidden card-hover">
             <div class="p-6">
                <h3 class="text-xl font-bold text-white text-yellow-500 mb-2">SALÓN DE ACTOS</h3>
                <p class="text-gray-300">Capacidad: 100+ personas.</p>
            </div>
        </div>
    </div>
</section>

<section class="mb-8">
    <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-white border-b border-gray-700 pb-2">Calendario de Reservas</h2>
    </div>

    <div class="bg-gray-800 rounded-xl shadow-xl p-2 sm:p-4 border border-gray-700">
        <div id="calendar"></div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',
            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            slotMinTime: '07:00:00',
            slotMaxTime: '23:00:00',
            allDaySlot: false,
            events: '/events', // Endpoint MVC
            eventClick: function(info) {
                alert('Materia: ' + info.event.extendedProps.materia + '\nProfesor: ' + info.event.extendedProps.nombreapellido);
            }
        });
        calendar.render();
    });
</script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
