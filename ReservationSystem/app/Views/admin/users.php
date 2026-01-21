<?php ob_start(); ?>

<div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden min-h-[600px]">
    <div class="p-6 border-b border-gray-700 flex flex-col md:flex-row justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-white mb-2">Gestión de Usuarios</h2>
            <p class="text-gray-400">Administra los usuarios del sistema</p>
        </div>
        
        <form action="/admin/users" method="GET" class="mt-4 md:mt-0 flex">
             <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Buscar..." class="bg-gray-700 text-white rounded-l px-4 py-2 focus:outline-none border border-gray-600">
             <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700">Buscar</button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-gray-300">
            <thead class="bg-gray-700 text-gray-100 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">Usuario</th>
                    <th class="px-6 py-3">Nombre</th>
                    <th class="px-6 py-3">Rol</th>
                    <th class="px-6 py-3 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 flex items-center gap-3">
                         <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xs">
                            <?php echo strtoupper(substr($user['usuario'], 0, 1)); ?>
                         </div>
                        <?php echo htmlspecialchars($user['usuario']); ?>
                    </td>
                    <td class="px-6 py-4"><?php echo htmlspecialchars($user['NombreYApellido']); ?></td>
                    <td class="px-6 py-4">
                        <?php if ($user['esAdmin']): ?>
                            <span class="px-2 py-1 rounded text-xs font-semibold bg-purple-900 text-purple-200">Administrador</span>
                        <?php else: ?>
                             <span class="px-2 py-1 rounded text-xs font-semibold bg-gray-600 text-gray-200">Usuario</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                         <!-- Toggle Role -->
                        <form action="/admin/users/toggle-role" method="POST" class="inline">
                            <input type="hidden" name="id" value="<?php echo $user['ID']; ?>">
                            <input type="hidden" name="role" value="<?php echo $user['esAdmin'] ? 0 : 1; ?>">
                            <button type="submit" class="text-xs text-blue-400 hover:text-blue-300">
                                <?php echo $user['esAdmin'] ? 'Degradar' : 'Ascender'; ?>
                            </button>
                        </form>
                        
                        <!-- Delete -->
                         <form action="/admin/users/delete" method="POST" class="inline" onsubmit="return confirm('¿Eliminar usuario?');">
                            <input type="hidden" name="id" value="<?php echo $user['ID']; ?>">
                             <button type="submit" class="text-xs text-red-400 hover:text-red-300">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
     <div class="p-4 border-t border-gray-700 flex justify-center items-center gap-2">
        <?php if ($page > 1): ?>
            <a href="/admin/users?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Anterior</a>
        <?php endif; ?>
        
        <span class="text-sm text-gray-400">Página <?php echo $page; ?> de <?php echo $totalPages; ?></span>

         <?php if ($page < $totalPages): ?>
            <a href="/admin/users?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600 text-sm">Siguiente</a>
        <?php endif; ?>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
