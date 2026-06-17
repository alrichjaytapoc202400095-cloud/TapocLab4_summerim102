<?php

include "config.php";

$id=
$_GET['id'];

$product=
$conn->query(
"
SELECT *
FROM products
WHERE id=$id
"
)->fetch_assoc();

if($_POST){

$name=
$_POST['name'];

$desc=
$_POST['description'];

$price=
$_POST['price'];

$stock=
$_POST['stock'];

$category=
$_POST['category'];

$supplier=
$_POST['supplier'];

$conn->query(
"
UPDATE products
SET

name='$name',

description='$desc',

price='$price',

stock='$stock',

category_id='$category',

supplier_id='$supplier'

WHERE id=$id
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
value="<?= $product['name'] ?>">

<input
name="description"
value="<?= $product['description'] ?>">

<input
name="price"
value="<?= $product['price'] ?>">

<input
name="stock"
value="<?= $product['stock'] ?>">

<select
name="category">

<?php
while(
$c=
$cat->fetch_assoc()
):
?>

<option
value="<?= $c['id'] ?>"

<?= $c['id']==$product['category_id']
?
'selected'
:
''
?>

>

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
value="<?= $s['id'] ?>"

<?= $s['id']==$product['supplier_id']
?
'selected'
:
''
?>

>

<?= $s['name'] ?>

</option>

<?php endwhile; ?>

</select>

<button>
Update
</button>

</form>