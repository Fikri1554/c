<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Export PDF</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        color: #333;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th,
    .data-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .data-table th {
        background-color: #A70000;
        color: white;
    }

    h4 {
        text-align: center;
        text-transform: uppercase;
        margin: 0;
    }
    </style>
</head>

<body>
    <h4><u>Daftar Sertifikat</u></h4>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:95%;">Nama Sertifikat</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $trNya; ?>
        </tbody>
    </table>
</body>

</html>