<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:login.php');
}

// Ensure the price column is set to FLOAT with two decimal places
mysqli_query($conn, "ALTER TABLE `products` MODIFY COLUMN `price` FLOAT(10, 2) NOT NULL") or die('Query failed');

// Fetch genres for dropdown
$genres_query = mysqli_query($conn, "SELECT * FROM `genres`") or die('Query failed');
$genres = [];
while ($row = mysqli_fetch_assoc($genres_query)) {
    $genres[] = $row;
}

// Add a product
if (isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $genre_id = $_POST['genre_id'];
    $price = number_format((float)$_POST['price'], 2, '.', ''); // Format as float with 2 decimals
    $image = mysqli_real_escape_string($conn, $_FILES['image']['name']); // Escape the image name
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];

    $unique_image_name = uniqid() . '-' . $image;
    $image_folder = 'uploaded_img/' . $unique_image_name;

    if ($image_size > 2000000) {
        $message[] = 'Image size is too large.';
    } else {
        $select_product = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('Query failed');
        if (mysqli_num_rows($select_product) > 0) {
            $message[] = 'Product already exists.';
        } else {
            $query = "INSERT INTO `products` (name, author, genre_id, price, image) VALUES ('$name', '$author', '$genre_id', '$price', '$unique_image_name')";
            $add_product = mysqli_query($conn, $query) or die(mysqli_error($conn));
            if ($add_product) {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'Product added successfully!';
            } else {
                $message[] = 'Failed to add product.';
            }
        }
    }
}

// Delete a product
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('Query failed');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('Query failed');
    header('location:admin_products.php');
}

// Update a product
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_name = $_POST['update_name'];
    $update_author = $_POST['update_author'];
    $update_genre_id = $_POST['update_genre_id'];
    $update_price = number_format((float)$_POST['update_price'], 2, '.', ''); // Format as float with 2 decimals

    mysqli_query($conn, "UPDATE `products` SET name = '$update_name', author = '$update_author', genre_id = '$update_genre_id', price = '$update_price' WHERE id = '$update_p_id'") or die('Query failed');

    $update_image = $_FILES['update_image']['name'];
    $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
    $update_image_size = $_FILES['update_image']['size'];
    $update_folder = 'uploaded_img/' . $update_image;
    $update_old_image = $_POST['update_old_image'];

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Image file size is too large';
        } else {
            mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('Query failed');
            move_uploaded_file($update_image_tmp_name, $update_folder);
            unlink('uploaded_img/' . $update_old_image);
        }
    }
    header('location:admin_products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="add-products">
    <h1 class="title">Livros</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <h3>Adicionar Produto</h3>
        <input type="text" name="name" class="box" placeholder="Insira Nome do Livro" required>
        <input type="text" name="author" class="box" placeholder="Insira o Autor" required>
        <select name="genre_id" class="box" required>
            <option value="" disabled selected>Selecione o Gênero</option>
            <?php foreach ($genres as $genre) { ?>
                <option value="<?php echo $genre['id']; ?>"><?php echo $genre['name']; ?></option>
            <?php } ?>
        </select>
        <input type="number" step="0.01" min="0" name="price" class="box" placeholder="Valor" required>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
        <input type="submit" value="Adicionar" name="add_product" class="btn">
    </form>
</section>

<section class="show-products">
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT products.*, genres.name AS genre_name FROM `products` LEFT JOIN `genres` ON products.genre_id = genres.id") or die('Query failed');
        if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
                ?>
                <div class="box">
                    <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
                    <div class="name"><?php echo $fetch_products['name']; ?></div>
                    <div class="author">Autor: <?php echo $fetch_products['author']; ?></div>
                    <div class="genre">Gênero: <?php echo $fetch_products['genre_name']; ?></div>
                    <div class="price">€<?php echo number_format($fetch_products['price'], 2, '.', ''); ?></div>
                    <a href="admin_products.php?update=<?php echo $fetch_products['id']; ?>" class="option-btn">Update</a>
                    <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Deletar esse produto?');">Deletar</a>
                </div>
                <?php
            }
        } else {
            echo '<p class="empty">Sem produtos ainda!</p>';
        }
        ?>
    </div>
</section>

<section class="edit-product-form">
    <?php
    if (isset($_GET['update'])) {
        $update_id = $_GET['update'];
        $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('Query failed');
        if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
                ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
                    <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
                    <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
                    <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="box" required>
                    <input type="text" name="update_author" value="<?php echo $fetch_update['author']; ?>" class="box" required>
                    <select name="update_genre_id" class="box" required>
                        <option value="" disabled selected>Selecione o Gênero</option>
                        <?php foreach ($genres as $genre) { ?>
                            <option value="<?php echo $genre['id']; ?>" <?php echo $fetch_update['genre_id'] == $genre['id'] ? 'selected' : ''; ?>>
                                <?php echo $genre['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                    <input type="number" step="0.01" name="update_price" value="<?php echo number_format($fetch_update['price'], 2, '.', ''); ?>" min="0" class="box" required>
                    <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
                    <input type="submit" value="Update" name="update_product" class="btn">
                    <input type="reset" value="Cancel" id="close-update" class="option-btn">
                </form>
                <?php
            }
        }
    } else {
        echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
    }
    ?>
</section>

<script src="js/admin_script.js"></script>
</body>
</html>
