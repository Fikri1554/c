<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificates And Documents Transmittal Form</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 12px;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td><img src="<?php echo base_url('assets/img/logo_aes.png'); ?>" style="width: 50px;"></td>
            <td style="font-size: 18px; font-weight: bold; text-align: left;">PT. ANDHINI EKA KARYA SEJAHTERA</td>
            <td style="text-align: right; font-size: 8px;">SRPS LICENSE NO:SIUPPAK 12.12 Tahun 2014</td>
            <td><img src="<?php echo base_url('assets/img/iso.png'); ?>" style="width: 50px;"></td>
            <td><img src="<?php echo base_url('assets/img/compliance.png'); ?>" style="width: 50px;"></td>
        </tr>
    </table>
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; font-size: 12px; font-weight: bold; margin-right: 2px;">
                <label>Certificates And Documents Transmittal Form</label>
            </td>
            <td style="width: 50%; text-align: right;">
                <table style="border-collapse: collapse; border: 1px solid black; font-size: 12px; width: 50%;">
                    <tr>
                        <td style="border: 1px solid black; font-weight: bold; text-align: left;">Rank</td>
                        <td style="border: 1px solid black; text-align: left;">
                            <?php echo htmlspecialchars($crewRank); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; font-weight: bold; text-align: left;">Name</td>
                        <td style="border: 1px solid black; text-align: left;">
                            <?php echo htmlspecialchars($crewName); ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="border: 1px solid black; font-weight: bold; text-align: left;">Vessel</td>
                        <td style="border: 1px solid black; text-align: left;">
                            <?php echo htmlspecialchars($vesselName); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p style="margin-top: -10px;"><strong>To the Master</strong><br><strong>From JKT - Crew Department</strong></p>
    <p style="margin-top: -20px;">The Following Certificates and TravelDocuments have been checked and given to
        following. Crew which please check and acknowledge receipt by signing this form and returning it to our
        office. Meantime please email aes@andhika.com acknowledging receipt for same. Should there be any discrepancies
        please advise AES-CD Jkt immediately.
    </p>
    <table style="width: 100%; border-collapse: collapse; text-align: center;">
        <tr>
            <th style="padding: 5px; background-color: #f2f2f2; text-align: left; width: 30%;">Certificates</th>
            <th style="padding: 5px; background-color: #f2f2f2;">Submitted</th>
            <th style="padding: 5px; background-color: #f2f2f2;">Issued</th>
            <th style="padding: 5px; background-color: #f2f2f2;">Expire</th>
            <th style="padding: 5px; background-color: #f2f2f2; text-align: left; width: 30%;">Document Number</th>
        </tr>
        <?php echo $certTable; ?>
    </table>
    <div style="margin-top: 30px;">
        <p>I the Crew mentioned above acknowledge receipt of the above certificates & documents and will ensure to bring
            it along onboard the vessel I am assigned to</p>
        <p>
            <strong>Signature, Rank, and Name</strong>:___________________
            (<?php echo htmlspecialchars($crewRank); ?>), (<?php echo htmlspecialchars($crewName); ?>)
        </p>
        <p>I, Master of the above vessel acknowledge receipt of the above certificates & documents from the crew and
            will advice JKT - CD of any discrepancies upon receipt and will advice JKT - CD 1 months notice. Should any
            of the above becomes invalid / expire during the service of the crew onboard the vessel.</p>
        <p><strong>Signature & Name</strong>: _______________________________</p>
    </div>
</body>

</html>