<?php
include 'connection.php';

function displayMenuSection($conn, $categoryId, $limit = null, $sectionTitle) {
    if ($limit === null) {
        $sql = "SELECT menuId, menuName, menuPrice, menuDesc, menuPic FROM menu WHERE categoryId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
    } else {
        $sql = "SELECT menuId, menuName, menuPrice, menuDesc, menuPic FROM menu WHERE categoryId = ? LIMIT " . intval($limit);
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>$sectionTitle</h2>";
    echo '<div class="menu-container">';

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menuName = htmlspecialchars($row['menuName']);
            $menuPrice = number_format($row['menuPrice'], 2);
            $menuDesc = htmlspecialchars($row['menuDesc']);
            $menuPic = htmlspecialchars($row['menuPic']);

            echo "
            <div class=\"food-menu-box\">
                <div class=\"food-menu-img\">
                    <img src=\"images/{$menuPic}\" alt=\"{$menuName}\" class=\"img-responsive img-curve\">
                </div>
                <div class=\"food-menu-desc\">
                    <h4>{$menuName}</h4>
                    <p class=\"food-price\">RM {$menuPrice}</p>
                    <p class=\"food-detail\">{$menuDesc}</p>
                    <br>
                    <button class=\"btn btn-primary\" onclick=\"openModal('{$menuName}', 'RM {$menuPrice}', 'images/{$menuPic}', {$row['menuId']})\">Order Now</button>
                </div>
            </div>";
        }
    } else {
        echo "<div class='error'>No $sectionTitle available.</div>";
    }

    echo '</div>';
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Full Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/menu.css">
    <style>
        /* Optional: simple modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1050;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            z-index: 1040;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .cart-link {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: inherit;
        }

        .btn-primary {
            background-color: #ff6b81;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="menu-title-bar">
    <h1>Our Menu</h1>
    <button class="cart-link" onclick="openCartOptionModal()">
        <img src="images/cart.png" alt="Cart" class="cart-icon">
        <span class="cart-text">Cart</span>
    </button>
</div>

<div class="menu-header">
    <img src="images/CSC264 LOGO 1.png" alt="CSC264 Logo">
    <br>
    <a href="index.php" class="back-btn">⬅ Back to Homepage</a>
</div>

<?php
displayMenuSection($conn, 1, null, "Malay Food");
displayMenuSection($conn, 2, null, "Western Food");
displayMenuSection($conn, 3, null, "Korean Food");
displayMenuSection($conn, 4, null, "Drinks");
$conn->close();
?>

<!-- Food Modal Backdrop -->
<div class="modal-backdrop" id="modalBackdrop"></div>

<!-- Food Order Modal -->
<div class="modal" id="foodModal">
    <button class="close-btn" onclick="closeModal()" aria-label="Close">&times;</button>
    <img id="modal-img" src="" alt="Food Image">
    <h2 id="modal-name"></h2>
    <div class="price" id="modal-price"></div>

    <div class="qty-control">
        <button type="button" onclick="decreaseQty()">−</button>
        <input type="text" id="qty" value="1" readonly>
        <button type="button" onclick="increaseQty()">+</button>
    </div>

    <button class="add-btn" onclick="addToCart()">Add to cart</button>
</div>

<!-- Cart Option Modal -->
<div class="modal-backdrop" id="cartModalBackdrop"></div>

<div class="modal" id="cartOptionModal">
    <button class="close-btn" onclick="closeCartOptionModal()" aria-label="Close">&times;</button>
    <h2>Select Order Type</h2>
    <button class="btn btn-primary" onclick="redirectTo('Pickup')">Pickup</button>
    <button class="btn btn-primary" onclick="redirectTo('Delivery')">Delivery</button>
</div>


<script>
let selectedMenuId = null;

function openModal(name, price, imgSrc, menuId) {
    document.getElementById('modal-name').textContent = name;
    document.getElementById('modal-price').textContent = price;
    document.getElementById('modal-img').src = imgSrc;
    document.getElementById('foodModal').style.display = 'flex';
    document.getElementById('modalBackdrop').style.display = 'block';
    document.getElementById('qty').value = 1;
    selectedMenuId = menuId;
}

function closeModal() {
    document.getElementById('foodModal').style.display = 'none';
    document.getElementById('modalBackdrop').style.display = 'none';
}

function increaseQty() {
    let qty = document.getElementById('qty');
    qty.value = parseInt(qty.value) + 1;
}

function decreaseQty() {
    let qty = document.getElementById('qty');
    if (parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
    }
}

function addToCart() {
    const qty = document.getElementById('qty').value;
    fetch('addToCart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `menuId=${selectedMenuId}&cartQuantity=${qty}`
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closeModal();
    })
    .catch(error => console.error('Error:', error));
}

// Cart Modal Functions
function openCartOptionModal() {
    document.getElementById('cartOptionModal').style.display = 'flex';
    document.getElementById('cartModalBackdrop').style.display = 'block';
}

function closeCartOptionModal() {
    document.getElementById('cartOptionModal').style.display = 'none';
    document.getElementById('cartModalBackdrop').style.display = 'none';
}

function redirectTo(orderType) {
    fetch('setOrderType.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `orderType=${orderType}`
    })
    .then(response => response.text())
    .then(data => {
        console.log("OrderType response:", data); // Debug
        if (orderType === 'Pickup') {
            window.location.href = 'checkout.php';
        } else {
            window.location.href = 'address.php';
        }
    })
    .catch(error => console.error('Error:', error));
}

window.onclick = function(event) {
    const foodModal = document.getElementById('foodModal');
    const foodBackdrop = document.getElementById('modalBackdrop');
    const cartModal = document.getElementById('cartOptionModal');
    const cartBackdrop = document.getElementById('cartModalBackdrop');

    if (event.target === foodBackdrop) closeModal();
    if (event.target === cartBackdrop) closeCartOptionModal();
};

function redirectTo(orderType) {
    // Set session data using AJAX
    fetch('setOrderType.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `orderType=${orderType}`
    })
    .then(() => {
        if (orderType === 'Pickup') {
            window.location.href = 'checkout.php';
        } else {
            window.location.href = 'address.php';
        }
    })
    .catch(error => console.error('Error:', error));
}

</script>

</body>
</html>
