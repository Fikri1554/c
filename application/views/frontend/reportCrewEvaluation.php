<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Crew Evaluation Report</title>
</head>

<body style="font-family: Arial, 'Calibri', sans-serif; font-size: 12px; margin: 20px;">

    <div style="text-align: center; margin-bottom: 20px;">
        <div style="font-size: 18px; font-weight: bold; text-align: left;">
            <img src="<?php echo base_url(); ?>assets/img/logo_ady.png" style="width: 50%; display: block;">
        </div>
        <div
            style="font-size: 16px; font-weight: bold; margin-top: 5px; border: 1px solid; display: inline-block; padding: 4px 10px;">
            CREW EVALUATION REPORT
        </div>
    </div>

    <table style="border: 1px solid black; width: 100%; margin-bottom: 15px; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px; vertical-align: top;" width="50%">Vessel:
                <?php echo $vessel; ?></td>
            <td style="padding: 8px; vertical-align: top;" width="50%">Date of Report (dd/mm/yy):
                <?php echo $dateOfReport; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; vertical-align: top;">Seafarer's Name :
                <?php echo $seafarerName; ?></td>
            <td style="padding: 8px; vertical-align: top;">Reporting Period From:
                <?php echo $reportPeriodFrom; ?></td>
        </tr>
        <tr>
            <td style="padding: 8px; vertical-align: top;">Rank : <?php echo $rank; ?></td>
            <td style="padding: 8px; vertical-align: top;">Reporting Period To:
                <?php echo $reportPeriodTo; ?></td>
        </tr>
        <tr>
            <td colspan="2" style="padding: 8px; vertical-align: top;"><strong>Reason for the Report:</strong></td>
        </tr>
        <tr>
            <td style="padding: 8px; vertical-align: top;">
                <div>
                    <span
                        style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin-right: 5px;">
                        <?php echo $reasonMidway; ?>
                    </span>
                    Midway through contract
                </div>
                <div>
                    <span
                        style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin-right: 5px;">
                        <?php echo $reasonLeaving; ?>
                    </span>
                    Reporting crew leaving vessel
                </div>
            </td>
            <td style="padding: 8px; vertical-align: top;">
                <div>
                    <span
                        style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin-right: 5px;">
                        <?php echo $reasonSigningOff; ?>
                    </span>
                    Seafarer signing off vessel
                </div>
                <div>
                    <span
                        style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin-right: 5px;">
                        <?php echo $reasonSpecial; ?>
                    </span>
                    Special request
                </div>
            </td>
        </tr>
    </table>

    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <tr>
            <th style="border: 1px solid black; padding: 5px; text-align: left;">Criteria</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">Excellent (4)</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">Good (3)</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">Fair (2)</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">Poor (1)</th>
            <th style="border: 1px solid black; padding: 5px; text-align: center;">Identify Training Needs</th>
        </tr>
        <?php echo $criteriaTable; ?>
    </table>

    <div style="border: 1px solid black; padding: 10px; margin: 10px 0;">
        <div style="margin-bottom: 5px;">
            <label><strong>General Comments highlighting strengths/weaknesses:</strong></label>
        </div>
        <div style="margin-bottom: 5px;">Master: <?php echo $masterComments; ?></div>
        <div style="margin-bottom: 5px;">Reporting Officer: <?php echo $reportingOfficerComments; ?></div>

        <div style="margin-top: 10px;">
            <label><strong>Re Employ:</strong></label>
            <span
                style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin: 0 5px;">
                <?php echo ($reEmploy === 'Y') ? '&#10004;' : '&nbsp;'; ?>
            </span> Yes
            <span
                style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin: 0 5px;">
                <?php echo ($reEmploy === 'N') ? '&#10004;' : '&nbsp;'; ?>
            </span> No
            <br><br>
            <label><strong>Promote:</strong></label>
            <span
                style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin: 0 5px;">
                <?php echo ($promote === 'Y') ? '&#10004;' : '&nbsp;'; ?>
            </span> Yes
            <span
                style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin: 0 5px;">
                <?php echo ($promote === 'N') ? '&#10004;' : '&nbsp;'; ?>
            </span> No
            <span
                style="display: inline-block; width: 14px; height: 14px; border: 1px solid black; text-align: center; line-height: 14px; margin: 0 5px;">
                <?php echo ($promote === 'C') ? '&#10004;' : '&nbsp;'; ?>
            </span> Yes, Provided conditions are met
        </div>

    </div>

    <div style="border: 1px solid black; padding: 10px; margin: 10px 0;">
        <strong>Acknowledge</strong><br>
        Seafarer's signature: ___________________________
    </div>

    <table style="border: 1px solid black; width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding: 8px; vertical-align: top;">
                Reporting Officer<br>
                Full Name: <?php echo $reportingOfficerName; ?><br>
                Rank: <?php echo $reportingOfficerRank; ?>
            </td>
            <td style="padding: 8px; vertical-align: top;">
                Master / COO<br>
                Full Name: <?php echo $mastercoofullname; ?>
            </td>
            <td style="padding: 8px; vertical-align: top;">
                Received by CM: <?php echo $receivedByCM; ?><br>
                Date of Receipt: <?php echo $dateOfReceipt; ?>
            </td>
        </tr>
    </table>

    <div style="display: flex; width: 100%; justify-content: space-between; margin-top: 10px;">
        <div>Form OPS-015/ Rev.03/ 27-07-2018</div>
        <div>Distribution: Original - Office / Copy - Ship File</div>
    </div>

</body>

</html>