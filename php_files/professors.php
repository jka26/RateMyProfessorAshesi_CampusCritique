<?php
require_once('../config/db.php');

$search = $_GET['search'] ?? '';
$department = $_GET['department'] ?? '';

$query = "SELECT id, name, department FROM professors WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $query .= " AND name LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}
if (!empty($department)) {
    $query .= " AND department LIKE ?";
    $params[] = "%$department%";
    $types .= "s";
}

$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professors</title>
    <link rel="stylesheet" href="../public/styles/professors.css">
</head>
<body>
    <header>
        <h1>Campus Critique - Professors</h1>
    </header>

    <main>
        <section class="search-filter">
            <form method="get">
                <input type="text" name="search" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
                <input type="text" name="department" placeholder="Search by department" value="<?php echo htmlspecialchars($department); ?>">
                <button type="submit">Search</button>
                <a href="professors.php" class="clear-btn">Clear</a>
            </form>

        </section>

        <section class="professor-list">   
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="professor-card">
                    <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($row['department'])); ?></p>
                    <a href="professor_details.php?id=<?php echo $row['id']; ?>" class="details-button">View Details</a>
                </div>
            <?php endwhile; ?>
        </section>
    </main>



    <footer>
        <p>&copy; <?php echo date("Y"); ?> Campus Critique</p>
    </footer>
</body>
</html>
