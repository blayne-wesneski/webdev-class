<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Craft Co.</title>

    <!-- Import Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!--Import Stylesheets-->
    <link rel="stylesheet" href="/stylesheets/main.css">

    <!--Import Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Space+Grotesk&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-sm">
        <a class="navbar-brand" href="/final-project/index.php">Custom Craft Co.</a>
        <button class="navbar-toggler navbar-dark" type="button" data-bs-toggle="collapse"
            data-bs-target="#main-navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/final-project/index.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/final-project/contact.html">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/final-project/about.html">About Us</a>
                </li>
            </ul>
        </div>
    </nav>


    <?php
    // Read the contents of the secrets.json file
    $secrets = file_get_contents('secrets.json');

    // Decode the JSON data into an associative array
    $secretsData = json_decode($secrets, true);

    // Get the database connection details
    $username = $secretsData['username'];
    $password = $secretsData['password'];
    $servername = $secretsData['server_name'];
    $dbname = $secretsData['dbname'];

    try {
        // Connect to the database
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch all products
        $stmt = $conn->prepare("SELECT * FROM PRODUCTS");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
    ?>


    <!-- Products List -->
    <div class="container">
        <h1>Products List</h1>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Product ID</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Product Description</th>
                    <th scope="col">Product Price</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php echo $product['productID']; ?>
                        </td>
                        <td><a href="/detail.php?product=<?php echo $product['productID']; ?>">
                                <?php echo $product['productName']; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $product['productDescription']; ?>
                        </td>
                        <td>
                            <?php echo "$" . $product['productPrice']; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

    <!-- Footer Section -->
    <footer class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <h6 class="text-uppercase font-weight-bold">Ready to buy?</h6>
                    <p>Send us a message using this form and we will be in touch! Or if you prefer, you can
                        see all of our contact options on our <a href="/final-project/contact.html">Contact Us!</a> page!</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h3 class="feature-title" style="color: white;">Get in Touch!</h3>
                    <form method="POST"
                        onsubmit="return validateForm()">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Name" name="name" id="name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email Address" name="email" id="email"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="4" name="text" id="text" required></textarea>
                        </div>
                        <input type="submit" class="btn btn-secondary btn-block" value="Send">
                    </form>

                    <!-- JavaScript Function to Validate Form Input -->
                    <script>
                        function validateForm() {
                            var name = document.getElementById("name").value;
                            var email = document.getElementById("email").value;
                            var text = document.getElementById("text").value;

                            // Clean input values
                            var cleanedName = name.replace(/[^A-Za-z\s]/g, ''); // Keep only letters and spaces
                            var cleanedEmail = email.replace(/[^\w.@]/g, ''); // Keep only letters, digits, dots, and @
                            var cleanedText = text.replace(/[^\w\s.,!?]/g, ''); // Keep only letters, digits, spaces, and common punctuation

                            // Assign cleaned values back to inputs
                            document.getElementById("name").value = cleanedName;
                            document.getElementById("email").value = cleanedEmail;
                            document.getElementById("text").value = cleanedText;

                            return true;
                        }
                    </script>
                </div>
            </div>
            <div class="footer-copyright text-center">© 2024 Copyright: Blayne Wesneski</div>
            <div class="text-center"><a href="/index.html">Return to main landing page</a></div>
        </div>
    </footer>
</body>

</html>