<?php

include 'connect.php';

$query_all = mysqli_query($conn, 
    "SELECT *
    FROM repair 
    INNER JOIN ambulance ON ambulance.ambulance_id = repair.ambulance_id");
$all_data = mysqli_fetch_all($query_all, MYSQLI_ASSOC);

// เลือก id รถพยาบาลใช้เป็น labels
$ambu_query = mysqli_query($conn, 
    "SELECT ambulance_plate FROM ambulance");
$ambu_data = mysqli_fetch_all($ambu_query, MYSQLI_ASSOC);

// เตรียมอาร์เรย์นับจำนวน
$count = array_fill_keys(array_column($ambu_data, 'ambulance_plate'), 0);

// นับจำนวนครั้งที่แต่ละ ambulance_id ปรากฏใน $all_data
foreach ($all_data as $row) {
    if (isset($count[$row['ambulance_plate']])) {
        $count[$row['ambulance_plate']]++;
    }
}

// แสดงผลลัพธ์
print_r($count);



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Hello world</h1>
    <div id="chart-labels" style="display: none;"><?php echo json_encode($ambu_data); ?></div>
    <canvas id="ambulance_fixed"></canvas>

    <script>
        const labels = <?php echo json_encode($ambu_data); ?>;
        const cars = <?php echo json_encode($count); ?>;
        // สร้างกราฟด้วย Chart.js
        const mychart = new Chart(document.getElementById("ambulance_fixed"), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'จำนวนครั้งที่ซ่อม',
                    data: cars,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

    </script>
</body>
</html>
