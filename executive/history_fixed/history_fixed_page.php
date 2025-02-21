<?php
include 'connect.php';

$query_all = mysqli_query(
    $conn,
    "SELECT * from repair 
        INNER JOIN ambulance on ambulance.ambulance_id = repair.ambulance_id
        INNER JOIN repair_staff on repair.repair_staff_id = repair_staff.repair_staff_id"
);
$all_data = mysqli_fetch_all($query_all, MYSQLI_ASSOC);

// ประเภท
$type_query = mysqli_query(
    $conn,
    "SELECT DISTINCT repair_type FROM repair"
);
$type_data = mysqli_fetch_all($type_query, MYSQLI_ASSOC);

// เหตุผล
$reason_query = mysqli_query(
    $conn,
    "SELECT DISTINCT repair_reason FROM repair"
);
$reason_data = mysqli_fetch_all($reason_query, MYSQLI_ASSOC);

// อะไหล่ที่ซ่อม
$repairing_query = mysqli_query(
    $conn,
    "SELECT DISTINCT repair_repairing FROM repair"
);
$repairing_data = mysqli_fetch_all($repairing_query, MYSQLI_ASSOC);

// สถานะ
$status_query = mysqli_query(
    $conn,
    "SELECT DISTINCT repair_status FROM repair"
);
$status_data = mysqli_fetch_all($status_query, MYSQLI_ASSOC);

//----------------------------
// Bar Chart แสดงจำนวนครั้งการซ่อม
// เลือก id รถพยาบาลใช้เป็น labels
$ambu_query = mysqli_query(
    $conn,
    "SELECT ambulance_plate FROM ambulance"
);
$ambu_data = mysqli_fetch_all($ambu_query, MYSQLI_ASSOC);

// เตรียมอาร์เรย์นับจำนวน
$countAmID = array_fill_keys(array_column($ambu_data, 'ambulance_plate'), 0);

// นับจำนวนครั้งที่แต่ละ ambulance_id ปรากฏใน $all_data
foreach ($all_data as $row) {
    if (isset($countAmID[$row['ambulance_plate']])) {
        $countAmID[$row['ambulance_plate']]++;
    }
}
//----------------------------

//----------------------------
// Pie Chart แสดงสัดส่วนประเภทการซ่อม
// ใช้ $type_data ที่เคย query ไปแล้วข้างบน

// เตรียมอาร์เรย์นับจำนวน
$countType = array_fill_keys(array_column($type_data, 'repair_type'), 0);
// นับจำนวนครั้งที่แต่ละ ambulance_id ปรากฏใน $all_data
foreach ($all_data as $row) {
    if (isset($countType[$row['repair_type']])) {
        $countType[$row['repair_type']]++;
    }
}
//----------------------------

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?ts=<?php echo time(); ?>">
    <link rel="stylesheet" href="styletable.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css"> -->
    <link href="https://fonts.googleapis.com/css2?family=Itim&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="history_script.js?ts=<?php echo time(); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>ประวัติการส่งซ่อมรถและอุปกรณ์การแพทย์</title>
</head>

<body>
    <header class="header">
        <div class="logo-section">
            <img src="img/logo.jpg" alt="" class="logo">
            <h1 href="ceo_home_page.html" style="font-family: Itim;">CEO - HOME</h1>
        </div>
        <nav class="nav" style="margin-left: 20%;">
            <a href="approve_page.html" class="nav-item">อนุมัติคำสั่งซื้อ/เช่า</a>
            <a href="approve_clam_page.html" class="nav-item">อนุมัติเคลม</a>
            <a href="summary_page.html" class="nav-item">สรุปยอดขาย</a>
            <a href="case_report_page.html" class="nav-item">ดูสรุปรายงานเคส</a>
            <a href="history_fixed_page.html" class="nav-item active">ประวัติการส่งซ่อมรถและอุปกรณ์การแพทย์</a>
            <a href="static_car_page.html" class="nav-item">สถิติการใช้งานรถ</a>
        </nav>
    </header>
    <br>

    <div>
        <div class="myChart">
            <div class="thisChart" id="chartLeft">
                <canvas id="ambulance_fixed"></canvas>
            </div>
            <div class="thisChart" id="chartRight">
                <canvas id="type_fixed"></canvas>
            </div>
            <table style="width: auto; height: 50px;">
                <thead>
                    <tr>
                        <th>รถพยาบาลทั้งหมด</th>
                        <th>พร้อม</th>
                        <th>ไม่พร้อม</th>
                    </tr>
                </thead>
                <tbody>
                        <td>fff</td>
                </tbody>
            </table>
        </div>

    </div>



    <div class="search-section">

        <!-- <div class="search-container">
            <input type="text" placeholder="ค้นหา..." class="search-input">
            <button class="search-button">
                <i class="fa-solid fa-magnifying-glass"></i> 
            </button>
        </div> -->
        <div class="filter-icon">
            <i class="fa-solid fa-filter"></i> <!-- ไอคอน Filter -->
        </div>

        <div class="filter-sidebar" id="filterSidebar">
            <div class="sidebar-header">
                <h2>ตัวกรอง</h2>
                <button class="close-sidebar">&times;</button>
            </div>
            <div class="sidebar-content">
                <label for="calendarSelect">เลือกวันที่:</label>
                <input class="calendar-selected" id="calendarSelect1" type="date" placeholder="เลือกวันที่"> ถึง
                <input class="calendar-selected" id="calendarSelect2" type="date" placeholder="เลือกวันที่">

                <label for="">ระดับรถ:</label>
                <div class="checkbox">
                    <input id="level_select1" type="checkbox" value="1" checked> Level 1
                    <input id="level_select2" type="checkbox" value="2" checked> Level 2
                    <input id="level_select3" type="checkbox" value="3" checked> Level 3
                </div> <br>

                <label for="">สาเหตุ:</label>
                <select id="reason_select" class="filter-select">
                    <option value="" selected>เลือกสาเหตุ</option>
                    <?php foreach ($reason_data as $row) { ?>
                        <option value="<?php echo $row["repair_reason"]; ?>">
                            <?php echo $row["repair_reason"]; ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="">อะไหล่:</label>
                <select id="repairing_select" class="filter-select">
                    <option value="" selected>เลือกอะไหล่</option>
                    <?php foreach ($repairing_data as $row) { ?>
                        <option value="<?php echo $row["repair_repairing"]; ?>">
                            <?php echo $row["repair_repairing"]; ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="">สถานะการซ่อม:</label>
                <select id="status_select" class="filter-select">
                    <option value="" selected>สถานะการซ่อม</option>
                    <?php foreach ($status_data as $row) { ?>
                        <option value="<?php echo $row["repair_status"]; ?>">
                            <?php echo $row["repair_status"]; ?>
                        </option>
                    <?php } ?>
                </select>

                <label for="">ค่าใช้จ่าย:</label>
                <select id="cost_select" class="filter-select">
                    <option value="" selected>ค่าใช้จ่าย</option>
                    <option value=" < 10000">ต่ำกว่า 10,000 บาท</option>
                    <option value=" BETWEEN 10000 AND 50000">10,000-50,000 บาท</option>
                    <option value=" > 50000">มากกว่า 50,000 บาท</option>
                </select>

            </div>
        </div>

    </div>


    <?php
    //    foreach($all_data as $row)
    //     print_r($row["ambulance_id"]);
    ?>

    <select class="select" id="select_type" name="option" style="margin-left: 17%;">
        <option value="" disabled selected>เลือกประเภทของสิ่งที่ซ่อม</option>
        <?php foreach ($type_data as $row) { ?>
            <option value="<?php echo $row["repair_type"]; ?>">
                <?php echo $row["repair_type"]; ?>
            </option>
        <?php } ?>
    </select>



    <main id="main-content" class="main-content">
        <table>
            <thead>
                <tr>
                    <!-- <th></th> -->
                    <th>ทะเบียนรถพยาบาล</th>
                    <th>ระดับรถ</th>
                    <th>บันทึกโดย</th>
                    <th>วันที่บันทึก(ว-ด-ป)</th>
                    <th>วันที่ซ่อมเสร็จ</th>
                    <th>ประเภทการซ่อม</th>
                    <th>สาเหตุ</th>
                    <th>อุปกรณ์/อะไหล่</th>
                    <th>สถานะการซ่อม</th>
                    <th>ค่าใช้จ่าย(บาท)</th>
                    <!-- <th>ผ่านการซ่อมมาแล้ว(ครั้ง)</th> -->
                </tr>
            </thead>
            <tbody>
                <tr>

                    <!-- เมื่อเข้ามาครั้งแรก จะแสดงข้อมูลทั้งหมดจากตาราง repair -->
                    <?php foreach ($all_data as $rs_result) { ?>
                <tr>
                    <!-- ยังไม่ได้ให้แสดงรูป -->
                    <!-- <td> <img
                            src="img/van-ambulance-vehicle-emergency-medical-services-toyota-commuter-abl-commu-alsv-wang-saphung-hospital-loei-carryboy.jpg"
                            alt="เตียงผู้ป่วยแบบไฟฟ้า" class="equipment-image"></td> -->

                    <td><?php echo $rs_result['ambulance_plate']; ?></td>
                    <td><?php echo $rs_result['ambulance_level']; ?></td>

                    <td><?php echo $rs_result['repair_staff_firstname']; ?></td>
                    <td><?php echo $rs_result['repair_date']; ?></td>
                    <td><?php echo $rs_result['repair_success_datetime']; ?></td>

                    <td><?php echo $rs_result['repair_type']; ?></td>
                    <td><?php echo $rs_result['repair_reason']; ?></td>
                    <td><?php echo $rs_result['repair_repairing']; ?></td>
                    <td><?php echo $rs_result['repair_status']; ?></td>
                    <!-- ยังไม่มีข้อมูลที่เก็บตรงนี้ เลยยังดึงมาไม่ได้ -->
                    <td>
                        <?php if ($rs_result['repair_cost'] == '0') { ?>
                            <?php echo "-" ?>
                        <?php } else { ?>
                            <?php echo $rs_result['repair_cost'] ?>
                        <?php } ?>
                    </td>

                    <!-- คำนวณจากใน repair?
                            <td>
                                
                            </td> -->

                </tr>
            <?php } ?>

            </tr>
        </table>

        <!-- Modal เมื่อกด ดูรายละเอียด -->
        <div class="overlay" onclick="closeModal()"></div>
        <div class="modal">
            <h3>อุปกรณ์ทางการแพทย์ในรถ ทะเบียน: ณน 879</h3>
            <p><strong>AED:</strong> - </p>
            <p><strong>เตียง:</strong> ชำรุด</p>
            <p><strong>เครื่องช่วยหายใจ:</strong> - </p>
            <p><strong>เฝือกลม:</strong> ชำรุด</p>
            <p><strong>ถังออกซิเจน:</strong> หมดอายุ</p>
            <p><strong>อุปกรณ์ปฐมพยาบาล:</strong> - </p>
            <div class="modal-buttons">
                <button class="close" onclick="closeModal()">Close</button>
            </div>
        </div>
    </main>

    <script>
        // สคริปต์สำหรับเปิด-ปิด Sidebar
        document.addEventListener("DOMContentLoaded", () => {
            const filterIcon = document.querySelector(".filter-icon");
            const sidebar = document.getElementById("filterSidebar");
            const closeSidebar = document.querySelector(".close-sidebar");

            // เปิด Sidebar
            filterIcon.addEventListener("click", () => {
                sidebar.classList.add("open");
            });

            // ปิด Sidebar
            closeSidebar.addEventListener("click", () => {
                sidebar.classList.remove("open");
            });

            // ปิด Sidebar เมื่อคลิกนอก Sidebar
            document.addEventListener("click", (e) => {
                if (!sidebar.contains(e.target) && !filterIcon.contains(e.target)) {
                    sidebar.classList.remove("open");
                }
            });

        });

        // ตั้งค่าปฏิทิน Flatpickr
        flatpickr("#calendarSelect", {
            dateFormat: "Y-m-d", // รูปแบบวันที่เป็น YYYY-MM-DD
            onChange: function(selectedDates, dateStr, instance) {
                // เมื่อผู้ใช้เลือกวันที่, เรียกใช้งานฟังก์ชัน updateChart
                updateChart(dateStr);
            }
        });

        // ฟังก์ชันสำหรับเปิด Modal แสดงรายละเอียดของอุปกรณ์ทางการแพทย์ที่ไม่พร้อมใช้งาน
        function openModal() {
            document.querySelector('.modal').style.display = 'block';
            document.querySelector('.overlay').style.display = 'block';
        }

        function closeModal() {
            document.querySelector('.modal').style.display = 'none';
            document.querySelector('.overlay').style.display = 'none';
        }

        //----------------------------
        //Bar Chart แสดงจำนวนครั้งการซ่อมของรถแต่ละคัน
        const AmbuLabels = <?php echo json_encode(array_column($ambu_data, 'ambulance_plate')); ?>;
        const AmbuCars = <?php echo json_encode($countAmID); ?>;
        const AmbuChart = new Chart(document.getElementById("ambulance_fixed"), {
            type: 'bar',
            data: {
                labels: AmbuLabels,
                datasets: [{
                    label: 'จำนวนครั้งที่ซ่อม',
                    data: AmbuCars,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'จำนวนครั้งที่รถพยาบาลผ่านการซ่อม'
                    }
                }
            }
        });
        //----------------------------

        //----------------------------
        //Pie Chart แสดงสัดส่วนประเภทการซ่อมทั้งหมด
        const TypeLabels = <?php echo json_encode(array_column($type_data, 'repair_type')); ?>;
        const Types = <?php echo json_encode(array_map(fn($label) => $countType[$label] ?? 0, array_column($type_data, 'repair_type'))); ?>;

        const TypeChart = new Chart(document.getElementById("type_fixed"), {
            type: 'doughnut',
            data: {
                labels: TypeLabels,
                datasets: [{
                    label: 'จำนวน',
                    data: Types,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)'
                    ],
                    borderColor: 'rgb(166, 169, 175)',
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'สัดส่วนของประเภทการซ่อม'
                    }
                }
            }

        });
        //----------------------------
    </script>
</body>

</html>