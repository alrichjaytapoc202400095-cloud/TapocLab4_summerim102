<?php
include "config.php";

$report = $conn->query(
"SELECT c.name,
COUNT(p.id) AS products,
SUM(p.stock) AS total_stock,
SUM(p.price * p.stock) AS total_value
FROM categories c
LEFT JOIN products p ON c.id = p.category_id
GROUP BY c.id, c.name
ORDER BY total_value DESC"
);
?>

<link rel="stylesheet" href="style.css">

<div class="page">
  <div class="top-bar">
    <h1>Report</h1>
    <a href="index.php" class="button back">Back to Inventory</a>
  </div>

  <table>
    <tr>
      <th>Category</th>
      <th>Products</th>
      <th>Total Stock</th>
      <th>Total Value</th>
    </tr>

    <?php while ($row = $report->fetch_assoc()): ?>
      <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= $row['products'] ?></td>
        <td><?= $row['total_stock'] ?? 0 ?></td>
        <td><?= $row['total_value'] ?? 0 ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>
