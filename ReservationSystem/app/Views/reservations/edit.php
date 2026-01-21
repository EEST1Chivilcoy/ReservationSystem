<?php ob_start(); ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-white mb-6">Editar Reserva</h1>
        
        <?php 
            // Parsing simple del curso/division si están unidos
            // Esto es frágil si el formato en BD varía, pero funciona para la migración básica
            $fullCurso = $reservation['curso'];
            $cursoVal = $fullCurso; 
            $divisionVal = '';
            
            $commonCursos = ['Reunión', 'Charla/Conferencia', 'Acto', '1º', '2º', '3º', '4º', '5º', '6º', '7º'];
            // Detectar si tiene division
            // Ej: "1º A"
            $parts = explode(' ', $fullCurso);
            if (count($parts) > 1 && in_array($parts[0], $commonCursos)) {
                $cursoVal = $parts[0];
                $divisionVal = $parts[1];
            }
        ?>

        <form action="/reservations/update" method="POST" x-data="{ 
            curso: '<?php echo htmlspecialchars($cursoVal); ?>', 
            sitio: '<?php echo htmlspecialchars($reservation['info']); ?>',
            showDivision: false,
            showOtroSalon: false,
            updateUI() {
                this.showDivision = !['Reunión', 'Charla/Conferencia', 'Acto'].includes(this.curso);
                // Si el sitio no es uno de los estándar, asumimos 'Otro'
                const estandard = ['Salon de actos', 'Comedor', 'Audiovisuales'];
                this.showOtroSalon = !estandard.includes(this.sitio) || this.sitio === 'Otro';
                
                // Si es otro y no es 'Otro' literal, necesitamos setear sitio a 'Otro' y llenar el input extra
                // Esto es complejo de manejar con Alpine simple sin más lógica JS.
                // Simplificación:
                if (!estandard.includes(this.sitio)) {
                   // Es un sitio custom
                   // Dejamos que el usuario lo vea en el input si seleccionó 'Otro'?
                   // Mejor: Si no está en lista estándar, forzamos select a 'Otro' en init?
                }
            }
        }" x-init="
            if (!['Salon de actos', 'Comedor', 'Audiovisuales'].includes(sitio)) {
                 $refs.otroInput.value = sitio;
                 sitio = 'Otro';
            }
            updateUI();
        ">
            <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Curso y Division -->
                <div>
                     <label class="block text-sm font-medium text-gray-300 mb-2">Curso / Tipo</label>
                     <select name="curso" x-model="curso" @change="updateUI()" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                        <option value="Reunión">Reunión</option>
                        <option value="Charla/Conferencia">Charla/Conferencia</option>
                        <option value="Acto">Acto</option>
                        <option value="1º">1º</option>
                        <option value="2º">2º</option>
                        <option value="3º">3º</option>
                        <option value="4º">4º</option>
                        <option value="5º">5º</option>
                        <option value="6º">6º</option>
                        <option value="7º">7º</option>
                     </select>
                </div>
                <div x-show="showDivision">
                    <label class="block text-sm font-medium text-gray-300 mb-2">División</label>
                    <input type="text" name="division" value="<?php echo htmlspecialchars($divisionVal); ?>" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Materia / Motivo</label>
                <input type="text" name="materia" value="<?php echo htmlspecialchars($reservation['materia']); ?>" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fecha</label>
                    <input type="date" name="fecha" value="<?php echo htmlspecialchars($reservation['fecha']); ?>" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Hora Inicio</label>
                    <input type="time" name="horario" value="<?php echo htmlspecialchars($reservation['horario']); ?>" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Hora Fin</label>
                    <input type="time" name="horario1" value="<?php echo htmlspecialchars($reservation['horario1']); ?>" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Salón</label>
                <select name="info" x-model="sitio" @change="updateUI()" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                    <option value="Salon de actos">Salón de actos</option>
                    <option value="Comedor">Comedor</option>
                    <option value="Audiovisuales">Audiovisuales</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>

            <div class="mb-6" x-show="showOtroSalon">
                <label class="block text-sm font-medium text-gray-300 mb-2">Especificar Salón</label>
                <input type="text" name="otro_salon" x-ref="otroInput" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
            </div>

             <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Materiales</label>
                <textarea name="materiales" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white" rows="3"><?php echo htmlspecialchars($reservation['materiales']); ?></textarea>
            </div>

            <div class="flex justify-between items-center">
                <?php if ($esAdmin): ?>
                     <button type="button" onclick="if(confirm('¿Eliminar reserva?')) document.getElementById('delete-form').submit();" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500">Eliminar</button>
                <?php else: ?>
                    <div></div>
                <?php endif; ?>
                
                <div class="space-x-4">
                    <a href="/" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Actualizar</button>
                </div>
            </div>
        </form>
        
        <form id="delete-form" action="/reservations/delete" method="POST" class="hidden">
             <input type="hidden" name="id" value="<?php echo $reservation['id']; ?>">
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
