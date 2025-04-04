<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="icon" href="https://i.imgur.com/fSjgaVI.jpeg" type="image/svg+xml">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js for UI interactions -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>

    <?php
    require "../include/VerificacionAdmin.php";
    include('../include/conexion.php');

    // Cargar todos los usuarios
    $consulta = "SELECT * FROM usuarios";
    $resultado = mysqli_query($conexion, $consulta) or die('Error en consulta');
    $usuarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC); // Obtener todos los usuarios como un array
    mysqli_close($conexion);
    ?>
</head>

<body class="bg-gray-900 text-gray-100 font-sans min-h-screen flex flex-col">
    <!-- Navigation Bar -->
    <nav class="bg-gray-800 border-b border-gray-700 shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <a href="../index.php" class="flex items-center space-x-3">
                        <img src="../img/logo.png" alt="Logo" class="h-12 w-12">
                        <h1 class="text-xl md:text-2xl font-bold text-white font-display">Sistema de Reserva de Salones</h1>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <a href="../index.php" class="text-white bg-gray-700 hover:bg-gray-600 font-medium px-4 py-2 rounded-lg transition-all flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" clip-rule="evenodd" />
                        </svg>
                        Inicio
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                        <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Mobile menu -->
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-gray-800 rounded-md shadow-lg py-1 z-50">
                        <a href="../index.php" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-700">Inicio</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="bg-gradient-to-r from-primary-900 to-gray-800 py-6 px-4 sm:px-6 lg:px-8 mb-6 shadow-md">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-3xl font-bold text-white font-display">Gestión de Usuarios</h1>
            <p class="mt-2 text-gray-300">Administra los usuarios del sistema</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Action Buttons -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center">
            <div class="mb-4 sm:mb-0">
                <h2 class="text-2xl font-semibold text-white">Lista de Usuarios</h2>
                <p class="text-gray-400 text-sm mt-1">Gestión completa de cuentas de usuario. Mostrando 10 usuarios por página. Use la barra de búsqueda para encontrar cualquier usuario.</p>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" id="search" placeholder="Buscar usuario..." class="block w-full pl-10 pr-3 py-2 border border-gray-700 rounded-lg bg-gray-800 text-gray-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Usuario</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre y Apellido</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Rol</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-800" id="userTableBody">
                        <?php
                        foreach ($usuarios as $d) {
                            echo "<tr class=\"hover:bg-gray-700 transition-colors\">";
                            echo "<td class=\"px-6 py-4 whitespace-nowrap\">
                                <div class=\"flex items-center\">
                                    <div class=\"flex-shrink-0 h-10 w-10 rounded-full bg-primary-700 flex items-center justify-center text-white font-bold text-lg\">
                                        " . substr($d['usuario'], 0, 1) . "
                                    </div>
                                    <div class=\"ml-4\">
                                        <div class=\"text-sm font-medium text-white\">" . $d['usuario'] . "</div>
                                    </div>
                                </div>
                            </td>";
                            echo "<td class=\"px-6 py-4 whitespace-nowrap\">
                                <div class=\"text-sm text-white\">" . $d['NombreYApellido'] . "</div>
                            </td>";
                            echo "<td class=\"px-6 py-4 whitespace-nowrap\">";
                            if ($d['esAdmin'] == 1) {
                                echo "<span class=\"px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800\">Administrador</span>";
                            } else {
                                echo "<span class=\"px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800\">Usuario estándar</span>";
                            }
                            echo "</td>";

                            echo "<td class=\"px-6 py-4 whitespace-nowrap text-right text-sm font-medium\">
                                <div class=\"flex justify-end space-x-3\">
                                    <button onclick=\"openEditModal(" . $d['ID'] . ", '" . addslashes($d['usuario']) . "', '" . addslashes($d['NombreYApellido']) . "', " . $d['esAdmin'] . ")\" 
                                        class=\"text-primary-400 hover:text-primary-300 transition-colors\" title=\"Editar\">
                                        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\">
                                            <path d=\"M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z\" />
                                        </svg>
                                    </button>
                                    <a href='#' onclick=\"confirmDelete(" . $d['ID'] . ")\" 
                                        class=\"text-red-400 hover:text-red-300 transition-colors\" title=\"Eliminar\">
                                        <svg xmlns=\"http://www.w3.org/2000/svg\" class=\"h-5 w-5\" viewBox=\"0 0 20 20\" fill=\"currentColor\">
                                            <path fill-rule=\"evenodd\" d=\"M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z\" clip-rule=\"evenodd\" />
                                        </svg>
                                    </a>
                                </div>
                            </td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Back to Home Button -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <a href="../index.php" class="inline-flex items-center px-5 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white font-medium transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Volver al Inicio
        </a>
    </div>

    <!-- Modal para modificar usuario -->
    <div id="editUserModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold text-white mb-4">Modificar Usuario</h2>
            <form id="editUserForm" method="GET" action="modifica_sql.php">
                <input type="hidden" id="editUserId" name="ID">
                <div class="mb-4">
                    <label for="editUsuario" class="block text-sm font-medium text-gray-300">Usuario</label>
                    <input type="text" id="editUsuario" name="usuario" class="block w-full mt-1 px-3 py-2 bg-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                </div>
                <div class="mb-4">
                    <label for="editContraseña" class="block text-sm font-medium text-gray-300">Contraseña</label>
                    <input type="text" id="editContraseña" name="contraseña" class="block w-full mt-1 px-3 py-2 bg-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="********">
                </div>
                <div class="mb-4">
                    <label for="editNombreApellido" class="block text-sm font-medium text-gray-300">Nombre y Apellido</label>
                    <input type="text" id="editNombreApellido" name="nombreyapellido" class="block w-full mt-1 px-3 py-2 bg-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500" required>
                </div>
                <div class="mb-4">
                    <label for="editEsAdmin" class="block text-sm font-medium text-gray-300">Rol</label>
                    <select id="editEsAdmin" name="esAdmin" class="block w-full mt-1 px-3 py-2 bg-gray-700 text-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="1">Administrador</option>
                        <option value="0">Usuario estándar</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-500">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-400">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer rediseñado -->
    <footer class="bg-gray-800 border-t border-gray-700 py-6 mt-10">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0 flex items-center">
                    <img src="https://i.imgur.com/fSjgaVI.jpeg" alt="Logo" class="h-10 w-10 mr-3 rounded-full border border-gray-600">
                    <div>
                        <span class="text-gray-200 font-medium">Sistema de Reserva de Salones</span>
                        <p class="text-gray-400 text-xs">Gestión eficiente de espacios educativos</p>
                    </div>
                </div>

                <div class="text-center md:text-right">
                    <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> - Sistema de Reserva de Salones V2</p>
                    <p class="text-gray-500 text-xs mt-1">Desarrollado por G. Erramuspe, Bernardo</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Search functionality
        document.getElementById('search').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#userTableBody tr');

            tableRows.forEach(row => {
                const userName = row.querySelector('td:first-child .text-sm.font-medium').textContent.toLowerCase();
                const fullName = row.querySelector('td:nth-child(2) .text-sm').textContent.toLowerCase();

                if (userName.includes(searchValue) || fullName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Confirm delete functionality
        function confirmDelete(userId) {
            if (confirm('¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.')) {
                window.location.href = `baja_sql.php?id=${userId}`;
            }
        }

        // Abrir el modal con datos del usuario
        function openEditModal(id, usuario, nombreApellido, esAdmin) {
            document.getElementById('editUserId').value = id;
            document.getElementById('editUsuario').value = usuario;
            document.getElementById('editContraseña').value = '********';
            document.getElementById('editNombreApellido').value = nombreApellido;
            document.getElementById('editEsAdmin').value = esAdmin ? '1' : '0';
            document.getElementById('editUserModal').classList.remove('hidden');
        }

        // Cerrar el modal
        function closeEditModal() {
            document.getElementById('editUserModal').classList.add('hidden');
        }
    </script>
</body>

</html>