<?php
// Oracle database credentials
$host = 'your_host';
$port = 'your_port';
$service_name = 'your_service_name';
$username = 'your_username';
$password = 'your_password';

// Establish a connection to Oracle database using PDO
$tns = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=".$host.")(PORT=".$port."))(CONNECT_DATA=(SERVICE_NAME=".$service_name.")))";
$dsn = "oci:dbname=".$tns;
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Create a new record
if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepare SQL statement
    $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);

    // Execute SQL statement
    $stmt->execute();
    echo "Record created successfully!";
}

// Read all records
$sql = "SELECT * FROM users";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update a record
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepare SQL statement
    $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $id);

    // Execute SQL statement
    $stmt->execute();
    echo "Record updated successfully!";
}

// Delete a record
if (isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Prepare SQL statement
    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);

    // Execute SQL statement
    $stmt->execute();
    echo "Record deleted successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Demo</title>
</head>
<body>
    <h1>Create User</h1>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br><br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>
        <input type="submit" name="create" value="Create">
    </form>

    <h1>Users</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <input type="text" name="name" value="<?php echo $user['name']; ?>">
                        <input type="email" name="email" value="<?php echo $user['email']; ?>">
                        <input type="submit" name="update" value="Update">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
