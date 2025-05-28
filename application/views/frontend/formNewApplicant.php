<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>image/AndhikaTransparentBkGndBlue.png" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icon-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/hover-min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
    $(document).ready(function() {
        $('#otherKapalCheckbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('#inputOtherKapal').show();
            } else {
                $('#inputOtherKapal').hide();
            }
        });

        $('#otherCrewCheckbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('#inputOtherCrew').show();
            } else {
                $('#inputOtherCrew').hide();
            }
        });
    });

    function saveNewApplicant() {
        const formData = new FormData();

        const email = $("input[name='txtemail']").val();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('Format email tidak valid');
            return;
        }

        formData.append("txtemail", email);
        formData.append("txtnama", $("input[name='txtnama']").val());
        formData.append("txttempat_lahir", $("input[name='txttempat_lahir']").val());
        formData.append("txttanggal_lahir", $("input[name='txttanggal_lahir']").val());
        formData.append("txthandphone", $("input[name='txthandphone']").val());
        formData.append("position_applied", $("select[name='position_applied']").val());
        formData.append("ijazah_terakhir", $("select[name='ijazah_terakhir']").val());
        formData.append("pengalaman_terakhir", $("select[name='pengalaman_terakhir']").val());
        formData.append("last_salary", $("input[name='last_salary']").val());
        formData.append("pernah_join", $("select[name='pernah_join']").val() || 'N');

        $("input[name='kapal[]']:checked").each(function() {
            formData.append("kapal[]", $(this).val());
        });
        const otherKapalCheckbox = $('#otherKapalCheckbox');
        const otherKapalInput = $('input[name="kapal_other"]');
        if (otherKapalCheckbox.is(':checked') && otherKapalInput.val()) {
            formData.append("kapal[]", "OTHER: " + otherKapalInput.val());
        }

        $("input[name='crew[]']:checked").each(function() {
            formData.append("crew[]", $(this).val());
        });
        const otherCrewCheckbox = $('#otherCrewCheckbox');
        const otherCrewInput = $('input[name="crew_other"]');
        if (otherCrewCheckbox.is(':checked') && otherCrewInput.val()) {
            formData.append("crew[]", "OTHER: " + otherCrewInput.val());
        }

        const cvFiles = $("input[name='cv_files[]']")[0].files;
        if (cvFiles.length === 0) {
            showError("Silakan unggah CV");
            return;
        }
        for (let i = 0; i < cvFiles.length; i++) {
            formData.append("cv_files[]", cvFiles[i]);
        }

        $.ajax({
            url: '<?php echo base_url('extendCrewEvaluation/saveNewApplicant') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                try {
                    const res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        alert('Formulir berhasil dikirim. Terima kasih!');
                        window.location.reload();
                    } else {
                        showError(res.message);
                    }
                } catch (e) {
                    showError('Respon server tidak valid');
                }
            },
            error: function(xhr) {
                showError('Error: ' + xhr.statusText);
            }
        });

    }

    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        setTimeout(() => errorDiv.style.display = 'none', 5000);
    }
    </script>
</head>

<body style="background-color: #d1e9ef; font-family: Calibri, Candara, Segoe, 
    Segoe UI,Optima, Arial, sans-serif;">
    <div class="clearfix">
        <section class="header" style="padding-top:10px;padding-bottom:5px;">
            <div class="container">
                <div class="header-left">
                    <a class="navbar-brand" style="margin: 0px;">
                        <img src="<?php echo base_url(); ?>assets/img/andhika.gif" alt="logo" style="width:50px;">
                    </a>
                </div>
                <label style="padding:5px;font-size:30px;color:#000080;">ANDHIKA GROUP</label>
            </div>
        </section>
    </div>
    <section id="menu" style="background-color:#067780; min-height: 60px; width:100%;">
        <div class="container">
            <div class="menubar">
                <nav class="navbar navbar-default" style="margin-bottom:10px;">
                    <div class="navbar-header">
                        <a class="navbar-brand"
                            style="color:#FFFFFF;font-size:20px;font-weight:bold;padding:10px 0;font-family: serif;">
                            FORM RECRUITMENT CREW
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </section>


    <div class="container my-3">
        <div class="mx-auto bg-white p-4 p-md-5 rounded-5 shadow"
            style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
            <h2 class="text-primary mb-2 fs-4">📝 FORM RECRUITMENT CREW</h2>
            <p class="text-secondary small mb-4">
                Pastikan hanya mengunggah CV terbaru yang telah diperbarui. <br><br>
                Hanya kandidat yang memenuhi syarat yang akan dihubungi.
            </p>

            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Email *</label>
                    <input type="email" name="txtemail" class="form-control" required>

                </div>
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Nama Lengkap *</label>
                    <input type="text" name="txtnama" class="form-control" required>
                </div>

                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Tempat Lahir *</label>
                    <input type="text" name="txttempat_lahir" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Tanggal Lahir *</label>
                    <input type="date" name="txttanggal_lahir" class="form-control" required>
                </div>

                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Nomor Handphone (WA) *</label>
                    <input type="tel" name="txthandphone" class="form-control" required pattern="[0-9]{10,15}"
                        placeholder="Masukkan nomor HP yang valid (10-15 digit)">
                </div>

                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Jabatan yang Dilamar *</label>
                    <select class="form-select" name="position_applied" required>
                        <?php echo $optRank; ?>
                    </select>
                </div>

            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Ijazah Terakhir *</label>
                    <select class="form-select" name="ijazah_terakhir" required>
                        <option value="">- PILIH IJAZAH -</option>
                        <option value="ANT I">ANT I</option>
                        <option value="ANT II">ANT II</option>
                        <option value="ANT III">ANT III</option>
                        <option value="ANT IV">ANT IV</option>
                        <option value="ANT V">ANT V</option>
                        <option value="ATT I">ATT I</option>
                        <option value="ATT II">ATT II</option>
                        <option value="ATT III">ATT III</option>
                        <option value="ATT IV">ATT IV</option>
                        <option value="ATT V">ATT V</option>
                        <option value="ETO">ETO</option>
                        <option value="ETR">ETR</option>
                        <option value="RATING AS ABLE SEAFARER DECK ">RATING AS ABLE SEAFARER DECK </option>
                        <option value="RATINGS FORMING PART OF NAVIGATION WATCH  ">RATINGS FORMING PART OF
                            NAVIGATION
                            WATCH</option>
                        <option value="RATING AS ABLE ENGINE">RATING AS ABLE ENGINE</option>
                        <option value="RATINGS FORMING PART OF A WATCH ENGINE ROOM">RATINGS FORMING PART OF A WATCH
                            ENGINE ROOM
                        </option>
                        <option value="BASIC SAFETY TRAINING">BASIC SAFETY TRAINING</option>
                        <option value="SIO">SIO</option>
                        <option value="WELDER CERTIFICATE">WELDER CERTIFICATE</option>
                        <option value="FOOD HANDLING">FOOD HANDLING</option>
                        <option value="SHIP COOK">SHIP COOK</option>
                    </select>
                </div>

                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Pengalaman / Jabatan Terakhir *</label>
                    <select class="form-select" name="pengalaman_terakhir" required>
                        <?php echo $optRank; ?>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top: 20px;">
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Pengalaman Berlayar di Jenis Kapal *</label>
                    <div class="row row-cols-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="BULK CARRIER">
                            <label class="form-check-label">BULK CARRIER</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="CARGO">
                            <label class="form-check-label">CARGO</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="GENERAL CARGO">
                            <label class="form-check-label">GENERAL CARGO</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="CONTAINER">
                            <label class="form-check-label">CONTAINER</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER PRODUCT">
                            <label class="form-check-label">TANKER PRODUCT</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER OIL">
                            <label class="form-check-label">TANKER OIL</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="CRUDE OIL">
                            <label class="form-check-label">CRUDE OIL</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER CHEMICAL">
                            <label class="form-check-label">TANKER CHEMICAL</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER GAS">
                            <label class="form-check-label">TANKER GAS</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="FLOATING CRANE">
                            <label class="form-check-label">FLOATING CRANE</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="TUG BOAT">
                            <label class="form-check-label">TUG BOAT</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="SUPPLY VESSEL">
                            <label class="form-check-label">SUPPLY VESSEL</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="CREW BOAT">
                            <label class="form-check-label">CREW BOAT</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="kapal[]" value="RORO/PASSENGER">
                            <label class="form-check-label">RORO/PASSENGER</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="otherKapalCheckbox" name="kapal[]"
                                value="OTHER">
                            <label class="form-check-label">OTHER</label>
                            <div class="form-group mt-2" id="inputOtherKapal" style="display: none;">
                                <input type="text" class="form-control" name="kapal_other"
                                    placeholder="Sebutkan jenis kapal lainnya">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Pernah Berlayar dengan Crew Asing? *</label>
                    <div class="row row-cols-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="JAPAN">
                            <label class="form-check-label">JAPAN</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="KOREA">
                            <label class="form-check-label">KOREA</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="CHINA">
                            <label class="form-check-label">CHINA</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="INDIA">
                            <label class="form-check-label">INDIA</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="UKRAINA">
                            <label class="form-check-label">UKRAINA</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="GREECE">
                            <label class="form-check-label">GREECE</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="FILIPINA">
                            <label class="form-check-label">FILIPINA</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="AUSTRALIA">
                            <label class="form-check-label">AUSTRALIA</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="otherCrewCheckbox" name="crew[]"
                                value="OTHER">
                            <label class="form-check-label">OTHER</label>
                            <div class="form-group mt-2" id="inputOtherCrew" style="display: none;">
                                <input type="text" class="form-control mt-1" name="crew_other"
                                    placeholder="Sebutkan negara lainnya">
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="crew[]" value="Belum Pernah">
                            <label class="form-check-label">Belum Pernah</label>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Salary terakhir yang diterima? *</label>
                    <input type="text" name="last_salary" class="form-control" placeholder="Contoh: 1500 USD" required>
                </div>

                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">Apakah sudah pernah join di kapal Andhika Group
                        sebelumnya?
                        *</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pernah_join" value="Y" required>
                        <label class="form-check-label">Ya</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="pernah_join" value="N" required>
                        <label class="form-check-label">Tidak</label>
                    </div>
                </div>
                <div class="col-md-4 col-xs-12">
                    <label class="form-label fw-semibold">CV Terbaru *</label>
                    <input type="file" name="cv_files[]" class="form-control" accept=".pdf" multiple required>
                    <small class="text-muted">Upload maksimum 5 file PDF. Maks. 10 MB per file.</small>
                </div>
            </div>
            <div id="error-message" class="alert alert-danger"
                style="display:none; position:fixed; top:20px; right:20px;"></div>
            <input type="hidden" name="txtIdNewApplicant" value="<?php echo uniqid('NewApplicant'); ?>">
            <button type="button" class="btn btn-primary mt-4 px-4 py-2 shadow-sm" onclick="saveNewApplicant()">
                🚀 Kirim Formulir
            </button>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js">
    </script>



</body>

</html>