<?php ob_start(); ?>

<div class="max-w-4xl mx-auto">
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-white mb-6">Nueva Reserva</h1>
        
        <form action="/reservations/store" method="POST" x-data="{ 
            curso: 'Reunión', 
            sitio: 'Salon de actos',
            showDivision: false,
            showOtroSalon: false,
            updateUI() {
                this.showDivision = !['Reunión', 'Charla/Conferencia', 'Acto'].includes(this.curso);
                this.showOtroSalon = this.sitio === 'Otro';
            }
        }" x-init="updateUI()">
            
            <?php if ($esAdmin): ?>
                <div class="mb-6 bg-gray-700 p-4 rounded-lg">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nombre y Apellido (Admin)</label>
                    <input type="text" name="NombreYApellido" class="w-full bg-gray-600 border border-gray-500 rounded px-3 py-2 text-white" placeholder="Dejar vacío para usar su nombre: <?php echo htmlspecialchars($nombreyapellido); ?>">
                </div>
            <?php endif; ?>

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
                    <input type="text" name="division" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Materia / Motivo</label>
                <input type="text" name="materia" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Fecha</label>
                    <input type="date" name="fecha" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Hora Inicio</label>
                    <input type="time" name="horario" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Hora Fin</label>
                    <input type="time" name="horario1" required class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
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
                <input type="text" name="otro_salon" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
            </div>

             <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Materiales</label>
                <textarea name="materiales" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white" rows="3"></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500">Guardar Reserva</button>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
