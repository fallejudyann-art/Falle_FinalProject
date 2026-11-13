
<?php
require 'db_connect.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM suppliers WHERE id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("
        UPDATE suppliers SET
        supplier_name = ?,
        company_name = ?,
        email = ?,
        phone = ?,
        address = ?,
        product = ?,
        status = ?
        WHERE id = ?
    ");

    $stmt->execute([
        trim($_POST['supplier_name']),
        trim($_POST['company_name']),
        trim($_POST['email']),
        trim($_POST['phone']),
        trim($_POST['address']),
        trim($_POST['product']),
        $_POST['status'],
        $id
    ]);

    header("Location: index.php?success=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Supplier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#e8f5ef; color:#28574d; }
        .card { background:#f3fbf6; border:1px solid #cce8d9; border-radius:15px; }
        .form-control, .form-select { background:#e3f2e9; border-color:#c0dfce; }
        .btn-update { background:#21866e; color:white; }
        .btn-update:hover { background:#176653; color:white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card p-4 mx-auto" style="max-width:750px;">
        <h3 class="mb-4">Edit Supplier Information</h3>

        <form method="POST">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Supplier Name</label>
                    <input type="text" name="supplier_name" class="form-control"
                        value="<?= htmlspecialchars($supplier['supplier_name']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control"
                        value="<?= htmlspecialchars($supplier['company_name']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= htmlspecialchars($supplier['email']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contact Number</label>
                    <input type="tel" name="phone" class="form-control"
                        value="<?= htmlspecialchars($supplier['phone']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control"
                        value="<?= htmlspecialchars($supplier['address']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Product Supplied</label>
                    <input type="text" name="product" class="form-control"
                        value="<?= htmlspecialchars($supplier['product']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="Active" <?= $supplier['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                        <option value="Inactive" <?= $supplier['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>

            </div>

            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-update px-4">Update Supplier</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
