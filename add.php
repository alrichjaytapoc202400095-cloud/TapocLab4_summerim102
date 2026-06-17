<?php
include "config.php";

if($_POST){

$name=
$conn->real_escape_string(
$_POST['name']
);

$desc=
$conn->real_escape_string(
$_POST['description']
);

$price=
(float)
$_POST['price'];

$stock=
(int)
$_POST['stock'];

$category=
$_POST['category'];

$supplier=
$_POST['supplier'];

$conn->query(
"
INSERT INTO products
(
name,
description,
price,
stock,
category_id,
supplier_id
)

VALUES

(
'$name',
'$desc',
$price,
$stock,
$category,
$supplier
)
"
);

header(
"location:index.php"
);

}

$cat=
$conn->query(
"SELECT * FROM categories"
);

$sup=
$conn->query(
"SELECT * FROM suppliers"
);

?>

<form method="POST">

<input
name="name"
placeholder="Name"
required>

<input
name="description"
placeholder="Description"
required>

<input
name="price"
type="number">

<input
name="stock"
type="number">

<select
name="category">

<?php
while(
$c=
$cat->fetch_assoc()
):
?>

<option
value="<?= $c['id'] ?>">

<?= $c['name'] ?>

</option>

<?php endwhile; ?>

</select>

<select
name="supplier">

<?php
while(
$s=
$sup->fetch_assoc()
):
?>

<option
value="<?= $s['id'] ?>">

<?= $s['name'] ?>

</option>

<?php endwhile; ?>

</select>

<button>
Save
</button>

</form>