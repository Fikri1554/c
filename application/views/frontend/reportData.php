<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <script type="text/javascript">
    $(document).ready(function() {
        $("[id^=txtDate]").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
    });

    function searchData() {
        var txtSearch = $("#txtSearch").val();

        $("#idLoading").show();
        $.post('<?php echo base_url("report/getData/search"); ?>', {
                txtSearch: txtSearch
            },
            function(data) {
                $("#idTbody").empty();
                $("#idTbody").append(data.trNya);

                $("#idLoading").hide();
            },
            "json"
        );
    }

    function pickUpData(id, lblName) {
        $("#lblPickPerson").empty();
        $("#lblPickPerson").append(lblName);
        $("#txtIdPerson").val(id);

        $("#btnPrintPrincipal").attr("disabled", false);
        $("#btnExportPrincipal").attr("disabled", false);
        $("#btnPrintTransmital").attr("disabled", false);
        $("#btnPrintTraining").attr("disabled", false);
        $("#btnPrintReport").attr("disabled", false);
    }

    function printDataPrincipal() {
        var idPerson = $("#txtIdPerson").val();
        var company = $("#slcCompanyPrins").val();

        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }

        window.open("<?php echo base_url('report/navReport');?>/" + idPerson + "/" + company, "_blank");
    }

    function transmital() {
        var idPerson = $("#txtIdPerson").val();
        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }
        window.open("<?php echo base_url('report/transmital');?>/" + idPerson + "/", "_blank");
    }

    function reloadPage() {
        window.location = "<?php echo base_url('report/');?>";
    }

    function saveDataTrainEvaluation() {
        var formData = new FormData();
        formData.append("txtIdEditTrain", $("#txtIdEditTrain").val());
        formData.append("txtIdPerson", $("#txtIdPerson").val());
        formData.append("txtemployeeName", $("#txtemployeeName").val());
        formData.append("txtdesignation", $("#txtdesignation").val());
        formData.append("txtDateOfTraining", $("#txtDateOfTraining").val());
        formData.append("txtplaceOfTraining", $("#txtplaceOfTraining").val());
        formData.append("txtsubject", $("#txtsubject").val());
        formData.append("txtDateOfEvaluation", $("#txtDateOfEvaluation").val());
        formData.append("txtevaluator", $("#txtevaluator").val());
        formData.append("suggestion", $("#suggestion").val());
        formData.append("advise", $("#advise").val());

        $("input[name='score1']:checked").each(function() {
            formData.append("score1[]", $(this).val());
        });
        $("input[name='score2']:checked").each(function() {
            formData.append("score2[]", $(this).val());
        });
        $("input[name='score3']:checked").each(function() {
            formData.append("score3[]", $(this).val());
        });
        $("input[name='score4']:checked").each(function() {
            formData.append("score4[]", $(this).val());
        });

        $("#idLoading").show();

        $.ajax({
            url: "<?php echo base_url('report/saveDataTrainEvaluation'); ?>",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                $("#idLoading").hide();

                if (response.status === "success") {
                    alert(response.message);

                    let idperson = $("#txtIdPerson").val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('report/getTrainingEvaluation'); ?>",
                        data: {
                            idperson: idperson
                        },
                        dataType: "json",
                        success: function(response) {
                            $("#idTbodyTraining").html(response.trTraining);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching updated data:", error);
                        }
                    });

                    $("#trainingForm")[0].reset();
                    $("#formContainer").css("opacity", "0");
                    setTimeout(() => {
                        $("#formContainer").hide();
                        $("#tableContainer").show();
                        setTimeout(() => {
                            $("#tableContainer").css("opacity", "1");
                        }, 50);
                    }, 300);

                    setTimeout(() => {
                        $("#btnAdd").show();
                        setTimeout(() => {
                            $("#btnAdd").css("opacity", "1");
                        }, 50);
                    }, 300);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                alert("Error: " + xhr.status + " - " + xhr.statusText);
                $("#idLoading").hide();
            }
        });
    }

    function saveDataCrewEvaluation() {
        var formData = new FormData();
        var idPerson = $("#txtIdPerson").val();
        var txtIdEditCrew = $("#txtIdEditCrew").val();

        function validateDate(id) {
            let date = $("#" + id).val();
            return date && date !== "0000-00-00" ? date : null;
        }

        formData.append("txtIdEditCrew", txtIdEditCrew || '');
        formData.append("txtIdPerson", idPerson || '');
        formData.append("txtVessel", $("#slcVesselHeader").val() || '');
        formData.append("txtSeafarerName", $("#txtSeafarerName").val() || '');
        formData.append("txtRank", $("#slcRankHeader").val() || '');
        formData.append("txtDateOfReport", validateDate("txtDateOfReport") || '');
        formData.append("txtReportingPeriodFrom", validateDate("txtReportingPeriodFrom") || '');
        formData.append("txtReportingPeriodTo", validateDate("txtReportingPeriodTo") || '');

        formData.append("reasonMidway", $("#reasonMidway").is(":checked") ? 'Y' : '');
        formData.append("reasonSigningOff", $("#reasonSigningOff").is(":checked") ? 'Y' : '');
        formData.append("reasonLeaving", $("#reasonLeaving").is(":checked") ? 'Y' : '');
        formData.append("reasonSpecialRequest", $("#reasonSpecialRequest").is(":checked") ? 'Y' : '');

        formData.append("txtMasterComments", $("#txtMasterComments").val() || '');
        formData.append("txtOfficerComments", $("#txtOfficerComments").val() || '');
        formData.append("txtPromoted", $("input[name='txtPromoted']:checked").val() || 'N');
        formData.append("txtReemploy", $("input[name='txtReemploy']:checked").val() || 'N');
        formData.append("txtfullname", $("#txtfullname").val() || '');
        formData.append("txtreceived", $("#txtreceived").val() || '');
        formData.append("slcRank", $("#slcRank").val() || '');
        formData.append("txtDateReceipt", validateDate("txtDateReceipt") || '');

        var criteriaList = {
            "Ability/Knowledge of Job": "ability",
            "Safety Consciousness": "safety",
            "Dependability & Integrity": "integrity",
            "Initiative": "initiative",
            "Conduct": "conduct",
            "Ability to get on with others": "abilityGetOn",
            "Appearance (+ uniforms)": "appearance",
            "Sobriety": "sobriety",
            "English Language": "english",
            "Leadership (Officers)": "leadership"
        };

        for (let criteriaName in criteriaList) {
            let criteriaId = criteriaList[criteriaName];
            let selectedValue = $("input[name='" + criteriaId + "']:checked").val() || '';
            formData.append(criteriaId, selectedValue);
            formData.append("txtIdentify" + criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1), $("#txtIdentify" +
                criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1)).val() || '');
        }

        $("#idLoading").show();

        $.ajax({
            url: "<?php echo base_url('report/saveDataCrewEvaluation'); ?>?_=" + new Date().getTime(),
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                $("#idLoading").hide();
                alert(response.message);
            },
            error: function(xhr, status, error) {
                $("#idLoading").hide();
                console.error("AJAX Error:", xhr.responseText);
                alert("Error saving data. Please try again.");
            }
        });
        return false;
    }

    $(document).on("click", "#btnPrintReport", function() {
        getCrewEvaluation();
    });

    function getCrewEvaluation() {
        var idPerson = $("#txtIdPerson").val();

        $("#idTbodyCrewEvaluation").html('<tr><td colspan="8">Loading...</td></tr>');

        $.ajax({
            url: "<?php echo base_url('report/getCrewEvaluation'); ?>?_=" + new Date().getTime(),
            method: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            cache: false,
            success: function(response) {
                let htmlContent = response.trCrewEvaluation ||
                    '<tr><td colspan="8">No evaluation data found</td></tr>';
                $("#idTbodyCrewEvaluation").html(htmlContent);
            },
            error: function(xhr, status, error) {
                console.error("Error:", xhr.responseText);
                $("#idTbodyCrewEvaluation").html('<tr><td colspan="8">Error loading data</td></tr>');
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        let btnAddCrewEvaluation = document.getElementById("btnAddCrewEvaluation");
        let btnCancelFormCrew = document.getElementById("btnCancelFormCrew");

        if (btnAddCrewEvaluation) {
            btnAddCrewEvaluation.addEventListener("click", function() {
                let tableCrew = document.getElementById("tableContainerCrew");
                let formCrew = document.getElementById("formContainerCrew");

                document.getElementById("crewForm").reset();

                btnAddCrewEvaluation.style.opacity = "0";
                setTimeout(() => {
                    btnAddCrewEvaluation.style.display = "none";
                }, 300);

                tableCrew.style.opacity = "0";
                setTimeout(() => {
                    tableCrew.style.display = "none";
                    formCrew.style.display = "block";
                    setTimeout(() => {
                        formCrew.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }

        if (btnCancelFormCrew) {
            btnCancelFormCrew.addEventListener("click", function() {
                let tableCrew = document.getElementById("tableContainerCrew");
                let formCrew = document.getElementById("formContainerCrew");

                formCrew.style.opacity = "0";
                setTimeout(() => {
                    formCrew.style.display = "none";
                    tableCrew.style.display = "block";
                    setTimeout(() => {
                        tableCrew.style.opacity = "1";
                    }, 50);
                }, 300);

                setTimeout(() => {
                    btnAddCrewEvaluation.style.display = "block";
                    setTimeout(() => {
                        btnAddCrewEvaluation.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        let btnAdd = document.getElementById("btnAdd");
        let btnCancel = document.getElementById("btnCancel");

        if (btnAdd) {
            btnAdd.addEventListener("click", function() {
                let table = document.getElementById("tableContainer");
                let form = document.getElementById("formContainer");

                document.getElementById("trainingForm").reset();

                btnAdd.style.opacity = "0";
                setTimeout(() => {
                    btnAdd.style.display = "none";
                }, 300);

                table.style.opacity = "0";
                setTimeout(() => {
                    table.style.display = "none";
                    form.style.display = "block";
                    setTimeout(() => {
                        form.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }

        if (btnCancel) {
            btnCancel.addEventListener("click", function() {
                let table = document.getElementById("tableContainer");
                let form = document.getElementById("formContainer");

                form.style.opacity = "0";
                setTimeout(() => {
                    form.style.display = "none";
                    table.style.display = "block";
                    setTimeout(() => {
                        table.style.opacity = "1";
                    }, 50);
                }, 300);

                setTimeout(() => {
                    btnAdd.style.display = "block";
                    setTimeout(() => {
                        btnAdd.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }
    });

    $(document).ready(function() {
        $("#btnPrintTraining").on("click", function() {
            let idperson = $("#txtIdPerson").val();

            if (idperson) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('report/getTrainingEvaluation'); ?>",
                    data: {
                        idperson: idperson
                    },
                    dataType: "json",
                    success: function(response) {
                        $("#idTbodyTraining").html(response.trTraining);
                        $("#trainingEvaluationModal").modal("show");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            } else {
                alert("Please select an employee first.");
            }
        });
    });

    function setCheckboxValue(name, value) {
        $('input[name="' + name + '"]').each(function() {
            if ($(this).val() == value) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
    }

    function editData(id) {
        $.ajax({
            url: '<?php echo base_url('report/getDataEdit'); ?>',
            type: 'POST',
            data: {
                txtIdEditTrain: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#txtIdEditTrain').val(id);
                    $('#txtemployeeName').val(response.employeeName);
                    $('#txtdesignation').val(response.designation);
                    $('#txtDateOfTraining').val(response.dateOfTraining);
                    $('#txtplaceOfTraining').val(response.placeOfTraining);
                    $('#txtsubject').val(response.subject);
                    $('#txtDateOfEvaluation').val(response.dateOfEvaluation);
                    $('#txtevaluator').val(response.evaluatorNameDesignation);
                    $('#suggestion').val(response.training_material_suggestion);
                    $('#advise').val(response.future_training_expectation);

                    setCheckboxValue('score1', response.employee_job_understanding);
                    setCheckboxValue('score2', response.quality_productivity_skill);
                    setCheckboxValue('score3', response.initiative_and_ideas);
                    setCheckboxValue('score4', response.general_performance);

                    let table = document.getElementById("tableContainer");
                    let form = document.getElementById("formContainer");
                    let btnAdd = document.getElementById("btnAdd");

                    table.style.opacity = "0";
                    setTimeout(() => {
                        table.style.display = "none";
                        form.style.display = "block";
                        setTimeout(() => {
                            form.style.opacity = "1";
                        }, 50);
                    }, 300);

                    setTimeout(() => {
                        btnAdd.style.opacity = "0";
                        setTimeout(() => {
                            btnAdd.style.display = "none";
                        }, 50);
                    }, 300);
                    $("#btnAdd").hide();
                    $("#tableContainer").hide();
                } else {
                    alert("Gagal mengambil data.");
                }
            },
            error: function() {
                alert("Terjadi kesalahan saat mengambil data.");
            }
        });
    }

    function editDataCrewEvaluation(id) {
        $.ajax({
            url: '<?php echo base_url('report/getDataEditCrewEvaluation'); ?>',
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#txtIdEditCrew').val(id);
                    $('#txtIdPerson').val(response.report.idperson);

                    console.log('idperson:', response.report.idperson);
                    console.log('txtIdPerson value:', $('#txtIdPerson').val());

                    $('#slcVesselHeader').val(response.report.vessel);
                    $('#txtSeafarerName').val(response.report.seafarer_name);
                    $('#slcRankHeader').val(response.report.rank);
                    $('#txtDateOfReport').val(response.report.date_of_report);
                    $('#txtReportingPeriodFrom').val(response.report.reporting_period_from);
                    $('#txtReportingPeriodTo').val(response.report.reporting_period_to);

                    $('#reasonMidway').prop('checked', response.report.reason_midway_contract === 'Y');
                    $('#reasonSigningOff').prop('checked', response.report.reason_signing_off === 'Y');
                    $('#reasonLeaving').prop('checked', response.report.reason_leaving_vessel === 'Y');
                    $('#reasonSpecialRequest').prop('checked', response.report.reason_special_request ===
                        'Y');

                    $('#txtMasterComments').val(response.report.master_comments);
                    $('#txtOfficerComments').val(response.report.reporting_officer_comments);

                    $(`input[name="txtPromoted"][value="${response.report.promote}"]`).prop('checked',
                        true);
                    $(`input[name="txtReemploy"][value="${response.report.re_employ}"]`).prop('checked',
                        true);

                    $('#txtfullname').val(response.report.reporting_officer_name);
                    $('#txtreceived').val(response.report.received_by_cm);
                    $('#slcRank').val(response.report.reporting_officer_rank);
                    $('#txtDateReceipt').val(response.report.date_of_receipt);

                    const criteriaMapping = {
                        'Ability/Knowledge of Job': 'ability',
                        'Safety Consciousness': 'safety',
                        'Dependability & Integrity': 'integrity',
                        'Initiative': 'initiative',
                        'Conduct': 'conduct',
                        'Ability to get on with others': 'abilityGetOn',
                        'Appearance (+ uniforms)': 'appearance',
                        'Sobriety': 'sobriety',
                        'English Language': 'english',
                        'Leadership (Officers)': 'leadership'
                    };

                    Object.entries(criteriaMapping).forEach(([name, key]) => {
                        const criteria = response.criteria[name] || {};
                        const value = criteria.excellent === 'Y' ? 4 :
                            criteria.good === 'Y' ? 3 :
                            criteria.fair === 'Y' ? 2 :
                            criteria.poor === 'Y' ? 1 : '';

                        $(`input[name="${key}"][value="${value}"]`).prop('checked', true);

                        $(`#txtIdentify${key.charAt(0).toUpperCase() + key.slice(1)}`).val(criteria
                            .identify || '');
                    });

                    toggleFormCrew(true);

                } else {
                    alert("Failed to load data: " + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr) {
                alert("Error loading data. Status: " + xhr.status);
            }
        });
    }

    function toggleFormCrew(showForm) {
        if (showForm) {
            $("#btnAddCrewEvaluation").hide();
            $("#formContainerCrew").css("display", "block");
            $("#tableContainerCrew").css("opacity", "0");
            setTimeout(() => {
                $("#tableContainerCrew").hide();
                $("#formContainerCrew").css("opacity", "1");
            }, 50);
        } else {
            $("#formContainerCrew").css("opacity", "0");
            setTimeout(() => {
                $("#formContainerCrew").hide();
                $("#tableContainerCrew").show();
                $("#btnAddCrewEvaluation").show();
                setTimeout(() => {
                    $("#tableContainerCrew").css("opacity", "1");
                }, 50);
            }, 300);
        }
    }

    function updateRowNumbers() {
        $("#idTbodyTraining tr").each(function(index) {
            $(this).find("td:first").text(index + 1);
        });
    }

    function deleteData(id, idPerson) {
        if (confirm("Delete data...??")) {
            $("#idLoading").show();

            $.post('<?php echo base_url("report/delData"); ?>/', {
                id: id,
                idPerson: idPerson
            }, function(response) {
                $("#idLoading").hide();
                if (response.status === "Success") {
                    $(`tr[data-id="${id}"]`).remove();
                    updateRowNumbers();
                    alert("Data berhasil dihapus.");
                } else {
                    alert("Gagal menghapus data: " + response.message);
                }
            }, "json").fail(function(xhr) {
                $("#idLoading").hide();
                alert("Error: " + xhr.statusText);
            });
        }
    }

    function deleteDataCrewEvaluation(id, idPerson) {
        if (confirm("Delete this evaluation?")) {
            $.ajax({
                url: "<?php echo base_url('report/delDataCrewEvaluation'); ?>",
                method: "POST",
                data: {
                    id: id,
                    idPerson: idPerson
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "Success") {
                        $("tr[data-id='" + id + "']").remove();
                        $("#idTbodyCrewEvaluation tr").each(function(index) {
                            $(this).find("td:first").text(index + 1);
                        });
                    }
                    alert(response.message || "Deleted successfully");
                },
                error: function(xhr) {
                    alert("Error deleting data");
                    console.error(xhr.responseText);
                }
            });
        }
    }


    function ViewPrint(id) {
        var idPerson = $("#txtIdPerson").val();
        if (idPerson === "") {
            alert("Person Empty..!!!");
            return false;
        }
        window.open("<?php echo base_url('report/printTrainEvaluation'); ?>/" + id + "/" + idPerson + "/", "_blank");
    }

    function ViewPrintCrewEvaluation(reportId, idPerson) {
        window.open("<?php echo base_url('report/printCrewEvaluation'); ?>/" + reportId + "/" + idPerson + "/",
            "_blank");
    }
    </script>
</head>

<body>
    <div class="container-fluid" style="background-color:#D4D4D4;min-height:500px;">
        <div class="form-panel" style="margin-top:5px;padding-bottom:15px;" id="idDataTable">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <legend style="text-align:right;margin-bottom:5px;">
                                <img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>"
                                    style="margin-right:10px;display:none;">
                                <b><i>:: Report Data ::</i></b>
                            </legend>
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-5 col-xs-12">
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtSearch"
                                        oninput="searchData();" placeholder="Crew Name..">
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <button class="btn btn-success btn-sm btn-block" title="Refresh"
                                        onclick="reloadPage();"><i class="fa fa-refresh"></i> Refresh</button>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;height:510px;overflow: auto;" id="divIdDataTable">
                                <div class="col-md-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                                            style="background-color:#D7EAEC;width:100%;">
                                            <thead>
                                                <tr style="background-color:#067780;color:#FFF;height:30px;">
                                                    <th style="vertical-align:middle;width:10%;text-align:center;">No
                                                    </th>
                                                    <th style="vertical-align:middle;width:80%;text-align:center;">Crew
                                                    </th>
                                                    <th style="vertical-align:middle;width:10%;text-align:center;">#
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="idTbody">
                                                <?php echo $trNya; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 col-xs-12">
                            <input type="hidden" id="txtIdPerson" value="">
                            <legend>
                                <div class="row">
                                    <div class="col-md-8 col-xs-12">
                                        <span>Principal</span>
                                        <span id="lblPickPerson" style="float:right;color:blue;"></span>
                                    </div>
                                </div>
                            </legend>
                            <div class="row">
                                <div class="col-md-7 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-12">
                                            <label for="slcCompanyPrins">Company</label>
                                            <span style="float:right;"><b>:</b></span>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <select class="form-control input-sm" id="slcCompanyPrins">
                                                <?php echo $optCompany; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-5 col-xs-12">
                                            <button class="btn btn-primary btn-sm btn-block" title="Cetak"
                                                onclick="printDataPrincipal();" id="btnPrintPrincipal"
                                                style="margin-top: 10px;">
                                                <i class="fa fa-print"></i> Print
                                            </button>
                                        </div>
                                        <div class="col-md-5 col-xs-12">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-4">
                                    <button class="btn btn-success btn-sm btn-block" title="Cetak"
                                        onclick="transmital();" id="btnPrintTransmital" disabled="disabled">
                                        <i class="fa fa-print"></i> Transmital
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-danger btn-sm btn-block" title="Cetak" id="btnPrintTraining"
                                        disabled="disabled" data-toggle="modal" data-target="#trainingEvaluationModal">
                                        <i class="fa fa-print"></i> Training Evaluation
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak" id="btnPrintReport"
                                        disabled="disabled" data-toggle="modal" data-target="#crewEvaluationModal">
                                        <i class=" fa fa-print"></i> Report Evaluation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<div class="modal fade" id="crewEvaluationModal" tabindex="-1" aria-labelledby="modalTitle">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary mb-3" id="btnAddCrewEvaluation">
                    <i class='fa fa-plus-circle'></i> Add Data
                </button>
                <div id="formContainerCrew"
                    style="display: none; margin-top: 10px; opacity: 0; transition: opacity 0.3s ease-in-out;">
                    <form id="crewForm" onsubmit="return saveDataCrewEvaluation(event);">
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="slcVesselHeader" style="font-size:12px;">Vessel :</label>
                                <select class="form-control input-sm" id="slcVesselHeader">
                                    <?php echo $optVessel; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Seafarer's Name</label>
                                <input type="text" class="form-control" id="txtSeafarerName">
                            </div>
                            <div class="col-md-2">
                                <label for="slcRankHeader" style="font-size:12px;">Rank:</label>
                                <select class="form-control input-sm" id="slcRankHeader">
                                    <?php echo $optRank; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date of Report</label>
                                <input type="date" class="form-control" id="txtDateOfReport">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Reporting Period From</label>
                                <input type="date" class="form-control" id="txtReportingPeriodFrom">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To</label>
                                <input type="date" class="form-control" id="txtReportingPeriodTo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 style="font-family: calibri; margin-bottom: 10px;">Reason for the Report:</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="reasonMidway" value="Y">
                                    <label class="form-check-label" for="reasonMidway"
                                        style="margin-right: 10px;">Midway
                                        through contract</label>

                                    <input class="form-check-input" type="checkbox" id="reasonSigningOff" value="Y">
                                    <label class="form-check-label" for="reasonSigningOff"
                                        style="margin-right: 10px;">Seafarer signing off vessel</label>

                                    <input class="form-check-input" type="checkbox" id="reasonLeaving" value="Y">
                                    <label class="form-check-label" for="reasonLeaving"
                                        style="margin-right: 10px;">Reporting crew leaving vessel</label>

                                    <input class="form-check-input" type="checkbox" id="reasonSpecialRequest" value="Y">
                                    <label class="form-check-label" for="reasonSpecialRequest"
                                        style="margin-right: 10px;">Special request</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        <th>Excellent (4)</th>
                                        <th>Good (3)</th>
                                        <th>Fair (2)</th>
                                        <th>Poor (1)</th>
                                        <th>Identify Training Needs</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Ability/Knowledge of Job</td>
                                        <td><input type="radio" name="ability" value="4"></td>
                                        <td><input type="radio" name="ability" value="3"></td>
                                        <td><input type="radio" name="ability" value="2"></td>
                                        <td><input type="radio" name="ability" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyAbility">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Safety Consciousness</td>
                                        <td><input type="radio" name="safety" value="4"></td>
                                        <td><input type="radio" name="safety" value="3"></td>
                                        <td><input type="radio" name="safety" value="2"></td>
                                        <td><input type="radio" name="safety" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifySafety">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dependability & Integrity</td>
                                        <td><input type="radio" name="integrity" value="4"></td>
                                        <td><input type="radio" name="integrity" value="3"></td>
                                        <td><input type="radio" name="integrity" value="2"></td>
                                        <td><input type="radio" name="integrity" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyIntegrity">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Initiative</td>
                                        <td><input type="radio" name="initiative" value="4"></td>
                                        <td><input type="radio" name="initiative" value="3"></td>
                                        <td><input type="radio" name="initiative" value="2"></td>
                                        <td><input type="radio" name="initiative" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyInitiative">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Conduct</td>
                                        <td><input type="radio" name="conduct" value="4"></td>
                                        <td><input type="radio" name="conduct" value="3"></td>
                                        <td><input type="radio" name="conduct" value="2"></td>
                                        <td><input type="radio" name="conduct" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyConduct">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ability to get on with others</td>
                                        <td><input type="radio" name="abilityGetOn" value="4"></td>
                                        <td><input type="radio" name="abilityGetOn" value="3"></td>
                                        <td><input type="radio" name="abilityGetOn" value="2"></td>
                                        <td><input type="radio" name="abilityGetOn" value="1"></td>
                                        <td><input type="text" class="form-control input-sm"
                                                id="txtIdentifyAbilityGetOn">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Appearance (+ uniforms)</td>
                                        <td><input type="radio" name="appearance" value="4"></td>
                                        <td><input type="radio" name="appearance" value="3"></td>
                                        <td><input type="radio" name="appearance" value="2"></td>
                                        <td><input type="radio" name="appearance" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyAppearance">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sobriety</td>
                                        <td><input type="radio" name="sobriety" value="4"></td>
                                        <td><input type="radio" name="sobriety" value="3"></td>
                                        <td><input type="radio" name="sobriety" value="2"></td>
                                        <td><input type="radio" name="sobriety" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifySobriety">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>English Language</td>
                                        <td><input type="radio" name="english" value="4"></td>
                                        <td><input type="radio" name="english" value="3"></td>
                                        <td><input type="radio" name="english" value="2"></td>
                                        <td><input type="radio" name="english" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyEnglish">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Leadership (Officers)</td>
                                        <td><input type="radio" name="leadership" value="4"></td>
                                        <td><input type="radio" name="leadership" value="3"></td>
                                        <td><input type="radio" name="leadership" value="2"></td>
                                        <td><input type="radio" name="leadership" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyLeadership">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label>&bullet; General Comments highlighting strengths / weaknesses:</label>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Master Comments</label>
                                <textarea class="form-control" name="comments_master" rows="6"
                                    placeholder="Master's comments" id="txtMasterComments"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Officer Comments</label>
                                <textarea class="form-control" name="comments_officer" rows="6"
                                    placeholder="Reporting Officer's comments" id="txtOfficerComments"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Re-employ</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="txtReemploy" value="Y">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="txtReemploy" value="N">
                                    <label class="form-check-label">No</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Promote</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="txtPromoted" value="Y">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="txtPromoted" value="N">
                                    <label class="form-check-label">No</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="txtPromoted" value="Conditional">
                                    <label class="form-check-label">Yes, provided the following conditions are
                                        met</label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <label>&bullet; Reporting Officer:</label>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtfullname" style="font-size:12px;">Fullname :</label>
                                <input type="text" class="form-control input-sm" id="txtfullname">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtreceived" style="font-size:12px;">Received by CM :</label>
                                <input type="text" class="form-control input-sm" id="txtreceived">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtmastercoofullname" style="font-size:12px;">Master / COO Full
                                    Name:</label>
                                <input type="text" class="form-control input-sm" id="txtmastercoofullname">
                            </div>
                            <div class="col-md-2">
                                <label for="slcRank" style="font-size:12px;">Rank:</label>
                                <select class="form-control input-sm" id="slcRank">
                                    <?php echo $optRank; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date of Receipt</label>
                                <input type="date" class="form-control" id="txtDateReceipt">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <input type="hidden" id="txtIdPerson" value="">
                            <input type="hidden" id="txtIdEditCrew" value="">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success btn-block btn-xs"
                                    data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-block btn-xs">Submit</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger btn-block btn-xs"
                                    id="btnCancelFormCrew">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" id="tableContainerCrew" style="margin-top: 10px;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr style="background-color:#067780; color:#FFF;">
                                <th style="text-align:center; width: 40px;" rowspan="2">No</th>
                                <th style="text-align:center; width: 100px;" rowspan="2">Vessel</th>
                                <th style="text-align:left; width: 100px;" rowspan="2">Seafarer's Name</th>
                                <th style="text-align:center; width: 100px;" rowspan="2">Rank</th>
                                <th style="text-align:center; width: 100px;" rowspan="2">Date of Period</th>
                                <th style="text-align:center;" colspan="2">Reporting Period</th>
                                <th style="text-align:center; width: 80px;" rowspan="2">Action</th>
                            </tr>
                            <tr style="background-color:#067780; color:#FFF;">
                                <th style="text-align:center; width: 80px; border-top: 1px solid white;">From</th>
                                <th style="text-align:center; width: 80px; border-top: 1px solid white;">To</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyCrewEvaluation">
                            <?php echo isset($trCrewEvaluation) ? $trCrewEvaluation : ''; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="trainingEvaluationModal" tabindex="-1" aria-labelledby="modalTitle">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Training Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary" id="btnAdd"><i class='fa fa-plus-circle'></i> Add
                    Data</button>

                <div id="formContainer"
                    style="display: none; margin-top: 10px; opacity: 0; transition: opacity 0.3s ease-in-out;">
                    <form id="trainingForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txtemployeeName">Employee Name</label>
                                    <input type="text" class="form-control" id="txtemployeeName" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtdesignation">Designation</label>
                                    <input type="text" class="form-control" id="txtdesignation" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtDateOfTraining">Date Of Training</label>
                                    <input type="date" class="form-control" id="txtDateOfTraining" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtplaceOfTraining">Place Of Training</label>
                                    <input type="text" class="form-control" id="txtplaceOfTraining" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtsubject">Subject</label>
                                    <input type="text" class="form-control" id="txtsubject" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtDateOfEvaluation">Date Of Evaluation</label>
                                    <input type="date" class="form-control" id="txtDateOfEvaluation" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtevaluator">Evaluator Name & Designation</label>
                                    <input type="text" class="form-control" id="txtevaluator" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employee Understanding with the job after training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Improvement for employee with Quality/Productivity and skill after
                                        training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="di    splay: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Improvement for employee in initiations and idea after training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>General performance about this employee after training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Suggestion for material and style to improve employee's job
                                        performance:</label>
                                    <textarea class="form-control" id="suggestion"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Advise and expectation in the next training program:</label>
                                    <textarea class="form-control" id="advise"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <input type="hidden" id="txtIdEditTrain" value="">
                            <input type="hidden" id="txtIdPerson" value="">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success btn-block btn-xs"
                                    data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary btn-block btn-xs"
                                    onclick="saveDataTrainEvaluation();">Submit</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger btn-block btn-xs"
                                    id="btnCancel">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" id="tableContainer" style="margin-top: 10px;">
                    <table class="table table-bordered table-striped" id="tblTrainEvaluation">
                        <thead>
                            <tr style="background-color:#067780; color:#FFF; height:35px;">
                                <th style="text-align:center; width: 40px;">No</th>
                                <th style="text-align:left; padding-left:10px;">Employee Name</th>
                                <th style="text-align:center;">Designation</th>
                                <th style="text-align:center;">Date Of Training</th>
                                <th style="text-align:center;">Place Of Training</th>
                                <th style="text-align:center;">Subject</th>
                                <th style="text-align:center;">Date Of Evaluation</th>
                                <th style="text-align:center;">Evaluator Name & Designation</th>
                                <th style="text-align:center; width:250px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyTraining">
                            <?php echo isset($trTraining) ? $trTraining : ''; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



</html>