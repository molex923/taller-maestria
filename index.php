<?php
require_once 'db_config.php';
$db = new Database();
$conn = $db->connect();

// CREATE
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $stmt = $conn->prepare("INSERT INTO Users (name, email) VALUES (?, ?)");
    $stmt->execute([$name, $email]);
    header("Location: index.php");
    exit();
}

// UPDATE
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $stmt = $conn->prepare("UPDATE Users SET name = ?, email = ? WHERE id = ?");
    $stmt->execute([$name, $email, $id]);
    header("Location: index.php");
    exit();
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit();
}

// SEARCH
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $conn->prepare("SELECT * FROM Users WHERE name LIKE ? OR email LIKE ?");
    $stmt->execute(['%'.$search.'%', '%'.$search.'%']);
    $users = $stmt->fetchAll();
} else {
    $users = $conn->query("SELECT * FROM Users")->fetchAll();
}

// For Edit form
$editUser = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
    $stmt->execute([$id]);
    $editUser = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Azure SQL Server</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Taller Maestria CRUD con Azure SQL Server</h1>

        <!-- Search Form -->
        <form class="search-form" method="get">
            <input type="text" name="search" placeholder="Buscar por nombre o email" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Buscar</button>
            <a href="index.php">Limpiar</a>
        </form>

        <!-- Add/Edit Form -->
        <h2><?php echo $editUser ? "Editar usuario" : "Agregar usuario"; ?></h2>
        <form class="crud-form" method="post">
            <?php if ($editUser): ?>
                <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
            <?php endif; ?>
            <input type="text" name="name" placeholder="Nombre" required value="<?php echo $editUser ? $editUser['name'] : ""; ?>">
            <input type="email" name="email" placeholder="Email" required value="<?php echo $editUser ? $editUser['email'] : ""; ?>">
            <button type="submit" name="<?php echo $editUser ? "update" : "add"; ?>">
                <?php echo $editUser ? "Actualizar" : "Agregar"; ?>
            </button>
            <?php if ($editUser): ?>
                <a class="cancel-link" href="index.php">Cancelar</a>
            <?php endif; ?>
        </form>

        <!-- Users List -->
        <h2>Usuarios</h2>
        <table>
            <tr>
                <th>ID</th><th>Nombre</th><th>Email</th><th>Acciones</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="actions">
                        <a href="?edit=<?php echo $user['id']; ?>">Editar</a>
                        <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('¿Seguro que quieres eliminar?');">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>