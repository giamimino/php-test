<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}

$statement = $pdo->prepare('SELECT * FROM products');
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- style -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Products</h1>

<p>
    <a href="create.php" type="button" class="btn btn-sm btn-success">Add Product</a>
</p>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Image</th>
      <th scope="col">Title</th>
      <th scope="col">Price</th>
      <th scope="col">Create Date</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as  $i => $product) { ?>
        <tr>
          <th scope="row"><?php echo $i + 1 ?></th>
          <td>
              <?php if ($product['image']): ?>
                  <img src="<?php echo $product['image'] ?>" alt="<?php echo $product['title'] ?>" style="width: 50px;">
              <?php endif; ?>
          </td>
          <td><?php echo $product['title'] ?></td>
          <td><?php echo $product['price'] ?>$</td>
          <td><?php echo $product['create_date'] ?></td>
          <td>
              <a href="update.php?id=<?php echo $product['id'] ?>" type="button" class="btn btn-sm btn-outline-primary">Edit</a>
              <a href="delete.php?id=<?php echo $product['id'] ?>" type="button" class="btn btn-sm btn-outline-danger">Delete</a>
          </td>
        </tr>
    <?php } ?>
  </tbody>
</table>

</body>
</html>
