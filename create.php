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

$title = '';
$description = '';
$price = '';
$imagePath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');

    // Handle image upload
    $image = $_FILES['image'] ?? null;
    if ($image && $image['tmp_name']) {
        $imagePath = 'assets/image/' . time() . '-' . $image['name'];
        mkdir(dirname($imagePath));
        move_uploaded_file($image['tmp_name'], $imagePath);
    }

    // Prepare SQL statement
    $stmt = $pdo->prepare("INSERT INTO products (title, description, price, image, create_date) 
                            VALUES (:title, :description, :price, :image, :date)");

    // Bind parameters
    $stmt->bindValue(':title', $title);
    $stmt->bindValue(':description', $description);
    $stmt->bindValue(':price', $price);
    $stmt->bindValue(':image', $imagePath);
    $stmt->bindValue(':date', $date);

    // Execute the statement
    $stmt->execute();
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- style -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Create new Product</h1>

<p>
    <a href="index.php" type="button" class="btn btn-sm btn-danger">Cancel</a>
</p>

<form method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label style="margin: 10px 0 5px 0;">Product Image</label>
    <input type="file" name="image" class="form-control">
  </div>

  <div class="form-group">
    <label style="margin: 10px 0 5px 0;">Product Title</label>
    <input type="text" name="title" class="form-control" value="<?php echo $title ?>" required>
  </div>

  <div class="form-group">
    <label style="margin: 10px 0 5px 0;">Product Description</label>
    <textarea type="text" name="description" class="form-control"><?php echo $description ?></textarea>
  </div>

  <div class="form-group">
    <label style="margin: 10px 0 5px 0;">Product Price</label>
    <input type="number" name="price" class="form-control" value="<?php echo $price ?>" required>
  </div>

  <button type="submit" style="margin-top: 10px;" class="btn btn-primary">Submit</button>
</form>

</body>
</html>
