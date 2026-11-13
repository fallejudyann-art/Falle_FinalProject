
<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO suppliers
        (supplier_name, company_name, email, phone, address, product, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        trim($_POST['supplier_name']),
        trim($_POST['company_name']),
        trim($_POST['email']),
        trim($_POST['phone']),
        trim($_POST['address']),
        trim($_POST['product']),
        $_POST['status']
    ]);

    header("Location: index.php?success=added");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Supplier</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #fce4ec, #f8dce7, #f9e9ef);
            font-family: 'Segoe UI', sans-serif;
            color: #75495d;
        }

        .container {
            max-width: 850px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .page-icon {
            width: 75px;
            height: 75px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px;
            background: #f3bfd2;
            color: #995d76;
            border-radius: 50%;
            font-size: 32px;
        }

        .page-header h2 {
            font-weight: 800;
            color: #995d76;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #b17b91;
        }

        .card {
            background: #fff0f5;
            border: 1px solid #edc2d2;
            border-radius: 20px;
            box-shadow: 0 10px 30px #b879931c;
        }

        .card-header {
            background: #f8dce7;
            border-bottom: 1px solid #edc2d2;
            border-radius: 20px 20px 0 0 !important;
            padding: 20px 25px;
        }

        .card-header h5 {
            margin: 0;
            color: #995d76;
            font-weight: 700;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            color: #89566b;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background: #fceaf1;
            border: 1px solid #e8bdce;
            border-radius: 10px;
            padding: 12px 14px;
            color: #75495d;
        }

        .form-control:focus,
        .form-select:focus {
            background: #fff7fa;
            border-color: #d995b1;
            box-shadow: 0 0 0 3px #d995b122;
            color: #75495d;
        }

        .form-control::placeholder {
            color: #bd91a3;
        }

        .input-group-text {
            background: #f3d0de;
            border: 1px solid #e8bdce;
            color: #995d76;
            border-radius: 10px 0 0 10px;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .btn-add {
            background: #e9a9c3;
            color: #713e55;
            border: none;
            border-radius: 10px;
            padding: 11px 22px;
            font-weight: 700;
        }

        .btn-add:hover {
            background: #dc8eae;
            color: white;
        }

        .btn-cancel {
            background: #e9dce2;
            color: #765b67;
            border: none;
            border-radius: 10px;
            padding: 11px 22px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #dcc8d1;
            color: #654454;
        }

        .form-footer {
            border-top: 1px solid #edcbd8;
            margin-top: 30px;
            padding-top: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .required-note {
            color: #b17b91;
            font-size: 13px;
        }

        @media(max-width: 576px) {
            .card-body {
                padding: 20px;
            }

            .form-footer {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .form-footer .btn,
            .form-footer a {
                text-align: center;
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container py-5">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="page-icon">
            <i class="bi bi-person-plus-fill"></i>
        </div>

        <h2>Add New Supplier</h2>
        <p>Enter the supplier information below to add a new record.</p>
    </div>

    <!-- FORM CARD -->
    <div class="card">

        <div class="card-header">
            <h5><i class="bi bi-pencil-square me-2"></i>Supplier Information</h5>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="row g-4">

                    <div class="col-md-6">
                        <label class="form-label">Supplier Name</label>
                        <input type="text" name="supplier_name"
                               class="form-control"
                               placeholder="Enter supplier name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name"
                               class="form-control"
                               placeholder="Enter company name" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email"
                               class="form-control"
                               placeholder="example@email.com" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="tel" name="phone"
                               class="form-control"
                               placeholder="Enter contact number" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address"
                               class="form-control"
                               placeholder="Enter complete address" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Product Supplied</label>
                        <input type="text" name="product"
                               class="form-control"
                               placeholder="Enter product supplied" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Supplier Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                </div>

                <!-- BUTTONS -->
                <div class="form-footer">

                    <span class="required-note">
                        <i class="bi bi-info-circle me-1"></i>
                        All fields are required.
                    </span>

                    <div class="d-flex gap-2">
                        <a href="index.php?view=suppliers" class="btn-cancel">
                            <i class="bi bi-arrow-left me-1"></i> Cancel
                        </a>

                        <button type="submit" class="btn btn-add">
                            <i class="bi bi-plus-circle me-1"></i> Add Supplier
                        </button>
                    </div>

                </div>

            </form>

        </div>
    </div>

    <div class="text-center mt-4">
        <small style="color:#b17b91;">
            Supplier Management System
        </small>
    </div>

</div>

</body>
</html>
