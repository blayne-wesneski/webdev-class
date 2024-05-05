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

    <!-- Main Content -->
    <div class="container col-xxl-8 px-4 py-5">
        <div class="row flex-lg-row-reverse align-items-center g-5 py-5">

            <?php
            function getDatabaseSecrets()
            {
                $secrets = file_get_contents('secrets.json');
                return json_decode($secrets, true);
            }
            $secretsData = getDatabaseSecrets();
            $username = $secretsData['username'];
            $password = $secretsData['password'];
            $servername = $secretsData['server_name'];
            $dbname = $secretsData['dbname'];
            ?>

            <!-- PHP Code Block to Fetch and Display Product Image -->
            <div class="col-10 col-sm-8 col-lg-6">

                <?php
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    if (isset($_GET['product'])) {
                        $productID = $_GET['product'];
                        $stmt = $conn->prepare("SELECT * FROM PRODUCTS WHERE productID = :productID");
                        $stmt->bindParam(':productID', $productID);
                        $stmt->execute();
                        $product = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($product) {
                            echo '<img src="/images/' . $product['productName'] . '.png" class="d-block mx-lg-auto img-fluid" alt="a product that says your design here"
                            width="400" height="400" loading="lazy" style="border-radius: 25%; border: solid 1px black;">';
                        } else {
                            echo "Product not found.";
                        }
                    } else {
                        echo "Product ID not provided.";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>

            </div>

            <!-- PHP Code Block to Fetch and Display Product Details -->
            <div class="col-lg-6">

                <?php
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    if (isset($_GET['product'])) {
                        $productID = $_GET['product'];
                        $stmt = $conn->prepare("SELECT * FROM PRODUCTS WHERE productID = :productID");
                        $stmt->bindParam(':productID', $productID);
                        $stmt->execute();
                        $product = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($product) {
                            echo "<script> var productPrice = " . $product['productPrice'] . ";</script>";
                            echo "<h2>Product Details</h2>";
                            echo "<p><strong>Product ID:</strong> " . $product['productID'] . "</p>";
                            echo "<p><strong>Product Name:</strong> " . $product['productName'] . "</p>";
                            echo "<p><strong>Description:</strong> " . $product['productDescription'] . "</p>";
                            echo "<p><strong>Price:</strong> $" . $product['productPrice'] . "</p>";
                            echo "<p><strong>Stock:</strong> " . $product['productStock'] . "</p>";
                            echo '<p id="BTC_Price"><strong>Current price in BTC:</strong></p>';
                            echo '<p id="ETH_Price"><strong>Current price in ETH:</strong></p>';
                            echo '<p id="LTC_Price"><strong>Current price in LTC:</strong></p>';
                        } else {
                            echo "Product not found.";
                        }
                    } else {
                        echo "Product ID not provided.";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>

                <script>
                    // JavaScript code to fetch cryptocurrency prices
                    fetch('https://api.coincap.io/v2/assets/bitcoin')
                        .then(response => response.json())
                        .then(data => {
                            const bitcoinPriceUSD = data.data.priceUsd;
                            const productPriceBTC = productPrice / bitcoinPriceUSD;
                            document.getElementById("BTC_Price").innerHTML = "<strong>Current price in BTC:</strong> " + productPriceBTC.toFixed(8);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });

                    fetch('https://api.coincap.io/v2/assets/ethereum')
                        .then(response => response.json())
                        .then(data => {
                            const ethereumPriceUSD = data.data.priceUsd;
                            const productPriceETH = productPrice / ethereumPriceUSD;
                            document.getElementById("ETH_Price").innerHTML = "<strong>Current price in ETH:</strong> " + productPriceETH.toFixed(18);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });

                    fetch('https://api.coincap.io/v2/assets/litecoin')
                        .then(response => response.json())
                        .then(data => {
                            const litecoinPriceUSD = data.data.priceUsd;
                            const productPriceLTC = productPrice / litecoinPriceUSD;
                            document.getElementById("LTC_Price").innerHTML = "<strong>Current price in LTC:</strong> " + productPriceLTC.toFixed(8);
                        })
                        .catch(error => {
                            console.error('Error fetching data:', error);
                        });
                </script>
            </div>
        </div>
    </div>

    <!-- Payment Methods Section -->
    <div class="features_background">
        <h1 class="display-5 fw-bold lh-1 mb-3" style="text-align: center; padding-top: 30px; padding-bottom: 15px;">
            Payment Methods</h1>
        <div class="container features">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h3 class="feature-title">Credit/Debit Card</h3>
                    <p>We accept most major types of credit/debit card.</p>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h3 class="feature-title">Mail Order</h3>
                    <p>We accept cash or check by mail order.</p>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h3 class="feature-title">Cryptocurrency</h3>
                    <p>We also accept payment in the form of cryptocurrency. We currently accept Bitcoin, Ethereum, and
                        Litecoin.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="page-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <h6 class="text-uppercase font-weight-bold">Ready to buy?</h6>
                    <p>Send us a message using this form and we will be in touch! Or if you prefer, you can
                        see all of our contact options on our <a href="/contact">Contact Us!</a> page!</p>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <h3 class="feature-title" style="color: white;">Get in Touch!</h3>
                    <!-- Contact Form -->
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
                            cleanedName = name.replace(/[^A-Za-z\s]/g, ''); // Keep only letters and spaces
                            cleanedEmail = email.replace(/[^\w.@]/g, ''); // Keep only letters, digits, dots, and @
                            cleanedText = text.replace(/[^\w\s.,!?]/g, ''); // Keep only letters, digits, spaces, and common punctuation

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