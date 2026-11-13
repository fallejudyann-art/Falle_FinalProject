
<?php
require 'db_connect.php';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// PAGE VIEW
$view = $_GET['view'] ?? 'welcome';

if (!in_array($view, ['welcome', 'dashboard', 'suppliers'])) {
    $view = 'welcome';
}

$showSidebar = $view !== 'welcome';

// SEARCH SUPPLIERS
$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("
        SELECT * FROM suppliers
        WHERE supplier_name LIKE ?
        OR company_name LIKE ?
        OR email LIKE ?
        ORDER BY id DESC
    ");

    $term = "%$search%";
    $stmt->execute([$term, $term, $term]);
} else {
    $stmt = $pdo->query("SELECT * FROM suppliers ORDER BY id DESC");
}

$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// DASHBOARD COUNTS
$total = $pdo->query("SELECT COUNT(*) FROM suppliers")->fetchColumn();
$active = $pdo->query("SELECT COUNT(*) FROM suppliers WHERE status='Active'")->fetchColumn();
$inactive = $pdo->query("SELECT COUNT(*) FROM suppliers WHERE status='Inactive'")->fetchColumn();

// SUCCESS MESSAGES
$messages = [
    'added' => 'Supplier successfully added!',
    'updated' => 'Supplier successfully updated!',
    'deleted' => 'Supplier successfully deleted!'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Supplier Management System</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SEPARATE CSS DESIGN -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- SIDEBAR: HIDDEN ON WELCOME PAGE -->
    <?php if ($showSidebar): ?>

    <div class="sidebar" id="sidebar">

        <div class="brand">
            <i class="bi bi-box-seam me-2"></i>SUPPLIER SYSTEM
        </div>

        <p>MAIN MENU</p>

        <a href="index.php?view=dashboard"
           class="<?= $view === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i>Dashboard
        </a>

        <a href="index.php?view=suppliers"
           class="<?= $view === 'suppliers' ? 'active' : '' ?>">
            <i class="bi bi-table"></i>Supplier Table
        </a>

        <p class="mt-4">MANAGEMENT</p>

        <a href="add.php">
            <i class="bi bi-person-plus-fill"></i>Add Supplier
        </a>

    </div>

    <?php endif; ?>


    <!-- MAIN CONTENT -->
    <div class="main-content <?= $showSidebar ? 'with-sidebar' : '' ?>">

        <!-- TOPBAR -->
        <div class="topbar">

            <div class="d-flex align-items-center gap-3">

                <?php if ($showSidebar): ?>
                    <button class="menu-btn" onclick="toggleSidebar()">
                        <i class="bi bi-list fs-5"></i>
                    </button>
                <?php endif; ?>

                <div class="text-center mx-auto">
    <h5 class="mb-0 fw-bold">Supplier Management System</h5>
    <small class="subtitle">
        Manage your suppliers efficiently
    </small>
</div>

            </div>

        </div>


        <!-- SUCCESS MESSAGE -->
        <?php if (isset($_GET['success'])): ?>

            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                <?= e($messages[$_GET['success']] ?? 'Operation completed.') ?>
            </div>

        <?php endif; ?>


        <?php if ($view === 'dashboard'): ?>

            <!-- DASHBOARD -->
            <h2 class="page-title">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </h2>

            <p class="subtitle">
                Overview of your supplier management system.
            </p>

            <div class="row g-3">

                <?php
                $cards = [
                    ['Total Suppliers', $total, 'people-fill', 'total'],
                    ['Active Suppliers', $active, 'check-circle-fill', 'active-card'],
                    ['Inactive Suppliers', $inactive, 'pause-circle-fill', 'inactive']
                ];

                foreach ($cards as $card):
                ?>

                    <div class="col-md-4">
                        <div class="stat-card <?= $card[3] ?>">

                            <i class="bi bi-<?= $card[2] ?> stat-icon"></i>

                            <p><?= $card[0] ?></p>

                            <h2><?= $card[1] ?></h2>

                        </div>
                    </div>

                <?php endforeach; ?>

            </div>

            <div class="content-card mt-4">

                <h5 class="section-title">
                    <i class="bi bi-info-circle me-2"></i>System Overview
                </h5>

                <p class="subtitle mb-0">
                    Welcome to the Supplier Management System.
                    Use the sidebar to view supplier records or add a new supplier.
                </p>

            </div>


        <?php elseif ($view === 'suppliers'): ?>

            <!-- SUPPLIER TABLE -->
            <div class="content-card">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                    <div>
                        <h4 class="section-title mb-1">
                            <i class="bi bi-table me-2"></i>Supplier Records
                        </h4>

                        <p class="subtitle mb-0">
                            View and manage all registered suppliers.
                        </p>
                    </div>

                    <a href="add.php" class="btn btn-add">
                        <i class="bi bi-plus-circle me-1"></i>Add Supplier
                    </a>

                </div>


                <!-- SEARCH -->
                <form method="GET" class="d-flex gap-2 mb-4" style="max-width:450px">

                    <input type="hidden" name="view" value="suppliers">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search name, company or email..."
                        value="<?= e($search) ?>"
                    >

                    <button class="btn btn-add" type="submit">
                        <i class="bi bi-search"></i>
                    </button>

                    <?php if ($search !== ''): ?>

                        <a href="index.php?view=suppliers" class="btn btn-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>

                    <?php endif; ?>

                </form>


                <!-- TABLE -->
                <div class="table-container">

                    <table class="table table-hover">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Supplier Name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Product</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if ($suppliers): ?>

                            <?php foreach ($suppliers as $s): ?>

                                <tr>

                                    <td>#<?= e($s['id']) ?></td>

                                    <td class="fw-semibold">
                                        <?= e($s['supplier_name']) ?>
                                    </td>

                                    <td><?= e($s['company_name']) ?></td>

                                    <td><?= e($s['email']) ?></td>

                                    <td><?= e($s['phone']) ?></td>

                                    <td><?= e($s['address']) ?></td>

                                    <td><?= e($s['product']) ?></td>

                                    <td>
                                        <span class="<?= $s['status'] === 'Active' ? 'badge-active' : 'badge-inactive' ?>">
                                            <?= e($s['status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex gap-2">

                                            <a
                                                href="edit.php?id=<?= e($s['id']) ?>"
                                                class="btn btn-edit btn-sm"
                                                title="Edit Supplier"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <form
                                                action="delete.php"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this supplier?')"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= e($s['id']) ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-delete btn-sm"
                                                    title="Delete Supplier"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="9" class="text-center py-5">

                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>

                                    <strong>No supplier records found.</strong>

                                    <p class="mb-0">
                                        Add a supplier or try another search.
                                    </p>

                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

                <p class="subtitle mt-3 mb-0">
                    Showing <?= count($suppliers) ?> supplier record(s).
                </p>

            </div>


        <?php else: ?>

            <!-- WELCOME PAGE: NO SIDEBAR -->
            <div class="welcome">

                <i class="bi bi-box-seam"></i>

                <h2 class="page-title mt-3">
                    Welcome to Supplier Management
                </h2>

                <p class="subtitle">
                    Click below to open your supplier management system.
                </p>

                <div class="d-flex justify-content-center flex-wrap gap-2 mt-4">

                    <a href="index.php?view=dashboard" class="btn btn-add">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Open Dashboard
                    </a>

                    <a href="index.php?view=suppliers" class="btn btn-success">
                        <i class="bi bi-table me-1"></i>
                        View Supplier Table
                    </a>

                </div>

            </div>

        <?php endif; ?>


        <!-- FOOTER -->
        <footer>
            <i class="bi bi-box-seam me-1"></i>
            Supplier Management System
        </footer>

    </div>


    <!-- SIDEBAR SCRIPT -->
    <script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('show');
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
