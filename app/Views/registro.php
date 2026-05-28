<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 450px; width: 100%;">
    <a class="back mb-3 text-center" href="../../public/index.php">Biblioteca Pública</a>

        <h2 class="text-center mb-1">Registro de Usuario Biblioteca</h2>
        <p class="text-center text-muted mb-4">Por favor, diligencie los siguientes campos</p>

        <form method="POST" action="../Controllers/registro.php" id="formRegistro">
            <!-- Campo: Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" maxlength="100" placeholder="Ingrese su nombre completo" required>
            </div>

            <!-- Campo: Correo -->
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" maxlength="100" placeholder="Ingrese su correo" required>
            </div>

            <!-- Campo: Edad -->
            <div class="mb-3">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" class="form-control" id="edad" name="edad" min="1" max="120" placeholder="Ingrese su edad" required>
            </div>

            <!-- Campo: Contraseña -->
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6" maxlength="255" placeholder="Mínimo 6 caracteres" required>
                <div class="form-text">Debe tener al menos una mayúscula, minúscula, número y símbolo.</div>
            </div>

            <!-- Campo: Confirmar Contraseña (CORREGIDO EL ATRIBUTO NAME) -->
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirm_password" name="password_confirm" minlength="6" maxlength="255" placeholder="Confirme su contraseña" required>
            </div>

            <!-- Botón de Envío -->
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
            <p class="text-center mb-0">¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('formRegistro').addEventListener('submit', function(e) {
            const pass = document.getElementById('password').value;
            const confirmPass = document.getElementById('confirm_password').value;

            if (pass !== confirmPass) {
                e.preventDefault();
                alert('Las contraseñas no coinciden. Por favor, verifíquelas.');
            }
        });
    </script>
</body>

</html>