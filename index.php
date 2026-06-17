<?php
include "config.php";

$search =
$_GET['search']
?? '';

$category =
$_GET['category']
?? '';

$sql =
"
SELECT p.*,c.name category,
s.name supplier
FROM products p

JOIN categories c
ON p.category_id=c.id

JOIN suppliers s
ON p.supplier_id=s.id

WHERE 1=1
";

if($search!=''){
$sql .=
" AND
(
p.name LIKE
'%$search%'

OR

p.description
LIKE
'%$search%'
)";
}

if($category!=''){
$sql .=
" AND c.id=$category";
}

$products=
$conn->query($sql);

$categories=
$conn->query(
"SELECT * FROM categories"
);

$stats=
$conn->query(
"
SELECT
COUNT(*) total,
SUM(stock) total_stock,
SUM(price*stock) total_value
FROM products
"
)->fetch_assoc();

?>

<link rel="stylesheet"
href="style.css">

<h1>Inventory</h1>

<a href="add.php">
+ Add Product
</a>

<form>

<input
name="search"
placeholder="Search"
>

<select name="category">

<option value="">
All
</option>

<?php
while(
$c=
$categories->fetch_assoc()
):
?>

<option
value="<?= $c['id'] ?>">

<?= $c['name'] ?>

</option>

<?php endwhile; ?>

</select>

<button>
Filter
</button>

</form>

<div class="card">

Products

<br>

<?= $stats['total'] ?>

</div>

<div class="card">

Stock

<br>

<?= $stats['total_stock'] ?>

</div>

<div class="card">

Value

<br>

<?= $stats['total_value'] ?>

</div>

<table>

<tr>

<th>Name</th>

<th>Price</th>

<th>Stock</th>

<th>Category</th>

<th>Supplier</th>

<th>Action</th>

</tr>

<?php
while(
$row=
$products->fetch_assoc()
):
?>

<tr
class="<?= $row['stock']<20?'low':'' ?>">

<td>
<?= $row['name'] ?>
</td>

<td>
<?= $row['price'] ?>
</td>

<td>
<?= $row['stock'] ?>
</td>

<td>
<?= $row['category'] ?>
</td>

<td>
<?= $row['supplier'] ?>
</td>

<td>

<a href=
"edit.php?id=
<?= $row['id'] ?>">

Edit

</a>

</td>

</tr>

<?php endwhile; ?>

</table>