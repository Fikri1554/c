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

    .header-table {
        width: 100%;
        margin-bottom: 20px;
    }

    .header-table td {
        padding: 5px;
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
    <br>

    <table class="header-table">
        <tr>
            <td><strong>Nama Lengkap:</strong> <?php echo $fullname; ?></td>
            <td><strong>ID Person:</strong> <?php echo $idperson; ?></td>
        </tr>
        <tr>
            <td><strong>Last Vessel:</strong> <?php echo $lastvsl; ?></td>
            <td><strong>Company:</strong> <?php echo $company; ?></td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width:5%;">No</th>
                <th style="width:45%;">Nama Sertifikat</th>
                <th style="width:25%;">Issue Date</th>
                <th style="width:25%;">Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $trNya; ?>
        </tbody>
    </table>
</body>


</html>