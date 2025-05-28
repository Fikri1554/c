<?php
	if(!$this->session->userdata('idUserCrewSystem'))
	{
		redirect(base_url());
	}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Crewing System">
    <meta name="author" content="andhika group">
    <title>Crewing System</title>

    <link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>image/AndhikaTransparentBkGndBlue.png" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icon-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/hover-min.css">
    <!-- <link rel="stylesheet" href="assets/css/magnific-popup.css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owl.carousel.min.css">
    <!-- <link rel="stylesheet" href="assets/css/owl.theme.default.min.css"/> -->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <!-- <link rel="stylesheet" href="assets/css/bootsnav.css"/> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
    $(document).ready(function() {

        function loadNotificationBadge() {
            $.ajax({
                url: "<?php echo base_url('dashboard/getNotificationDetails'); ?>",
                method: "GET",
                dataType: "json",
                success: function(list) {
                    const badgeCount = list.length;
                    if (badgeCount > 0) {
                        $("#idBadgeNotification").text(badgeCount).show();
                    } else {
                        $("#idBadgeNotification").hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Failed to fetch notifications:", error);
                }
            });
        }
        loadNotificationBadge();
        setInterval(loadNotificationBadge, 5000);;
        $("#notificationToggle").on("click", function() {
            $.ajax({
                url: "<?php echo base_url('dashboard/getNotificationDetails'); ?>",
                method: "GET",
                dataType: "json",
                success: function(list) {
                    let html = `
                <li style="background-color: #007bff; color: white; text-align: center; padding: 10px; font-weight: bold; border-radius: 10px 10px 0 0;">
                    Upcoming Certificate Expirations
                </li>
            `;

                    if (list.length === 0) {
                        html +=
                            `<li style="padding: 20px; text-align: center; color: #999;">No upcoming certificates.</li>`;
                    } else {
                        list.forEach(item => {
                            let certDetails = "";
                            item.certs.forEach(cert => {
                                certDetails += `
                            <div style="color: #dc3545; font-size: 13px; margin-top: 4px;">${cert.dispname}</div>
                            <div style="font-size: 12px; color: #888;">Expired: ${cert.expdate}</div>
                        `;
                            });
                            html += `
                        <li class="notification-item" data-idperson="${item.idperson}" style="padding: 12px 16px; border-bottom: 1px solid #f0f0f0; cursor: pointer;">
                            <div style="font-weight: 600; font-size : 14px;">${item.fullName}</div>
                            <div style="color: #555; font-size: 13px;">${item.nmvsl}</div>
                            ${certDetails}
                        </li>
                    `;
                        });
                    }

                    $("#notificationMenu").html(html);
                },
                error: function(xhr, status, error) {
                    $("#notificationMenu").html(
                        "<li style='padding: 20px; text-align: center; color: red;'>Error loading notifications.</li>"
                    );
                    console.error("Failed to fetch notifications:", error);
                }
            });
        });

        // Redirect saat notifikasi diklik
        $(document).on("click", ".notification-item", function() {
            const idperson = $(this).data("idperson");
            localStorage.setItem("notifSearchValue", idperson);
            localStorage.setItem("notifSearchType", "id");
            window.location.href = "<?php echo base_url('personal/getData'); ?>";
        });
    });
    </script>
</head>

<body style="background-color: <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? '#ffffff' : '#d1e9ef'; ?>; font-family: Calibri, Candara, Segoe, 
    Segoe UI,Optima, Arial, sans-serif;">
    <div class="clearfix visible-lg-block visible-md-block">
        <section class="header" style="padding-top:10px;padding-bottom:5px;">
            <div class="container">
                <div class="header-left">
                    <a class="navbar-brand" href="" style="margin: 0px;">
                        <img src="<?php echo base_url(); ?>assets/img/andhika.gif" alt="logo" style="width:50px;">
                    </a>
                </div>
                <label style="padding:5px;font-size:30px;color:#000080;"> ANDHIKA GROUP </label>
            </div>
        </section>
    </div>
    <section id="menu" style="background-color:#067780;height:50px;width:100%;">
        <div class="container">
            <div class="menubar">
                <nav class="navbar navbar-default" style="margin-bottom:0px;">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#idMenuNav" aria-expanded="false" title="Menu Crew's">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand"
                            style="color:#FFFFFF;font-size:28px;font-weight:bold;margin-top:0px;padding-top:10px;font-family: serif;">
                            Crewing System
                        </a>
                    </div>
                    <div class="collapse navbar-collapse" id="idMenuNav">
                        <ul class="nav navbar-nav navbar-right">
                            <li id="idLiHome">
                                <a href="<?php echo base_url('dashboard'); ?>">Home</a>
                            </li>
                            <li id="idLiPersonal">
                                <a href="<?php echo base_url('personal/getData'); ?>"
                                    title="Personal / Crew">Personal</a>
                            </li>
                            <li id="idLiContract">
                                <a href="<?php echo base_url('contract/getDataCrewStatus'); ?>"
                                    title="Contract Crew">Contract</a>
                            </li>
                            <li id="idLiExpCert">
                                <a href="<?php echo base_url('expiredCertificate/getData'); ?>"
                                    title="Expired Certificate">Expired Certificate</a>
                            </li>
                            <li id="idLiReport">
                                <a href="<?php echo base_url('report/'); ?>" title="Report Data">Report</a>
                            </li>
                            <li id="idLiMaster">
                                <a href="<?php echo base_url('master/'); ?>" title="Master Data">Master</a>
                            </li>
                            <li id="idLiLogOut">
                                <a href="<?php echo base_url('personal/logOut'); ?>">Logout</a>
                            </li>
                            <li class="dropdown" id="idLiNotification" style="position: relative;">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"
                                    title="Notification" id="notificationToggle"
                                    style="position: relative; display: inline-block;">
                                    <i class="fa fa-bell" aria-hidden="true" style="font-size: 20px; color: #fff;"></i>
                                    <span class="badge" id="idBadgeNotification"
                                        style="background-color: #dc3545; color: #fff; font-size: 10px; border-radius: 50%; padding: 3px 6px; position: absolute; top: 0; right: 0; display: none;">
                                        0
                                    </span>
                                </a>
                                <ul class="dropdown-menu" id="notificationMenu"
                                    style="max-height: 350px; overflow-y: auto; width: 380px; padding: 0; margin-top: 10px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.15);">
                                </ul>
                            </li>

                        </ul><!-- / ul -->
                    </div><!-- /.navbar-collapse -->
                </nav>
                <!--/nav -->
            </div>
            <!--/.menubar -->
        </div><!-- /.container -->
        <button id="myBtnToOnTop" style="display: none; position: fixed; bottom: 20px; right: 20px; z-index: 99;">
        </button>

    </section>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>


    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script type="text/javascript">
    </script>
</body>

</html>