<?php
include "config.php";

$suppliers = $conn->query(
"SELECT s.id, s.name,
COUNT(p.id) AS products,
SUM(p.stock) AS total_stock,
SUM(p.price * p.stock) AS total_value
FROM suppliers s
LEFT JOIN products p ON s.id = p.supplier_id
GROUP BY s.id, s.name
ORDER BY s.name"
);
?>

<link rel="stylesheet" href="style.css">

<div class="page">
  <div class="top-bar">
    <h1>Suppliers</h1>
    <div class="actions">
      <a href="index.php" class="button back">Back to Inventory</a>
      <a href="report.php?type=supplier" class="button">Supplier Report</a>
    </div>
  </div>

  <table>
    <tr>
      <th>Supplier</th>
      <th>Products</th>
      <th>Total Stock</th>
      <th>Total Value</th>
      <th>Action</th>
    </tr>

    <?php while ($row = $suppliers->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= $row['products'] ?></td>
        <td><?= $row['total_stock'] ?? 0 ?></td>
        <td><?= $row['total_value'] ?? 0 ?></td>
        <td><a href="index.php?supplier=<?= $row['id'] ?>">View Products</a></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
