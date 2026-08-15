<?php
session_start();
require 'db.php';
header('Content-Type: application/json');

// Security check
if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_GET['action'] ?? '';

if ($action == 'get_data') {
    // 1. Fetch Inventory
    $inv_res = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $inventory = [];
    while($row = $inv_res->fetch_assoc()) {
        // Cast stock to integer for the frontend charts
        $row['stock'] = (int)$row['stock']; 
        $inventory[] = $row;
    }

    // 2. Fetch Ledger
    $ledg_res = $conn->query("SELECT * FROM ledger ORDER BY id DESC");
    $ledger = [];
    while($row = $ledg_res->fetch_assoc()) {
        $ledger[] = [
            'type' => $row['type'],
            'product' => $row['product'],
            'qty' => $row['qty'],
            'detail' => $row['detail'],
            'date' => date('n/j/Y', strtotime($row['created_at']))
        ];
    }

    echo json_encode(["inventory" => $inventory, "ledger" => $ledger]);
    exit();
}

if ($action == 'add_product') {
    $stmt = $conn->prepare("INSERT INTO products (name, sku, category, warehouse, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $input['name'], $input['sku'], $input['category'], $input['warehouse'], $input['stock']);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit();
}

if ($action == 'delete_product') {
    $stmt = $conn->prepare("DELETE FROM products WHERE sku = ?");
    $stmt->bind_param("s", $input['sku']);
    $stmt->execute();
    echo json_encode(["success" => true]);
    exit();
}

if ($action == 'transaction') {
    $type = $input['type']; // Stock In, Stock Out, Transfer, Adjustment
    $name = $input['name'];
    $qty = $input['qty'];
    $logQty = $input['logQty']; // String representation (e.g., "+10")
    $detail = $input['detail'];
    $warehouse = $input['warehouse'] ?? null;

    // Check if product exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE LOWER(name) = LOWER(?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update Product Stock or Warehouse
        if ($type === "Transfer") {
            $upd = $conn->prepare("UPDATE products SET warehouse = ? WHERE LOWER(name) = LOWER(?)");
            $upd->bind_param("ss", $warehouse, $name);
            $upd->execute();
        } else {
            $upd = $conn->prepare("UPDATE products SET stock = stock + ? WHERE LOWER(name) = LOWER(?)");
            $upd->bind_param("is", $qty, $name);
            $upd->execute();
        }

        // Insert into Ledger
        $log = $conn->prepare("INSERT INTO ledger (type, product, qty, detail) VALUES (?, ?, ?, ?)");
        $log->bind_param("ssss", $type, $name, $logQty, $detail);
        $log->execute();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Product not found."]);
    }
    exit();
}
?>