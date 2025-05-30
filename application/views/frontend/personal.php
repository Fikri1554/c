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
            yearRange: "-70:+4"
        });
        $("#btnNew").click(function() {
            $("#idLoading").show();
            $("#idDataTable").hide();
            $("#idForm").show(300);
        });
        cekUrlProses();
    });

    function saveDataPersonal() {
        var formData = new FormData();

        var txtSearch = $("#txtSearch").val();
        var idEdit = $("#txtIdEdit").val();
        var fileUpload = $("#fileUploadPersonalData").val();
        var gender = $("input[name='rdGender']:checked").val();
        var acceptLowRank = $("input[name='rdLowerRank']:checked").val();
        var chkEmail = "";
        var chkFax = "";
        var chkMobilePhone = "";
        var chkHomePhone = "";
        var chkPost = "";
        var heightPobia = $("input[name='rdHeightPhobia']:checked").val();
        var feelClaustroPhobic = $("input[name='rdFeelClaustrophobic']:checked").val();
        var nonAktif = "";
        var blacklist = "";
        var nonCrew = "";
        var newApplicent = "";
        var fileStatementWages = $("#uploadFileOfWages").val();
        var fileInterview = $("#uploadFileInterview").val();
        var slcVesselType = $("#slcVesselType").val();
        var fileEvaluation = $("#uploadFileEvaluation").val();
        var fileStateForm = $("#uploadFileStatementForm").val();

        if ($('#chkEmail').is(":checked")) {
            chkEmail = $("#chkEmail").val();
        }
        if ($('#chkFax').is(":checked")) {
            chkFax = $("#chkFax").val();
        }
        if ($('#chkMobilePhone').is(":checked")) {
            chkMobilePhone = $("#chkMobilePhone").val();
        }
        if ($('#chkHomePhone').is(":checked")) {
            chkHomePhone = $("#chkHomePhone").val();
        }
        if ($('#chkPost').is(":checked")) {
            chkPost = $("#chkPost").val();
        }

        if ($('#chkNonAktif').is(":checked")) {
            nonAktif = $("#chkNonAktif").val();
        }
        if ($('#chkBlackList').is(":checked")) {
            blacklist = $("#chkBlackList").val();
        }
        if ($('#chkNonCrew').is(":checked")) {
            nonCrew = $("#chkNonCrew").val();
        }
        if ($('#chkNewApp').is(":checked")) {
            newApplicent = $("#chkNewApp").val();
        }

        formData.append('idEdit', idEdit);
        formData.append('fname', $("#txtFname").val());
        formData.append('mname', $("#txtMname").val());
        formData.append('lname', $("#txtLname").val());
        formData.append('nationality', $("#slcCountryNational").val());
        formData.append('countryOriginal', $("#slcCountryOrigin").val());
        formData.append('tanggalLahir', $("#txtDate_DOB").val());
        formData.append('placeBirth', $("#slcCityBirth").val());
        formData.append('maritalStatus', $("#slcMaritalStatus").val());
        formData.append('fatherName', $("#txtFatherName").val());
        formData.append('motherName', $("#txtMotherName").val());
        formData.append('sosSecNo', $("#txtSosSecNumber").val());
        formData.append('sosSecIssuingCountry', $("#slcSosSecIssuingCountry").val());
        formData.append('personalTaxNo', $("#txtTaxNumber").val());
        formData.append('personalTaxNoCountry', $("#slcTaxIssCountry").val());
        formData.append('cesScore', $("#txtCesScore").val());
        formData.append('marlinTestScore', $("#txtmarlinTest").val());
        formData.append('dateTraining', $("#txtDate_training").val());
        formData.append('evaluation', $("#txtEvaluation").val());
        formData.append('gender', gender);
        formData.append('wifeName', $("#txtWifeName").val());
        formData.append('agama', $("#slcReligion").val());
        formData.append('rankApply', $("#slcRankApply").val());
        formData.append('vesselApply', $("#slcVesselApply").val());
        formData.append('WillingAcceptLowRank', acceptLowRank);
        formData.append('dateAvailable', $("#txtDate_available").val());
        formData.append('addressPrimary', $("#txtAddressPrimary").val());
        formData.append('city', $("#slcCity").val());
        formData.append('nearestAirPort', $("#slcNearestAirport").val());
        formData.append('kodePos', $("#txtPostCode").val());
        formData.append('country', $("#slcCountry").val());
        formData.append('mobileNo', $("#txtMobileNo").val());
        formData.append('homeNo', $("#txtHomeNo").val());
        formData.append('fax', $("#txtFax").val());
        formData.append('email', $("#txtEmail").val());
        formData.append('txtBankName', $("#txtBankName").val());
        formData.append('accountNo', $("#txtAccNo").val());
        formData.append('txtAccountName', $("#txtAccountName").val());
        formData.append('golDarah', $("#slcBloodType").val());
        formData.append('eyeColor', $("#txtEyeColor").val());
        formData.append('contactEmail', chkEmail);
        formData.append('contactFax', chkFax);
        formData.append('contactMobilePhone', chkMobilePhone);
        formData.append('contactHomePhone', chkHomePhone);
        formData.append('contactPost', chkPost);
        formData.append('weight', $("#txtWeight").val());
        formData.append('height', $("#txtHeight").val());
        formData.append('shoes', $("#txtShoes").val());
        formData.append('collar', $("#txtCollar").val());
        formData.append('chest', $("#txtChest").val());
        formData.append('waist', $("#txtWaist").val());
        formData.append('insideLed', $("#txtInsLed").val());
        formData.append('cap', $("#txtCap").val());
        formData.append('clothesSize', $("#slcSizeClothes").val());
        formData.append('sweaterSize', $("#slcSizeSweater").val());
        formData.append('boilerSuitSize', $("#slcSizeBoilersuit").val());
        formData.append('heightPobia', heightPobia);
        formData.append('feelClaustroPhobic', feelClaustroPhobic);
        formData.append('anyAllergy', $("#txtAnyAllergi").val());
        formData.append('additionalRemark', $("#txtAdditionalRemark").val());
        formData.append('signPlace', $("#txtSignPlace").val());
        formData.append('signDate', $("#txtDate_sign").val());
        formData.append('nonAktif', nonAktif);
        formData.append('blacklist', blacklist);
        formData.append('nonCrew', nonCrew);
        formData.append('newApplicent', newApplicent);
        formData.append('slcVesselType', slcVesselType);


        formData.append('cekFileUpload', fileUpload);
        formData.append('fileUpload', $("#fileUploadPersonalData").prop('files')[0]);

        formData.append('nextOfKin', $("#txtNextOfKin").val());
        formData.append('cekFileWages', fileStatementWages);
        formData.append('fileStatementWages', $("#uploadFileOfWages").prop('files')[0]);
        formData.append('cekFileInterview', fileInterview);
        formData.append('fileInterview', $("#uploadFileInterview").prop('files')[0]);
        formData.append('cekFileEvaluation', fileEvaluation);
        formData.append('fileEvaluation', $("#uploadFileEvaluation").prop('files')[0]);
        formData.append('cekFileStateForm', fileStateForm);
        formData.append('fileStateForm', $("#uploadFileStatementForm").prop('files')[0]);

        $("#idLoadingForm").show();
        // $("#btnSave").attr('disabled',true);
        $.ajax("<?php echo base_url('Personal/saveDataPersonal'); ?>", {
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);

                if (txtSearch == "") {
                    reloadPage();
                } else {
                    searchData();
                }
            }
        });
    }

    function saveDataNominee() {
        var idPerson = $("#txtIdPerson").val();
        var fullName = $("#txtNomineeFamDet").val();
        var relationship = $("#slcRelationshipFamDet").val();
        var gender = $("input[name='rdGenderFamDet']:checked").val();
        var kodePos = $("#txtPostCodeFamDet").val();
        var address = $("#txtAddressFamDet").val();
        var nationality = $("#slcNationalityFamDet").val();
        var kota = $("#slcCityFamDet").val();
        var country = $("#slcCountryFamDet").val();
        var email = $("#txtEmailFamDet").val();
        var telp = $("#txtTelpFamDet").val();
        var mobile = $("#txtMobileFamDet").val();

        $("#idLoading").show();
        $.post('<?php echo base_url("personal/updateNominee"); ?>', {
                idPerson: idPerson,
                fullName: fullName,
                relationship: relationship,
                gender: gender,
                kodePos: kodePos,
                address: address,
                nationality: nationality,
                kota: kota,
                country: country,
                email: email,
                telp: telp,
                mobile: mobile
            },
            function(data) {
                alert(data);
                $("#idLoading").hide();
                navProsesCrew();
            },
            "json"
        );
    }

    function saveFamilyData() {
        var idEdit = $("#txtIdEditDataFamily").val();
        var idPerson = $("#txtIdPerson").val();
        var relationShip = $("#slcRelationshipFamily").val();
        var childGender = $("input[name='rdGenderFamilyData']:checked").val();
        var firstName = $("#txtFirstNameFamDet").val();
        var lastName = $("#txtLastNameFamDet").val();
        var dob = $("#txtDate_DOBFamDet").val();
        var passportNo = $("#txtPassportNoFamDet").val();
        var issueDate = $("#txtDate_issuedFamDet").val();
        var place = $("#txtPlaceFamDet").val();
        var dateValid = $("#txtDate_ValidUntilFamDet").val();
        var visa = $("#slcVisaFamDet").val();

        $("#idLoading").show();
        $.post('<?php echo base_url("personal/saveFamilyData"); ?>', {
                idEdit: idEdit,
                idPerson: idPerson,
                relationShip: relationShip,
                childGender: childGender,
                firstName: firstName,
                lastName: lastName,
                dob: dob,
                passportNo: passportNo,
                issueDate: issueDate,
                place: place,
                dateValid: dateValid,
                visa: visa
            },
            function(data) {
                alert(data);
                $("#idLoading").hide();
                navProsesCrew();
            },
            "json"
        );
    }

    function searchData() {
        var typeSearch = $("#slcSearch").val();
        var txtSearch = $("#txtSearch").val();
        var typeDisplay = $("#txtHiddenTypeData").val();
        var urlNya = "";

        if (txtSearch == "") {
            alert("Text Search Empty..!!");
            return false;
        }
        $("#idLoading").show();

        if (typeDisplay == "all") {
            urlNya = '<?php echo base_url("personal/getData"); ?>/search/';
        } else if (typeDisplay == "onboard") {
            urlNya = '<?php echo base_url("personal/getDataOnboard"); ?>/search/';
        } else if (typeDisplay == "onleave") {
            urlNya = '<?php echo base_url("personal/getDataOnLeave"); ?>/search/';
        } else if (typeDisplay == "nonaktif") {
            urlNya = '<?php echo base_url("personal/getDataNonAktif"); ?>/search/';
        } else if (typeDisplay == "notforemp") {
            urlNya = '<?php echo base_url("personal/getDataNotForEmp"); ?>/search/';
        } else if (typeDisplay == "newapplicent") {
            urlNya = '<?php echo base_url("personal/getDataNewApplicent"); ?>/search/';
        }

        $.post(urlNya, {
                typeSearch: typeSearch,
                txtSearch: txtSearch,
                typeDisplay: typeDisplay
            },
            function(data) {
                $("#idTbody").empty();
                $("#idTbody").append(data.trNya);
                $("#idPage").empty();
                $("#idForm").hide();
                $("#idDataTable").show();
                $("#idLoading").hide();
            },
            "json"
        );
    }

    $(document).ready(function() {
        const searchValue = localStorage.getItem("notifSearchValue");
        const searchType = localStorage.getItem("notifSearchType") || "id";

        if (searchValue) {
            $('#txtSearch').val(searchValue);
            $('#slcSearch').val(searchType);
            searchData();

            localStorage.removeItem("notifSearchValue");
            localStorage.removeItem("notifSearchType");
        }
    });



    function getDataProses(id) {
        $("#idLoading").show();
        $.post('<?php echo base_url("personal/getDataProses"); ?>', {
                id: id,
                type: "editProses"
            },
            function(data) {
                $("#idViewPic").empty();
                $("#idViewPic").append(data.imgPic);

                $("#idDivViewWages").empty();
                $("#idDivViewWages").append(data.imgWages);

                $("#idDivViewInterview").empty();
                $("#idDivViewInterview").append(data.imgIntv);

                $("#idDivViewEvaluation").empty();
                $("#idDivViewEvaluation").append(data.imgEvaluation);

                $("#idDivViewStatementForm").empty();
                $("#idDivViewStatementForm").append(data.imgStatementForm);

                $("#slcDataForm").css('display', '');
                $("#slcDataForm").val("personal_data");
                $("#txtIdPerson").val(id);
                $("#txtIdEdit").val(id);
                $("#teksJudulName").text(data.fullName);

                $.each(data['rslVal'], function(key, val) {
                    $("#" + key).val(val);
                });

                $.each(data['rslCheck'], function(key, val) {
                    $("#" + key).attr("checked", val);
                });

                $("#idDataTable").hide();
                $("#idForm").show(300);
                $("#idFormBody").css("display", "");
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function navProsesCrew() {
        var slcNav = $("#slcDataForm").val();
        var idPerson = $("#txtIdPerson").val();

        $("#idFormBodyOther").empty();
        $("#idFormBodyOther").css("display", "");

        if (slcNav == "personal_data") {
            $("#teksJudulPage").html("<b><i>:: Personal Data ::</i></b>");
            $("#idFormBody").css("display", "");
        } else if (slcNav == "personal_id") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Personal Id ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("personal/getPersonalId"); ?>' + '/' + idPerson,
                function() {
                    $("#idLoadingForm").hide();
                    $("[id^=txtDate]").datepicker({
                        dateFormat: 'yy-mm-dd',
                        showButtonPanel: true,
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-70:+4"
                    });
                });
        } else if (slcNav == "certificate") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: All Certificate / Document ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("personal/getPersonalDataAllCertificate"); ?>' + '/' +
                idPerson,
                function() {

                    $("#txtIdPersonAllCertificate").val(idPerson);
                    $("#idLoadingForm").hide();

                    $("[id^=txtDate]").datepicker({
                        dateFormat: 'yy-mm-dd',
                        showButtonPanel: true,
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-70:+4"
                    });
                });
        } else if (slcNav == "compliance") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Compliance Certificates ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("compliance/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
            });
        } else if (slcNav == "sea") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Sea Experience ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("seaExperiance/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
                $("[id^=txtDate]").datepicker({
                    dateFormat: 'yy-mm-dd',
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-70:+4"
                });
            });
        } else if (slcNav == "general") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: General ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("general/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
            });
        } else if (slcNav == "language") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Language Knowledge ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("language/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
            });
        } else if (slcNav == "education") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Educational Attainment ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("education/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
            });
        } else if (slcNav == "contract") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Contract ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("contract/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
                $("[id^=txtDate]").datepicker({
                    dateFormat: 'yy-mm-dd',
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-70:+4"
                });
            });
        } else if (slcNav == "vaccine") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Vaccination ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("vaccine/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
            });
        } else if (slcNav == "other") {
            $("#idFormBody").css("display", "none");
            $("#idLoadingForm").show();
            $("#teksJudulPage").html("<b><i>:: Others ::</i></b>");

            $("#idFormBodyOther").load('<?php echo base_url("others/getData"); ?>' + '/' + idPerson, function() {
                $("#idLoadingForm").hide();
                $("[id^=txtDate]").datepicker({
                    dateFormat: 'yy-mm-dd',
                    showButtonPanel: true,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: "-70:+4"
                });
            });
        } else {
            $("#idFormBody").css("display", "none");
            $("#idLoading").show();

            $.post('<?php echo base_url("personal/navProsesCrew"); ?>', {
                    slcNav: slcNav,
                    idPerson: idPerson
                },
                function(data) {
                    $("#teksJudulPage").html(data.labelName);
                    $("#idFormBodyOther").append(data.divForm);

                    $("[id^=txtDate]").datepicker({
                        dateFormat: 'yy-mm-dd',
                        showButtonPanel: true,
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-70:+4"
                    });
                },
                "json"
            );
        }
    }

    function actBtnAdd(actShow, actHide) {
        $("#" + actHide).hide();
        $("#" + actShow).show(200);
    }

    function updateNominee(id) {
        $("#idLoading").show();
        $.post('<?php echo base_url("personal/getDataProses"); ?>', {
                id: id,
                type: "editNominee"
            },
            function(data) {
                $("#txtNomineeFamDet").val(data.fullname);
                $("#slcRelationshipFamDet").val(data.relationship);

                if (data.gender == "Male") {
                    $("#rdGenderMaleFamDet").attr('checked', 'checked');
                } else {
                    $("#rdGenderFemaleFamDet").attr('checked', 'checked');
                }

                $("#txtPostCodeFamDet").val(data.kodePos);
                $("#txtAddressFamDet").val(data.address);
                $("#slcNationalityFamDet").val(data.nationality);
                $("#slcCityFamDet").val(data.city);
                $("#slcCountryFamDet").val(data.country);
                $("#txtEmailFamDet").val(data.email);
                $("#txtTelpFamDet").val(data.telp);
                $("#txtMobileFamDet").val(data.mobile);

                $("#idFormNomineeFamDet").css("display", "");
                $("#idDatatableNomineeFamDet").css("display", "none");
                $("#idTableFamilyData").css("display", "none");
            },
            "json"
        );
    }

    function updateFamilyData(id, idPerson) {
        $("#idLoading").show();
        $.post('<?php echo base_url("personal/getDataProses"); ?>', {
                id: id,
                idPerson: idPerson,
                type: "editDataFamily"
            },
            function(data) {
                $("#txtIdEditDataFamily").val(id);
                $("#slcRelationshipFamily").val(data.relationship);

                if (data.gender == "1") {
                    $("#rdChildMaleFamDet").attr('checked', 'checked');
                } else {
                    $("#rdChildFemaleFamDet").attr('checked', 'checked');
                }

                $("#txtFirstNameFamDet").val(data.fmfname);
                $("#txtLastNameFamDet").val(data.fmlname);
                $("#txtDate_DOBFamDet").val(data.fmdob);
                $("#txtPassportNoFamDet").val(data.fmpassno);
                $("#txtDate_issuedFamDet").val(data.fmissdt);
                $("#txtPlaceFamDet").val(data.fmplc);
                $("#txtDate_ValidUntilFamDet").val(data.fmexpdt);
                $("#slcVisaFamDet").val(data.fmvisa);

                $("#idFormFamilyDataFamDet").css("display", "");
                $("#idDatatableFamilyDataFamDet").css("display", "none");
                $("#idTableNominee").css("display", "none");
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function addNewFamilyData() {
        $("#idLoading").show();
        $("#idTableNominee").css("display", "none");
        $("#idDatatableFamilyDataFamDet").css("display", "none");
        $("#idFormFamilyDataFamDet").css("display", "");
    }

    function delDataPersonal(idPerson) {
        var cfm = confirm("Delete data...??");
        if (cfm) {
            $("#idLoading").show();
            $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                    id: idPerson,
                    type: "deleteDataPersonal"
                },
                function(data) {
                    alert(data);
                    reloadPage();
                },
                "json"
            );
        }
    }

    function delData(id, idPerson) {
        var cfm = confirm("Delete data...??");
        if (cfm) {
            $("#idLoading").show();
            $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                    id: id,
                    idPerson: idPerson,
                    type: "deleteFamilyData"
                },
                function(data) {
                    alert(data);
                    navProsesCrew();
                },
                "json"
            );
        }
    }

    function delFileWages(id, fileName) {
        var cfm = confirm("Delete data...??");
        if (cfm) {
            $("#idLoading").show();
            $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                    idPerson: id,
                    fileName: fileName,
                    type: "deleteStatementWages"
                },
                function(data) {
                    alert(data);
                    $("#idDivViewWages").empty();
                },
                "json"
            );
        }
    }

    function delFileIntvw(id, fileName) {
        var cfm = confirm("Delete data...??");
        if (cfm) {
            $("#idLoading").show();
            $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                    idPerson: id,
                    fileName: fileName,
                    type: "deleteFileInterview"
                },
                function(data) {
                    alert(data);
                    $("#idDivViewInterview").empty();
                },
                "json"
            );
        }
    }

    function delFileEvaluation(id, fileName) {
        var cfm = confirm("Delete data...??");
        if (cfm) {
            $("#idLoading").show();
            $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                    idPerson: id,
                    fileName: fileName,
                    type: "deleteFileEvaluation"
                },
                function(data) {
                    alert(data);
                    $("#idDivViewEvaluation").empty();
                },
                "json"
            );
        }
    }

    function delFileStatementForm(id, fileName) {
        var cfm = confirm("Delete data...??");
        if (cfm) {
            $("#idLoading").show();
            $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                    idPerson: id,
                    fileName: fileName,
                    type: "deleteFileSTatementForm"
                },
                function(data) {
                    alert(data);
                    $("#idDivViewStatementForm").empty();
                },
                "json"
            );
        }
    }

    function cekUrlProses() {
        var urlNya = window.location.pathname;
        var tempData = urlNya.split('searchByContract');

        if (tempData.length == 2) {
            tempData = tempData[1].replace("/", "");

            getDataProses(tempData);
        }
    }

    function navButtonHead(type) {
        window.location = "<?php echo base_url('personal/navButtonHead');?>" + "/" + type;
    }

    function backButton() {
        $("#idViewPic").empty();
        $("#teksJudulName").empty();
        $("#idDivViewEvaluation").empty();

        var elTextHide = document.querySelectorAll('#idForm input[type="hidden"]');
        var elInput = document.querySelectorAll(
            '#idForm input[type="text"], #idForm input[type="number"], #idForm input[type="password"]'
        );
        var elSlc = document.querySelectorAll('#idForm select');
        var elUplFile = document.querySelectorAll('#idForm input[type="file"]');
        var elTextArea = document.querySelectorAll('#idForm textarea');
        var elChecked = document.querySelectorAll('#idForm input[type="checkbox"]');
        var elRadio = document.querySelectorAll('#idForm input[type="radio"]');

        elTextHide.forEach(element => element.value = '');
        elInput.forEach(element => element.value = '');
        elSlc.forEach(element => element.value = '');
        elUplFile.forEach(element => element.value = '');
        elTextArea.forEach(element => element.value = '');
        elChecked.forEach(element => element.checked = false);
        elRadio.forEach(element => element.checked = false);

        $("#idFormBodyOther").empty().css("display", "none");
        $("#idForm").css("display", "none");
        $("#idDataTable").show(200);
        $("#idLoading").hide();
    }


    function reloadPage() {
        window.location = "<?php echo base_url('personal/getData');?>";
    }

    function pickUpData(applicantId) {
        $.ajax({
            url: '<?php echo base_url("personal/getApplicantData") ?>',
            type: 'POST',
            data: {
                id: applicantId
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                $('#txtEmail').val(response.email);
                $('#txtFname').val(response.firstName);
                $('#txtMname').val(response.middleName);
                $('#txtLname').val(response.lastName);
            },
            error: function(xhr, status, error) {
                alert('Terjadi kesalahan: ' + error);
            }
        });
    }
    </script>
</head>

<body>
    <!-- <section style="padding: 0px 45px;"> -->
    <div class="container-fluid" style="background-color:#D4D4D4;">
        <div class="form-panel" style="padding-top:5px;display:;" id="idDataTable">
            <legend style="text-align:right;">
                <img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>"
                    style="margin-right:10px;display:none;">
                <b><i>:: Personal / Crew ::</i></b>
            </legend>
            <div class="row">
                <div class="col-md-1 col-xs-12">
                    <input type="hidden" id="txtHiddenTypeData" value="<?php echo $typeDisplay; ?>">
                    <button id="btnNew" class="btn btn-primary btn-sm btn-block" title="New Crew"><i
                            class="fa fa-user-plus"></i> New</button>
                </div>
                <div class="col-md-2 col-xs-12">
                    <select class="form-control input-sm" id="slcSearch">
                        <option value="id">Id</option>
                        <option value="name">Name</option>
                        <option value="age">Age</option>
                        <option value="rank">Rank</option>
                        <option value="applied">Applied Rank</option>
                        <option value="vessel">Vessel</option>
                    </select>
                </div>
                <div class="col-md-2 col-xs-12">
                    <input type="text" id="txtSearch" value="" class="form-control input-sm" placeholder="Text">
                </div>
                <div class="col-md-1 col-xs-12">
                    <button class="btn btn-info btn-sm btn-block" onclick="searchData();"><i class="fa fa-search"></i>
                        Search</button>
                </div>
                <div class="col-md-1 col-xs-12">
                    <button class="btn btn-success btn-sm btn-block" onclick="reloadPage();"><i
                            class="fa fa-refresh"></i> Refresh</button>
                </div>
                <div class="col-md-5 col-xs-12">
                    <div class="btn-group btn-group-justified" role="group" aria-label="Status Crew">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-pupple btn-xs" title="New Applicent"
                                style="font-weight:bold;" onclick="navButtonHead('newapplicent');">New
                                Applicent</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-xs" title="On Board" style="font-weight:bold;"
                                onclick="navButtonHead('onboard');">On Board</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success btn-xs" title="On Leave"
                                style="font-weight:bold;" onclick="navButtonHead('onleave');">On Leave</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning btn-xs" title="Non Aktif"
                                style="font-weight:bold;" onclick="navButtonHead('nonaktif');">Non Aktif</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger btn-xs" title="Not for Employeed"
                                style="font-weight:bold;" onclick="navButtonHead('notofemp');">Not for Emp.</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:5px;">
                <div class="col-md-12 col-xs-12">
                    <div class="table-responsive">
                        <table
                            class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                            <thead>
                                <tr style="background-color:#067780;color:#FFF;height:30px;">
                                    <th style="vertical-align:middle;width:5%;text-align:center;">No</th>
                                    <th style="vertical-align:middle;width:22%;text-align:center;">Full Name</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Rank<br>Applied For
                                    </th>
                                    <th style="vertical-align:middle;width:8%;text-align:center;">Gender</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Religion</th>
                                    <th style="vertical-align:middle;width:15%;text-align:center;">Birth</th>
                                    <th style="vertical-align:middle;width:5%;text-align:center;">Status</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Accept <br>Lower Rank
                                    </th>
                                    <th style="vertical-align:middle;width:8%;text-align:center;">#</th>
                                </tr>
                            </thead>
                            <tbody id="idTbody">
                                <?php echo $trNya; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="idPage"><?php echo $listPage; ?></div>
                </div>
            </div>
        </div>
        <div class="form-panel" style="padding-top:5px;display:none;" id="idForm">
            <legend style="text-align:right;margin-bottom:5px;">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <select id="slcDataForm" class="form-control input-sm" onchange="navProsesCrew();"
                            style="float:left;margin-bottom:5px;display:none;">
                            <option value="personal_data">Personal Data</option>
                            <option value="personal_id">Personal Id</option>
                            <option value="family">Family Details</option>
                            <option value="certificate">All Certificate / Document</option>
                            <option value="compliance">Compliance Certificate</option>
                            <option value="sea">Sea Experiance</option>
                            <option value="general">General</option>
                            <option value="language">Language Knowledge</option>
                            <option value="education">Education Attainment</option>
                            <option value="contract">Contract</option>
                            <option value="vaccine">Vaccine</option>
                            <option value="other">Others</option>
                        </select>
                        <input type="hidden" id="txtIdPerson" value="">
                    </div>
                    <div class="col-md-1 col-xs-12">
                        <button class="btn btn-primary btn-xs btn-block" title="Back" onclick="backButton();"><i
                                class="fa fa-mail-reply-all"></i> Back</button>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <span id="teksJudulName" style="font-weight:bold;"></span>
                    </div>
                    <div class="col-md-5 col-xs-12">
                        <img id="idLoadingForm" src="<?php echo base_url('assets/img/loading.gif');?>"
                            style="margin-right:20px;display:none;">
                        <span id="teksJudulPage"><b><i>:: Personal Data ::</i></b></span>
                    </div>
                </div>
            </legend>
            <div id="idFormBody">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-6 col-xs-12">
                                <label for="fileUploadPersonalData" style="font-size:12px;">Photo :</label>
                                <input type="file" class="form-control input-sm" id="fileUploadPersonalData">
                            </div>
                            <div class="col-md-6 col-xs-12" id="idViewPic" style="text-align:center;"></div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtFname" style="font-size:12px;">First Name :</label>
                                <input type="text" class="form-control input-sm" id="txtFname" placeholder="First Name">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtMname" style="font-size:12px;">Middle Name :</label>
                                <input type="text" class="form-control input-sm" id="txtMname"
                                    placeholder="Middle Name">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtLname" style="font-size:12px;">Last Name :</label>
                                <input type="text" class="form-control input-sm" id="txtLname" placeholder="Last Name">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="slcCountryNational" style="font-size:12px;">Nationality (current
                                    Citizenship) :</label>
                                <select class="form-control input-sm" id="slcCountryNational">
                                    <?php echo $optCountry; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcCountryOrigin" style="font-size:12px;">Country of Origin :</label>
                                <select class="form-control input-sm" id="slcCountryOrigin">
                                    <?php echo $optCountry; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtDate_DOB" style="font-size:12px;">Date of Birth :</label>
                                <input type="text" class="form-control input-sm" id="txtDate_DOB" placeholder="Date">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="slcCityBirth" style="font-size:12px;">Place / City of Birth :</label>
                                <select class="form-control input-sm" id="slcCityBirth">
                                    <?php echo $optCity; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcMaritalStatus" style="font-size:12px;">Marital Status :</label>
                                <select class="form-control input-sm" id="slcMaritalStatus">
                                    <?php echo $optMaritalStatus; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtFatherName" style="font-size:12px;">Father Name :</label>
                                <input type="text" class="form-control input-sm" id="txtFatherName"
                                    placeholder="Father Name">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtMotherName" style="font-size:12px;">Mother Name :</label>
                                <input type="text" class="form-control input-sm" id="txtMotherName"
                                    placeholder="Mother Name">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtSosSecNumber" style="font-size:12px;">Social Security Number :</label>
                                <input type="text" class="form-control input-sm" id="txtSosSecNumber"
                                    placeholder="Social Security Number">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcSosSecIssuingCountry" style="font-size:12px;">Social Security Issuing
                                    Country :</label>
                                <select class="form-control input-sm" id="slcSosSecIssuingCountry">
                                    <?php echo $optCountry; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtTaxNumber" style="font-size:12px;">Personal Tax Number :</label>
                                <input type="text" class="form-control input-sm" id="txtTaxNumber"
                                    placeholder="Tax Number">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="slcTaxIssCountry" style="font-size:12px;">Personal Tax Issuing Country
                                    :</label>
                                <select class="form-control input-sm" id="slcTaxIssCountry">
                                    <?php echo $optCountry; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtCesScore" style="font-size:12px;">CES Score :</label>
                                <input type="text" class="form-control input-sm" id="txtCesScore"
                                    placeholder="CES Score">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtmarlinTest" style="font-size:12px;">Marlin Test Score :</label>
                                <input type="text" class="form-control input-sm" id="txtmarlinTest" placeholder="Score">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-12 col-xs-12">
                                <legend style="font-size:12px;float:right;margin-bottom:0px;margin-top:10px;">Training :
                                </legend>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <label for="txtDate_training" style="font-size:12px;">Date :</label>
                                        <input type="text" class="form-control input-sm" id="txtDate_training"
                                            placeholder="Date">
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <label for="txtEvaluation" style="font-size:12px;">Evaluation :</label>
                                        <input type="text" class="form-control input-sm" id="txtEvaluation"
                                            placeholder="Evaluation">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-12 col-xs-12">
                                <legend style="font-size:12px;float:right;margin-bottom:0px;margin-top:10px;">Account
                                    Bank :</legend>
                                <div class="row">
                                    <div class="col-md-4 col-xs-12">
                                        <label for="txtBankName" style="font-size:12px;">Bank Name :</label>
                                        <input type="text" class="form-control input-sm" id="txtBankName"
                                            placeholder="Bank">
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <label for="txtAccNo" style="font-size:12px;">Account Number :</label>
                                        <input type="text" class="form-control input-sm" id="txtAccNo"
                                            placeholder="Account No">
                                    </div>
                                    <div class="col-md-4 col-xs-12">
                                        <label for="txtAccountName" style="font-size:12px;">Account Name :</label>
                                        <input type="text" class="form-control input-sm" id="txtAccountName"
                                            placeholder="Account Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-9 col-xs-12">
                                        <label for="uploadFileOfWages" style="font-size:12px;">File Statement of Wages
                                            :</label>
                                        <input type="file" class="form-control input-sm" id="uploadFileOfWages">
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label>&nbsp</label>
                                        <button class="btn btn-warning btn-xs btn-block"
                                            onclick="$('#uploadFileOfWages').val('')">Clear</button>
                                        <div id="idDivViewWages"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-9 col-xs-12">
                                        <label for="uploadFileStatementForm" style="font-size:12px;">File Statement
                                            :</label>
                                        <input type="file" class="form-control input-sm" id="uploadFileStatementForm">
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label>&nbsp</label>
                                        <button class="btn btn-warning btn-xs btn-block"
                                            onclick="$('#uploadFileStatementForm').val('')">Clear</button>
                                        <div id="idDivViewStatementForm"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-9 col-xs-12">
                                        <label for="uploadFileInterview" style="font-size:12px;">File Interview
                                            :</label>
                                        <input type="file" class="form-control input-sm" id="uploadFileInterview">
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label>&nbsp</label>
                                        <button class="btn btn-warning btn-xs btn-block"
                                            onclick="$('#uploadFileInterview').val('')">Clear</button>
                                        <div id="idDivViewInterview"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <div class="row">
                                    <div class="col-md-9 col-xs-12">
                                        <label for="uploadFileEvaluation" style="font-size:12px;">File Evaluation
                                            :</label>
                                        <input type="file" class="form-control input-sm" id="uploadFileEvaluation">
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label>&nbsp</label>
                                        <button class="btn btn-warning btn-xs btn-block"
                                            onclick="$('#uploadFileEvaluation').val('')">Clear</button>
                                        <div id="idDivViewEvaluation"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="rdGenderMale" style="font-size:12px;">Gender :</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="rdGender" id="rdGenderMale" value="Male"> Male
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="rdGender" id="rdGenderFemale" value="Female"> Female
                                </label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtWifeName" style="font-size:12px;">Wife Name :</label>
                                <input type="text" class="form-control input-sm" id="txtWifeName"
                                    placeholder="Wife Name">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcReligion" style="font-size:12px;">Religion :</label>
                                <select class="form-control input-sm" id="slcReligion">
                                    <?php echo $optReligion; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="slcRankApply" style="font-size:12px;">Rank applied for :</label>
                                <select class="form-control input-sm" id="slcRankApply">
                                    <?php echo $optRank; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcVesselApply" style="font-size:12px;">Vessel applied for :</label>
                                <select class="form-control input-sm" id="slcVesselApply">
                                    <?php echo $optVessel; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="rdLowerRankNo" style="font-size:12px;">Willing to accept lower rank
                                    ?</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="rdLowerRank" id="rdLowerRankYes" value="1"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="rdLowerRank" id="rdLowerRankNo" value="0"
                                        checked="checked"> No
                                </label>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtDate_available" style="font-size:12px;">Available From (date) :</label>
                                <input type="text" class="form-control input-sm" id="txtDate_available"
                                    placeholder="Date">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtAddressPrimary" style="font-size:12px;">Primary / Permanent Address
                                    :</label>
                                <textarea class="form-control input-sm" id="txtAddressPrimary"></textarea>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcCity" style="font-size:12px;">City :</label>
                                <select class="form-control input-sm" id="slcCity">
                                    <?php echo $optCity; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="slcNearestAirport" style="font-size:12px;">Nearest Airport :</label>
                                <select class="form-control input-sm" id="slcNearestAirport">
                                    <?php echo $optCity; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtPostCode" style="font-size:12px;">Post Code :</label>
                                <input type="text" class="form-control input-sm" id="txtPostCode"
                                    placeholder="Post Code">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcCountry" style="font-size:12px;">Country :</label>
                                <select class="form-control input-sm" id="slcCountry">
                                    <?php echo $optCountry; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtMobileNo" style="font-size:12px;">Mobile Tel. :</label>
                                <input type="text" class="form-control input-sm" id="txtMobileNo"
                                    placeholder="Mobile No">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtHomeNo" style="font-size:12px;">Home Tel. :</label>
                                <input type="text" class="form-control input-sm" id="txtHomeNo" placeholder="Home No">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtFax" style="font-size:12px;">Fax :</label>
                                <input type="text" class="form-control input-sm" id="txtFax" placeholder="Fax">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtEmail" style="font-size:12px;">Email :</label>
                                <input type="text" class="form-control input-sm" id="txtEmail" placeholder="Email">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="slcBloodType" style="font-size:12px;">Blood Type :</label>
                                <select class="form-control input-sm" id="slcBloodType">
                                    <?php echo $optBlood; ?>
                                </select>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtEyeColor" style="font-size:12px;">Eye Color :</label>
                                <input type="text" class="form-control input-sm" id="txtEyeColor"
                                    placeholder="Eye Color">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-8 col-xs-12">
                                <label style="font-size:12px;">Contact Method :</label><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkEmail" value="1"> Email
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkFax" value="1"> Fax
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkMobilePhone" value="1"> Mobile Phone
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkHomePhone" value="1"> Home Phone
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkPost" value="1"> Post
                                </label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtNextOfKin" style="font-size:12px;">Next of Kin :</label>
                                <input type="text" class="form-control input-sm" id="txtNextOfKin"
                                    placeholder="Next of Kin" value="">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-2 col-xs-12">
                                <label for="txtWeight" style="font-size:12px;">Weight (k/g) :</label>
                                <input type="text" class="form-control input-sm" id="txtWeight" placeholder="Weight">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtHeight" style="font-size:12px;">Height (cm) :</label>
                                <input type="text" class="form-control input-sm" id="txtHeight" placeholder="Height">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtShoes" style="font-size:12px;">Shoes (mm) :</label>
                                <select class="form-control input-sm" id="txtShoes">
                                    <option value="39">39</option>
                                    <option value="40">40</option>
                                    <option value="41">41</option>
                                    <option value="42">42</option>
                                    <option value="43">43</option>
                                    <option value="44">44</option>
                                    <option value="45">45</option>
                                    <option value="46">46</option>
                                    <option value="47">47</option>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtCollar" style="font-size:12px;">Collar (cm) :</label>
                                <input type="text" class="form-control input-sm" id="txtCollar" placeholder="Collar">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtChest" style="font-size:12px;">Chest (cm) :</label>
                                <input type="text" class="form-control input-sm" id="txtChest" placeholder="Chest">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtWaist" style="font-size:12px;">Waist (cm) :</label>
                                <input type="text" class="form-control input-sm" id="txtWaist" placeholder="Waist">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-2 col-xs-12">
                                <label for="txtInsLed" style="font-size:12px;">Ins. Led (cm) :</label>
                                <input type="text" class="form-control input-sm" id="txtInsLed"
                                    placeholder="Inside Led">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtCap" style="font-size:12px;">Cap (cm) :</label>
                                <input type="text" class="form-control input-sm" id="txtCap" placeholder="Cap">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="slcSizeClothes" style="font-size:12px;">Clothes Size :</label>
                                <select class="form-control input-sm" id="slcSizeClothes">
                                    <?php echo $optSize; ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="slcSizeSweater" style="font-size:12px;">Sweater&nbspSize&nbsp:</label>
                                <select class="form-control input-sm" id="slcSizeSweater">
                                    <?php echo $optSize; ?>
                                </select>
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="slcSizeBoilersuit" style="font-size:12px;">Boilersuit&nbspsize&nbsp:</label>
                                <select class="form-control input-sm" id="slcSizeBoilersuit">
                                    <?php echo $optSize; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="rdHeightPhobiaNo" style="font-size:12px;">Height Phobia :</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="rdHeightPhobia" id="rdHeightPhobiaYes" value="y"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="rdHeightPhobia" id="rdHeightPhobiaNo" value="n"
                                        checked="checked"> No
                                </label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="rdFeelClaustrophobicNo" style="font-size:12px;">Feel claustrophobic
                                    :</label><br>
                                <label class="radio-inline">
                                    <input type="radio" name="rdFeelClaustrophobic" id="rdFeelClaustrophobicYes"
                                        value="y"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="rdFeelClaustrophobic" id="rdFeelClaustrophobicNo"
                                        value="n" checked="checked"> No
                                </label>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <div class="row">
                                    <div class="col-md-9 col-xs-12">
                                        <label for="txtcrewVesselStatus" style="font-size:12px;">Crew Vessel Type
                                            :</label>
                                        <select class="form-control input-sm" id="slcVesselType">
                                            <?php echo $getVesselType; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtAnyAllergi" style="font-size:12px;">Any Allergy :</label><br>
                                <input type="text" class="form-control input-sm" id="txtAnyAllergi"
                                    placeholder="Any Allergy">
                            </div>
                            <div class="col-md-8 col-xs-12">
                                <label for="txtAdditionalRemark" style="font-size:12px;">Additional Remarks
                                    :</label><br>
                                <textarea class="form-control input-sm" id="txtAdditionalRemark"></textarea>
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-4 col-xs-12">
                                <label for="txtSignPlace" style="font-size:12px;">Sign Place :</label><br>
                                <input type="text" class="form-control input-sm" id="txtSignPlace"
                                    placeholder="Sign Place">
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <label for="txtDate_sign" style="font-size:12px;">Sign Date :</label><br>
                                <input type="text" class="form-control input-sm" id="txtDate_sign" placeholder="Date">
                            </div>
                        </div>
                        <div class="row" style="margin-top:5px;">
                            <div class="col-md-3 col-xs-12">
                                <label for="chkNewApp" style="font-size:12px;">New applicent :</label><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkNewApp" value="1"> New applicent
                                </label>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label for="chkNonAktif" style="font-size:12px;">Non Aktif :</label><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkNonAktif" value="1"> Non Aktif
                                </label>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label for="chkBlackList" style="font-size:12px;">Not for Employeed :</label><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkBlackList" value="1"> Not for Employeed
                                </label>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <label for="chkNonCrew" style="font-size:12px;">Non Crew :</label><br>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="chkNonCrew" value="1"> Non Crew
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-bottom:30px;margin-top:30px;">
                    <input type="hidden" id="txtIdEdit" value="">
                    <div class="col-md-6 col-xs-12">
                        <button id="btnSave" class="btn btn-primary btn-xs btn-block" title="Save Data"
                            onclick="saveDataPersonal();"><i class="glyphicon glyphicon-saved"></i> Save</button>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <button class="btn btn-danger btn-xs btn-block" title="Cancel" onclick="reloadPage();"><i
                                class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
                    </div>
                </div>
            </div>
            <div id="idFormBodyOther" style="min-height:500px;display:none;">
            </div>
        </div>
    </div>
    <!-- </section> -->
</body>

</html>