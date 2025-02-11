<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style_shopping.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Product Equipment</title>
    <script src="javascrip_member/shopping.js?ts=<?php echo time(); ?>"" defer></script>
    <?php include 'connect.php'; ?>
</head>
<div class="top-navbar">
    <nav class="nav-links">
        <div><a href="contact.html">ติดต่อเรา</a></div>
        <div class="dropdown">
            <img src="image/user.png" alt="Logo" class="nav-logo">
            <div class="dropdown-menu">
                <a href="profile.html">โปรไฟล์</a>
                <a href="order-history.html">ประวัติคำสั่งซื้อ</a>
                <a href="claim.html">เคลมสินค้า</a>
                <a href="logout.html">ออกจากระบบ</a>
            </div>
        </div>
        <a href="index.html">
            <img src="image/united-states-of-america.png" alt="Logo" class="nav-logo">
        </a>
    </nav>
</div>


<!-- Navbar ชั้นล่าง -->
<div class="main-navbar">
    <nav class="nav-links">
        <div><a href="index.html">หน้าแรก</a></div>
        <div><a href="reservation_car.html">จองคิวรถ</a></div>
        <a href="index.html">
            <img src="image/Logo.png" alt="Logo" class="nav-logo1">
        </a>
        <div><a href="shopping.php" style="color: E88B71;">ซื้อ/เช่าอุปกรณ์ทางการแพทย์</a></div>
    </nav>

    <div class="cart-icon">
        <a href="cart.html">
            <i class="fas fa-shopping-cart"></i>
        </a>
    </div>
</div>


<!--search-->

<body>
    <div class="search-section">
        <div class="search-container">
            <input type="text" placeholder="ค้นหา..." class="search-input">
            <button class="search-button">
                <i class="fas fa-search"></i> <!-- ไอคอนแว่นขยาย -->
            </button>
        </div>
        <div class="filter-icon">
            <i class="fas fa-bars"></i> <!-- ไอคอน Filter -->
        </div>
    </div>


    <!-- Sidebar -->
    <div class="filter-sidebar" id="filterSidebar">
        <div class="sidebar-header">
            <h2>ระบุความต้องการของคุณ</h2>
            <button class="close-sidebar">&times;</button>
        </div>
        <div class="sidebar-content">

            <label for="category">ประเภทสินค้า:</label>
            <select id="category" class="filter-select">
                <option value="" selected hidden>ทั้งหมด</option>
                <option value="อุปกรณ์วัดและตรวจสุขภาพ">อุปกรณ์วัดและตรวจสุขภาพ</option>
                <option value="อุปกรณ์ช่วยการเคลื่อนไหว">อุปกรณ์ช่วยการเคลื่อนไหว</option>
                <option value="อุปกรณ์สำหรับฟื้นฟูและกายภาพบำบัด">อุปกรณ์สำหรับฟื้นฟูและกายภาพบำบัด</option>
                <option value="อุปกรณ์สุขอนามัย">อุปกรณ์สุขอนามัย</option>
                <option value="อุปกรณ์ช่วยหายใจและระบบทางเดินหายใจ">อุปกรณ์ช่วยหายใจและระบบทางเดินหายใจ</option>
                <option value="อุปกรณ์ปฐมพยาบาล">อุปกรณ์ปฐมพยาบาล</option>
            </select>

            <label for="priceSort">ราคา:</label>
            <select id="priceSort" class="filter-select">
                <option value="" selected hidden>เรียงลำดับราคา</option>
                <option value="first">มากไปน้อย</option>
                <option value="basic">น้อยไปมาก</option>
            </select>

            <label for="productSort">เรียงลำดับสินค้า:</label>
            <select id="productSort" class="filter-select">
                <option value="" selected hidden>ทั้งหมด</option>
                <option value="first">สินค้าขายดีที่สุด</option>
                <option value="basic">เกี่ยวข้องที่สุด</option>
            </select>

            <label for="">ช่วงราคาสินค้า:</label>
            <div class="price-range">
                <input type="number" id="minPrice" placeholder="ต่ำสุด" min="0" max="1000000" value="0">
                <input type="range" id="minPriceRange" min="0" max="1000000" step="100" value="0" oninput="updateMinPrice()">
                <input type="range" id="maxPriceRange" min="0" max="1000000" step="100" value="1000000" oninput="updateMaxPrice()">
                <input type="number" id="maxPrice" placeholder="สูงสุด" min="0" max="1000000" value="1000000">
            </div>
            <button onclick="applyFilters()">ใช้ตัวกรอง</button>

        </div>
    </div>

    <!-- ดึงข้อมูลจากฐานข้อมูล -->
    <?php 
        $sql = "SELECT * 
        FROM medical_equipment
        ORDER BY equipment_id";
        $result = mysqli_query($conn, $sql);
    ?>

    <!-- ส่วนที่ใช้แสดงข้อมูล -->
    <div id="prodContian">
        <section class="product-container">
        <?php   
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="product">
            <a href="product_detailstest.php?id=<?= $row['equipment_id'] ?>">
                <img src="image/<?= $row['equipment_image'] ?>" alt="<?= $row['equipment_name'] ?>">
                <p>ID: <?= $row['equipment_id'] ?></p>
                <p><?= $row['equipment_name'] ?></p>
                <p class="cost">฿ <?= number_format($row['equipment_price'],) ?></p>
            </a>
        </div>
        <?php
        }
        mysqli_close($conn);
        ?>
        </section>
    </div>
    

</body>
<!-- <script>
    
    document.addEventListener("DOMContentLoaded", function () {
    const filterButton = document.querySelector(".search-button"); 
    const categorySelect = document.getElementById("category"); 
    const priceSortSelect = document.getElementById("priceSort"); 
    const productSortSelect = document.getElementById("productSort"); 
    const minPriceInput = document.getElementById("minPrice"); 
    const maxPriceInput = document.getElementById("maxPrice"); 

    filterButton.addEventListener("click", function () {
        const category = categorySelect.value;
        const priceSort = priceSortSelect.value;
        const productSort = productSortSelect.value;
        const minPrice = minPriceInput.value;
        const maxPrice = maxPriceInput.value;

        let queryString = `?category=${category}&priceSort=${priceSort}&productSort=${productSort}&minPrice=${minPrice}&maxPrice=${maxPrice}`;
        window.location.href = "shopping.php" + queryString;
    });

});

</script> -->

</html>