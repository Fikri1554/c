<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <script type="text/javascript">
    function displayOnBoard() {
        $("#idLoading").show();
        $.post('<?php echo base_url("dashboard/getDetailOnBoard"); ?>', {},
            function(data) {
                $("#idBodyModal").empty();
                $("#idBodyModal").append(data.trNya);

                $("#idLblModal").text(data.totalCrew);

                $('#modalReqDetail').modal('show');
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function displayOnLeave() {
        $("#idLoading").show();
        $.post('<?php echo base_url("dashboard/getDetailOnLeave"); ?>', {}, function(data) {
            if (data) {
                $("#idBodyTotalCrewOnLeave").empty();
                $("#idBodyTotalCrewOnLeave").append(data.trNya);
                $("#idLblModalTotalCrew").text(data.totalCrew);
                $('#modalTotalCrewOnLeave').modal('show');
            } else {
                alert("Gagal memuat data. Silakan coba lagi.");
            }
            $("#idLoading").hide();
        }, "json").fail(function() {
            alert("Terjadi kesalahan. Silakan coba lagi.");
            $("#idLoading").hide();
        });
    }



    $(document).on('click', '.table-row', function() {
        var $detailsRow = $(this).next('.details-row');
        if ($detailsRow.length) {
            $detailsRow.slideToggle();
        }
    });


    function displayNewApplicent() {
        $("#idLoading").show();
        $.post('<?php echo base_url("dashboard/getDetailCrewNewApplicent"); ?>', {},
            function(data) {
                $("#idBodyModalCrew").empty();
                $("#idBodyModalCrew").append(data.trNya);

                $("#idLblModalTotalCrew").text(data.totalCrew);

                $('#modalShowCrewing').modal('show');
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function getDetailCrew(vslCode) {
        $("#idLoadingModal").show();
        $.post('<?php echo base_url("dashboard/getDetailCrewOnBoard"); ?>', {
                vslCode: vslCode
            },
            function(data) {
                $("#idBodyModalCrewDetail").empty();
                $("#idBodyModalCrewDetail").append(data.trNya);
                $("#idLblModalVesselDetail").text(data.vessel);

                $("#idLoadingModal").hide();
            },
            "json"
        );
    }

    function getDetailCrewName(rank, crewName) {
        $.ajax({
            url: '<?php echo base_url("dashboard/getDetailCrewOnLeave"); ?>',
            type: 'POST',
            data: {
                rank: rank,
                crew_name: crewName,
            },
            dataType: 'json',
            success: function(data) {
                const tbody = $('#idBodyModalCrewDetailOnLeave');
                tbody.html(data.trNya);

                const label = $('#idLblModalCrewNameOnLeave');
                label.html(`${crewName} (${rank})`);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching crew details:', error);
                alert('Failed to fetch crew details. Please try again later.');
            },
        });
    }

    function changeCursor(element, hasData) {
        if (hasData) {
            element.style.cursor = 'pointer';
        } else {
            element.style.cursor = 'default';
        }
    }

    function resetCursor(element) {
        element.style.cursor = 'default';
    }

    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/contractBarChart'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                const crewData = response.crewData;
                const rankSummary = response.rankSummary;
                const rankOrder = response.rankOrder || {};
                const months = Object.keys(rankSummary);

                var rankCategories = Object.keys(rankOrder)
                    .sort((a, b) => (rankOrder[a] || 999) - (rankOrder[b] || 999));

                var seriesData = rankCategories.map(rank => ({
                    name: rank,
                    data: months.map(month => (rankSummary[month] && rankSummary[month][
                            rank
                        ]) ?
                        rankSummary[month][rank] :
                        0)
                })).filter(series => series.data.some(value => value > 0));

                var totalData = months.map(month => {
                    return Object.values(rankSummary[month] || {}).reduce((sum, value) =>
                        sum + value, 0);
                });

                const chart = Highcharts.chart('idDivBarChartRank', {
                    chart: {
                        type: 'column',
                        backgroundColor: null,
                        height: 1000,
                        width: 1150
                    },
                    title: {
                        text: 'Crew Contract Expiry: Monthly Distribution in 2025',
                        style: {
                            fontSize: '22px',
                            fontWeight: 'bold',
                            color: '#000'
                        }
                    },
                    xAxis: {
                        categories: months,
                        crosshair: true,
                        labels: {
                            style: {
                                fontSize: '16px',
                                color: '#000'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Crew',
                            style: {
                                fontSize: '18px',
                                fontWeight: 'bold',
                                color: '#000'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '16px',
                                color: '#000'
                            }
                        },
                        stackLabels: {
                            enabled: true,
                            style: {
                                fontWeight: 'bold',
                                color: '#000',
                                fontSize: '16px'
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        valueSuffix: ' crew members'
                    },
                    plotOptions: {
                        column: {
                            stacking: null,
                            pointPadding: 0.1,
                            groupPadding: 0.1,
                            borderWidth: 0,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false,
                            },
                            events: {
                                click: function(event) {
                                    $("#paginationInfo").html('');
                                    const index = Math.round(event.point.index);
                                    const selectedMonth = months[index];
                                    const crewDetails = crewData.filter(item => item
                                        .month === selectedMonth);

                                    var groupedData = {};
                                    crewDetails.forEach(item => {
                                        if (!groupedData[item.rank_name]) {
                                            groupedData[item.rank_name] = [];
                                        }
                                        groupedData[item.rank_name].push({
                                            name: item.crew_name,
                                            signOn: item.sign_on_date,
                                            signOff: item
                                                .estimated_signoff_date
                                        });
                                    });

                                    var modalBody = '';
                                    var rowNumber = 1;
                                    Object.keys(groupedData)
                                        .sort((a, b) => (rankOrder[a] || 999) - (
                                            rankOrder[b] || 999))
                                        .forEach(rank => {
                                            var crewList = groupedData[rank];
                                            crewList.forEach((crew, index) => {
                                                modalBody += `<tr>
                                                ${index === 0 ? `<td style="text-align: center;" rowspan="${crewList.length}">${rowNumber}</td>` : ''}
                                                <td style="text-align: left;">${crew.name}</td>
                                                ${index === 0 ? `<td style="text-align: left;" rowspan="${crewList.length}">${rank}</td>` : ''}
                                                <td style="text-align: left;">${crew.signOn}</td>
                                                <td style="text-align: left;">${crew.signOff}</td>
                                            </tr>`;
                                            });
                                            rowNumber++;
                                        });

                                    $('#modalTitleCrewDetailByEstimatedSignOff').text(
                                        `Crew Distribution for ${selectedMonth}`);
                                    $('#idBodyModalCrewDetailByEstimatedSignOff').html(
                                        modalBody);
                                    $('#detailModalCrewSignoff').modal('show');
                                }
                            }
                        }
                    },
                    series: [{
                        name: 'Total',
                        data: totalData,
                        color: {
                            linearGradient: [0, 0, 0, 500],
                            stops: [
                                [0, '#001f3f'],
                                [1, '#0074D9']
                            ]
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '16px',
                                fontWeight: 'bold',
                                color: '#000'
                            }
                        }
                    }],
                    legend: {
                        enabled: false,
                        labelFormatter: function() {
                            return this.name;
                        },
                        useHTML: true
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: true
                    }
                });
                setTimeout(function() {
                    if (!$('#convertToTableBtn').length) {
                        $('#idDivBarChartRank').after(
                            '<button id="convertToTableBtn" class="btn btn-primary btn-sm mt-3">Convert to Table</button>'
                        );
                    }
                }, 500)


                $(document).on('click', '#convertToTableBtn', function() {
                    var groupedData = {};
                    crewData.forEach(item => {
                        if (!groupedData[item.rank_name]) {
                            groupedData[item.rank_name] = [];
                        }
                        groupedData[item.rank_name].push({
                            name: item.crew_name,
                            signOn: item.sign_on_date,
                            signOff: item.estimated_signoff_date
                        });
                    });

                    var rankKeys = Object.keys(groupedData).sort((a, b) => (rankOrder[a] ||
                        999) - (rankOrder[b] || 999));
                    var currentPage = 0;

                    function displayTable(page) {
                        var rank = rankKeys[page];
                        var crewList = groupedData[rank] || [];
                        var modalBody = '';

                        crewList.forEach((crew, index) => {
                            modalBody += `<tr>
                                ${index === 0 ? `<td style="text-align: center;" rowspan="${crewList.length}">${page + 1}</td>` : ''}
                                <td style="text-align: left;">${crew.name}</td>
                                ${index === 0 ? `<td style="text-align: left;" rowspan="${crewList.length}">${rank}</td>` : ''}
                                <td style="text-align: left;">${crew.signOn}</td>
                                <td style="text-align: left;">${crew.signOff}</td>
                            </tr>`;
                        });

                        $('#idBodyModalCrewDetailByEstimatedSignOff').html(modalBody);
                        updatePagination();
                    }

                    function updatePagination() {
                        var totalPages = rankKeys.length;
                        var paginationHtml = `<nav><ul class="pagination">`;

                        for (var i = 0; i < totalPages; i++) {
                            paginationHtml += `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                <a href="#" class="page-link" data-page="${i}">${rankKeys[i]}</a>
                            </li>`;
                        }

                        paginationHtml += `</ul></nav>`;
                        $('#paginationInfo').html(paginationHtml);
                    }

                    $(document).on('click', '.page-link', function(event) {
                        event.preventDefault();
                        currentPage = parseInt($(this).data('page'));
                        displayTable(currentPage);
                    });

                    $('#modalTitleCrewDetailByEstimatedSignOff').text(
                        'Crew Contract Expiry Data');
                    $('#detailModalCrewSignoff').modal('show');
                    displayTable(currentPage);
                });


            }
        });
    });

    $(document).ready(function() {
        function loadChart(category) {
            $.ajax({
                url: '<?php echo base_url("dashboard/getSchool"); ?>',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    const categories = data.map(item => item.school);
                    let seriesData;
                    let onboardData = data.map(item => item.onboard_crew);

                    if (category === "Onboard") {
                        seriesData = onboardData;
                        $('#comparisonContainer').hide();
                        $('#idDivtotalSchoolContainer').removeClass('col-md-6').addClass(
                            'col-md-12');
                    } else if (category === "Onleave") {
                        seriesData = data.map(item => item.onleave_crew);
                        $('#comparisonContainer').show();
                        $('#idDivtotalSchoolContainer, #comparisonContainer').removeClass(
                            'col-md-12').addClass('col-md-6');
                    } else {
                        seriesData = data.map(item => item.onboard_crew + item.onleave_crew);
                        $('#comparisonContainer').show();
                        $('#idDivtotalSchoolContainer, #comparisonContainer').removeClass(
                            'col-md-12').addClass('col-md-6');
                    }

                    Highcharts.chart('idDivtotalSchool', {
                        chart: {
                            type: 'bar',
                            backgroundColor: null,
                            height: 600
                        },
                        title: {
                            text: `Top 10 Schools by Crew (${category})`,
                            style: {
                                fontSize: '20px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        },
                        xAxis: {
                            categories: categories,
                            title: {
                                text: 'Nama Sekolah'
                            },
                            labels: {
                                style: {
                                    fontSize: '14px'
                                }
                            },
                            gridLineWidth: 1
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'Jumlah Crew'
                            },
                            labels: {
                                style: {
                                    fontSize: '16px'
                                }
                            }
                        },
                        tooltip: {
                            formatter: function() {
                                return `<b>${data[this.point.index].school}</b><br>
                            ${category} Crew: ${seriesData[this.point.index]}`;
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    style: {
                                        fontSize: '16px',
                                        color: 'black'
                                    }
                                },
                                groupPadding: 0.1
                            }
                        },
                        series: [{
                            name: `${category} Crew`,
                            data: seriesData,
                            color: category === "Onboard" ? '#5DADE2' : (
                                category === "Onleave" ? '#F39C12' : '#2ECC71')
                        }]
                    });

                    if (category === "Onleave" || category === "All") {
                        Highcharts.chart('idDivComparison', {
                            chart: {
                                type: 'bar',
                                backgroundColor: null,
                                height: 600
                            },
                            title: {
                                text: 'Comparison with Onboard Crew',
                                style: {
                                    fontSize: '18px',
                                    fontWeight: 'bold',
                                    color: '#333'
                                }
                            },
                            xAxis: {
                                categories: categories,
                                title: {
                                    text: 'Nama Sekolah'
                                },
                                labels: {
                                    style: {
                                        fontSize: '14px'
                                    }
                                },
                                gridLineWidth: 1
                            },
                            yAxis: {
                                min: 0,
                                title: {
                                    text: 'Jumlah Crew'
                                },
                                labels: {
                                    style: {
                                        fontSize: '16px'
                                    }
                                }
                            },
                            tooltip: {
                                formatter: function() {
                                    return `<b>${data[this.point.index].school}</b><br>
                                Onboard Crew: ${onboardData[this.point.index]}`;
                                }
                            },
                            plotOptions: {
                                bar: {
                                    dataLabels: {
                                        enabled: true,
                                        style: {
                                            fontSize: '16px',
                                            color: 'black'
                                        }
                                    },
                                    groupPadding: 0.1
                                }
                            },
                            series: [{
                                name: 'Onboard Crew',
                                data: onboardData,
                                color: '#5DADE2'
                            }]
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', xhr.responseText || error);
                    alert(`Failed to load data: ${xhr.status} - ${xhr.statusText}`);
                }
            });
        }

        $('#crewCategory').change(function() {
            const selectedCategory = $(this).val();
            loadChart(selectedCategory);
        });

        loadChart('Onboard');
    });


    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/getCadangan'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const columns = 6;
                const heatmapData = data.map((item, index) => ({
                    x: (columns - 1) - (index %
                        columns),
                    y: Math.floor(index / columns),
                    value: item.total_onleave,
                    onboard: item.total_onboard,
                    rank: item.rank,
                    category: item.category,
                    color: item.color
                }));

                Highcharts.chart('idDivHeatMap', {
                    chart: {
                        type: 'heatmap',
                        plotBorderWidth: 1,
                        height: 500,
                        width: 1150,
                        backgroundColor: null
                    },
                    title: {
                        text: 'Ship Reserves per Rank (Heatmap)',
                        align: 'center',
                        style: {
                            fontSize: '20px',
                        }
                    },
                    xAxis: {
                        labels: {
                            enabled: false
                        },
                        title: null,
                    },
                    yAxis: {
                        labels: {
                            enabled: false
                        },
                        title: null,
                    },
                    tooltip: {
                        formatter: function() {
                            return `
                    <b>${this.point.rank}</b><br>
                    Crew Off-Duty: ${this.point.value}<br>
                    Crew On-Duty: ${this.point.onboard}<br>
                    `;
                        },
                    },
                    series: [{
                        borderWidth: 0.5,
                        data: heatmapData.map((item) => ({
                            x: item.x,
                            y: item.y,
                            value: item.value,
                            onboard: item.onboard,
                            rank: item.rank,
                            color: item.color
                        })),
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return this.point.rank;
                            },
                            style: {
                                fontSize: '15px',
                                fontWeight: 'bold',
                                color: '#000',
                                textOutline: 'none'
                            },
                        },
                    }],
                    credits: {
                        enabled: false,
                    },
                    legend: {
                        enabled: false
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', status, error);
            },
        });

    });

    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/rankContractExpiry'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                var categories = [];
                var expiringData = [];
                var suggestionData = [];
                var totalOnboard = [];
                var totalOnleave = [];

                response.forEach(item => {
                    categories.push(item.RankName);

                    var totalExpiring = parseInt(item.total_expiring);
                    var totalOnBoard = parseInt(item.total_onboard);
                    var totalOnLeave = parseInt(item.total_onleave);
                    var suggestionValue = parseInt(item.SuggestedRecruitment);

                    expiringData.push(totalExpiring);
                    suggestionData.push(suggestionValue);
                    totalOnboard.push(totalOnBoard);
                    totalOnleave.push(totalOnLeave);
                });

                Highcharts.chart('idDivRankContractExpiry', {
                    chart: {
                        type: 'bar',
                        height: 900,
                        backgroundColor: null
                    },
                    title: {
                        text: 'Recruitment Suggestion & Total Expiring ( 1 Months )',
                        style: {
                            fontSize: '20px',
                            fontWeight: 'bold',
                            color: '#000'
                        }
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Rank',
                            style: {
                                fontSize: '16px',
                                color: '#000'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '14px',
                                color: '#000'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Total Crew',
                            style: {
                                fontSize: '16px',
                                color: '#000'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '14px',
                                color: '#000'
                            }
                        }
                    },
                    tooltip: {
                        shared: true,
                        style: {
                            fontSize: '14px',
                            color: '#000'
                        },
                        formatter: function() {
                            var index = this.point.index;
                            var suggestionText = suggestionData[index] > 0 ?
                                `<b style="color:#000;">Recruitment Suggestion: (${suggestionData[index]}) orang</b>` :
                                '<b style="color:#000;">Cukup</b>';

                            return `
                                    <span style="font-size: 14px; color:#000;">${suggestionText}</span>`;

                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '14px',
                                    fontWeight: 'bold',
                                    color: '#000'
                                }
                            },
                            groupPadding: 0.1
                        }
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'top',
                        x: 10,
                        y: 50,
                        floating: true,
                        backgroundColor: 'rgba(255, 255, 255, 0.7)',
                        borderRadius: 5,
                        itemStyle: {
                            fontSize: '13px',
                            color: '#000'
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Recruitment Suggestion',
                        data: suggestionData,
                        colors: 'blue',
                        dataLabels: {
                            enabled: true,
                            inside: true,
                            align: 'right',
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold',
                                color: '#000'
                            }
                        }
                    }]
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching data:", error);
            }
        });
    });

    $(document).ready(function() {
        $("#selectAllVesselsOwnShipOwnShip").change(function() {
            $("input[name='vessels[]']").prop("checked", this.checked);
        });

        $("input[name='vessels[]']").change(function() {
            if (!this.checked) {
                $("#selectAllVesselsOwnShip").prop("checked", false);
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        var expanded = false;

        window.showCheckboxes = function() {
            var checkboxes = document.getElementById("idCheckboxVesselOwnShip");
            if (checkboxes) {
                if (!expanded) {
                    checkboxes.style.display = "block";
                    expanded = true;
                } else {
                    checkboxes.style.display = "none";
                    expanded = false;
                }
            } else {
                console.error("Element with ID 'idCheckboxVesselOwnShip' not found!");
            }
        };
    });

    document.addEventListener("DOMContentLoaded", function() {
        var expandedClient = false;

        window.showCheckboxesClient = function() {
            var checkboxesClient = document.getElementById("idCheckboxVesselClient");
            if (checkboxesClient) {
                if (!expandedClient) {
                    checkboxesClient.style.display = "block";
                    expandedClient = true;
                } else {
                    checkboxesClient.style.display = "none";
                    expandedClient = false;
                }
            } else {
                console.error("Element with ID 'idCheckboxVesselClient' not found!")
            }
        }
    })

    function searchVesselClient() {
        var selectedVesselClient = [];

        if ($("#selectAllVesselsClientShip").is(":checked")) {
            selectedVesselClient.push("All");
        } else {
            $("input[name='vesselsClient[]']:checked").each(function() {
                selectedVesselClient.push($(this).val());
            });
        }

        if (selectedVesselClient.length === 0) {
            alert('Pilih minimal satu kapal');
            return;
        }

        $.ajax({
            url: "<?php echo base_url('dashboard/crewBarChart'); ?>",
            type: "POST",
            data: {
                vesselsClient: selectedVesselClient
            },
            dataType: "json",
            success: function(response) {
                if (!response || response.length === 0) {
                    alert('Data not found!');
                    return;
                }

                var totalCrewClient = 0,
                    totalMaleClient = 0,
                    totalFemaleClient = 0,
                    totalAgeSumClient = 0;
                var vesselListClient = [];

                response.forEach(function(ship) {
                    var crewCountClient = parseInt(ship.jumlah_crew_onboard) || 0;
                    totalCrewClient += crewCountClient;
                    totalMaleClient += parseInt(ship.total_male) || 0;
                    totalFemaleClient += parseInt(ship.total_female) || 0;
                    totalAgeSumClient += parseInt(ship.total_umur) || 0;

                    vesselListClient.push(ship.nama_kapal);
                });

                var avgAgeClient = totalCrewClient > 0 ? (totalAgeSumClient / totalCrewClient).toFixed(1) :
                    0;

                $("#txtTotalCrewShipClient").text(totalCrewClient);
                $("#txtAvgAgeShipClient").text(avgAgeClient);
                $("#txtTotalMaleShipClient").text(totalMaleClient);
                $("#txtTotalFemaleShipClient").text(totalFemaleClient);

                var halfClient = Math.ceil(vesselListClient.length / 2);
                $("#listKapalClient_1").html(
                    "<ul style='font-size: 18px; color: #000080; font-weight: bold;'>" +
                    vesselListClient.slice(0, halfClient).map(vessel =>
                        `<li><i class='fa fa-ship'></i> ${vessel}</li>`).join("") +
                    "</ul>"
                );
                $("#listKapalClient_2").html(
                    "<ul style='font-size: 18px; color: #000080; font-weight: bold;'>" +
                    vesselListClient.slice(halfClient).map(vessel =>
                        `<li><i class='fa fa-ship'></i> ${vessel}</li>`).join("") +
                    "</ul>"
                );
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Gagal mengambil data. Silakan coba lagi.");
            }
        });
    }

    function searchVessel() {
        var selectedVessels = [];

        if ($("#selectAllVesselsOwnShip").is(":checked")) {
            selectedVessels.push("All");
        } else {
            $("input[name='vessels[]']:checked").each(function() {
                selectedVessels.push($(this).val());
            });
        }

        if (selectedVessels.length === 0) {
            alert("Pilih minimal satu kapal!");
            return;
        }

        $.ajax({
            url: "<?php echo base_url('dashboard/shipDemograph') ?>",
            type: "POST",
            data: {
                vessels: selectedVessels
            },
            dataType: "json",
            success: function(response) {
                if (!response || response.length === 0) {
                    alert("Data tidak ditemukan!");
                    return;
                }

                var totalCrew = 0,
                    totalMale = 0,
                    totalFemale = 0,
                    totalAgeSum = 0;
                var vesselList = [];

                response.forEach(function(ship) {
                    var crewCount = parseInt(ship.jumlah_crew_onboard) || 0;
                    totalCrew += crewCount;
                    totalMale += parseInt(ship.total_male) || 0;
                    totalFemale += parseInt(ship.total_female) || 0;
                    totalAgeSum += parseInt(ship.total_umur) || 0;
                    vesselList.push(ship.nama_kapal);
                });

                var avgAge = totalCrew > 0 ? (totalAgeSum / totalCrew).toFixed(1) : 0;

                $("#txtTotalCrew").text(totalCrew);
                $("#txtAvgAge").text(avgAge);
                $("#txtTotalMale").text(totalMale);
                $("#txtTotalFemale").text(totalFemale);

                var half = Math.ceil(vesselList.length / 2);
                $("#listKapal_1").html(
                    "<ul style='font-size: 18px; color: #000080; font-weight: bold;'>" +
                    vesselList.slice(0, half).map(vessel =>
                        `<li><i class='fa fa-ship'></i> ${vessel}</li>`).join(
                        "") +
                    "</ul>"
                );
                $("#listKapal_2").html(
                    "<ul style='font-size: 18px; color: #000080; font-weight: bold;'>" +
                    vesselList.slice(half).map(vessel =>
                        `<li><i class='fa fa-ship'></i> ${vessel}</li>`).join(
                        "") +
                    "</ul>"
                );
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
                alert("Gagal mengambil data. Silakan coba lagi.");
            }
        });
    }

    function showCheckboxes() {
        var checkboxes = document.getElementById("idCheckboxVesselOwnShip");
        if (checkboxes.style.display === "none") {
            checkboxes.style.display = "block";
        } else {
            checkboxes.style.display = "none";
        }
    }
    document.addEventListener("click", function(event) {
        var dropdown = document.getElementById("idCheckboxVesselOwnShip");
        var selectBox = document.querySelector("[onclick='showCheckboxes()']");

        if (!selectBox.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });

    function showCheckboxesClient() {
        var checkboxes = document.getElementById("idCheckboxVesselClient");
        if (checkboxes.style.display === "none") {
            checkboxes.style.display = "block";
        } else {
            checkboxes.style.display = "none";
        }
    }
    document.addEventListener("click", function(event) {
        var dropdown = document.getElementById("idCheckboxVesselClient");
        var selectBox = document.querySelector("[onclick='showCheckboxesClient()']");

        if (!selectBox.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = "none";
        }
    });
    </script>
    <style>
    body {
        background-color: #ffffff !important;
    }

    .header {
        background-color: #d1e9ef;
    }
    </style>

</head>

<body>
    <div class="container">
        <div class="form-panel" style="margin-top:5px;padding-bottom:15px;">
            <legend style="text-align:right;color:#067780;">
                <img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>"
                    style="margin-right:10px;display:none;">
                <b><i>:: DASHBOARD ::</i></b>
            </legend>
            <div class="row">
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-6">
                    <label style="font-size:18px;font-weight:bold;color:#067780;">Total : <?php echo $totalCrew; ?>
                        Person (On Board & On Leave)</label>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#FFFFFF;color:#000080;border:2px solid #000000;cursor:pointer;border-radius:30px;"
                        onclick="displayOnBoard();">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-anchor fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $onBoard; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">On Board</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#FFFFFF;color:#000080;border:2px solid #000000;cursor: pointer;border-radius:30px;"
                        onclick="displayOnLeave();">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-user-circle fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $onLeave; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">On Leave</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#FFFFFF;color:#000080;border:2px solid #000000;border-radius:30px;">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-user-circle-o fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $nonAktif; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">Non Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#FFFFFF;color:#000080;border:2px solid #000000;border-radius:30px;">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa-user-secret fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:32px;text-align:center;"><?php echo $notForEmp; ?></p>
                                <p style="font-size:10px;text-align:center;font-weight:bold;">Not for Employeed</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#FFFFFF;color:#000080;border:2px solid #000000;border-radius:30px;cursor:pointer;"
                        onclick="displayNewApplicent();">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa fa-user-plus fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $newApplicent; ?></p>
                                <p style="font-size:12px;text-align:center;font-weight:bold;">New Applicent</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#FFFFFF;color:#000080;border:2px solid #000000;border-radius:30px;">
                        <div class="row">
                            <div class="col-xs-3" style="text-align:center;">
                                <i class="fa fa fa-child fa-3x"></i>
                            </div>
                            <div class="col-xs-9">
                                <p style="font-size:30px;text-align:center;"><?php echo $cadetOnBoard; ?></p>
                                <p style="font-size:11px;text-align:center;font-weight:bold;">Cadet On Board</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!-- KAPAL CLIENT -->
                <div class="col-md-6" style="margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <h5 style="font-size:20px;">Data of Client Ship:</h5>
                        <div onclick="showCheckboxesClient()" style="position: relative; width: 150px; border: 1px solid #ccc; padding: 10px; border-radius: 8px; 
                        background: #fff; cursor: pointer; box-shadow: 0px 2px 5px rgba(0,0,0,0.2);">
                            <select
                                style="width: 100%; border: none; background: transparent; font-size: 14px; cursor: pointer;">
                                <option readonly>- Select Vessel -</option>
                            </select>
                            <div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;"></div>
                            <div id="idCheckboxVesselClient" style="display: none; border: 1px solid #ccc; border-radius: 8px; position: absolute; background: white; 
                            width: 100%; z-index: 1; padding: 10px; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
                                <?php echo $vesselTypeClient; ?>
                            </div>
                        </div>
                        <button type="submit" onclick="searchVesselClient();"
                            style="width: 150px; background: #84b0e3; color: white; padding: 10px; border: none; 
                            border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; transition: transform 0.2s, background 0.3s;"
                            onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'"
                            onmouseleave="this.style.transform='scale(1)'">
                            🔍 Search
                        </button>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <div class="row" style="margin-top: 20px; margin-right: 50px;">
                            <div class="col-md-4">
                                <div class="card text-center p-4 shadow-sm border-0" style="border-radius: 15px;">
                                    <h5 class="fw-bold"
                                        style="font-size: 20px; background-color: #84b0e3; padding: 5px; font-weight:bold; color: #fff;">
                                        Total Crew
                                    </h5>
                                    <div
                                        style="position: relative; display: inline-block; font-size: 150px; color: #84b0e3;">
                                        <span id="txtTotalCrewShipClient"
                                            style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                            font-size: 50px; font-weight: bold; color: #84b0e3; margin-top: 20px;">0</span>
                                        <i class='fa fa-users' style="font-size: 85px;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-4 shadow-sm border-0" style="border-radius: 15px;">
                                    <h5 class="fw-bold"
                                        style="font-size: 20px; background-color: #84b0e3; padding: 5px; font-weight:bold; color: #fff;">
                                        Average Age
                                    </h5>
                                    <div style="position: relative; display: inline-block;">
                                        <span id="txtAvgAgeShipClient"
                                            style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                                font-size: 50px; font-weight: bold; color: #84b0e3; margin-top: 20px;">0</span>
                                        <div style="
                                                margin-top: 70px;
                                                width: 135px; height: 135px; 
                                                background-color:#84b0e3;
                                                -webkit-mask-image: url('http://localhost/crewcv/assets/img/avgage.svg'); 
                                                mask-image: url('http://localhost/crewcv/assets/img/avgage.svg'); 
                                                -webkit-mask-size: contain;
                                                -webkit-mask-position: center;
                                                ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-4 shadow-sm border-0" style="border-radius: 15px;">
                                    <h5 class="fw-bold"
                                        style="font-size: 20px; background-color: #84b0e3; padding: 5px; font-weight:bold; color: #fff;">
                                        Gender
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div
                                                style="position: relative; display: inline-block; font-size: 150px; color: #84b0e3;">
                                                <span id="txtTotalMaleShipClient"
                                                    style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                                    font-size: 50px; font-weight: bold; color: #84b0e3; margin-top: 20px;">0</span>
                                                <i class="fa fa-male" style="font-size: 85px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                style="position: relative; display: inline-block; font-size: 150px; color: #84b0e3;">
                                                <span id="txtTotalFemaleShipClient"
                                                    style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                                    font-size: 50px; font-weight: bold; color: #84b0e3; margin-top: 20px;">0</span>
                                                <i class="fa fa-female" style="font-size: 85px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12" style="border-radius: 10px; padding: 15px;">
                                <h5 style="font-size: 20px; color: #000080; font-weight: bold;">Vessel Name Client</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="listKapalClient_1"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="listKapalClient_2"></div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- KAPAL MILIK -->
                <div class="col-md-6" style="margin-top: 20px;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <h5 style="font-size:20px;">Data of Own Ship:</h5>
                        <div onclick="showCheckboxes()" style="position: relative; width: 150px; border: 1px solid #ccc; padding: 10px; border-radius: 8px; 
                        background: #fff; cursor: pointer; box-shadow: 0px 2px 5px rgba(0,0,0,0.2);">
                            <select
                                style="width: 100%; border: none; background: transparent; font-size: 14px; cursor: pointer;">
                                <option readonly>- Select Vessel -</option>
                            </select>
                            <div style="position: absolute; left: 0; right: 0; top: 0; bottom: 0;"></div>
                            <div id="idCheckboxVesselOwnShip" style="display: none; border: 1px solid #ccc; border-radius: 8px; position: absolute; background: white; 
                            width: 100%; z-index: 1; padding: 10px; box-shadow: 0px 4px 8px rgba(0,0,0,0.2);">
                                <?php echo $vesselType; ?>
                            </div>
                        </div>
                        <button type="submit" onclick="searchVessel();"
                            style="width: 150px; background: #000080; color: white; padding: 10px; border: none; 
                            border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; transition: transform 0.2s, background 0.3s;"
                            onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'"
                            onmouseleave="this.style.transform='scale(1)'">
                            🔍 Search
                        </button>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-4">
                                <div class="card text-center p-4 shadow-sm border-0" style="border-radius: 15px;">
                                    <h5 class="fw-bold"
                                        style="font-size: 20px; background-color: #000080; padding: 5px; font-weight:bold; color: #fff;">
                                        Total Crew
                                    </h5>
                                    <div
                                        style="position: relative; display: inline-block; font-size: 150px; color: #000080;">
                                        <span id="txtTotalCrew"
                                            style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                            font-size: 50px; font-weight: bold; color: #000080; margin-top: 20px;">0</span>
                                        <i class='fa fa-users' style="font-size: 85px;"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-4 shadow-sm border-0" style="border-radius: 15px;">
                                    <h5 class="fw-bold"
                                        style="font-size: 20px; background-color: #000080; padding: 5px; font-weight:bold; color: #fff;">
                                        Average Age
                                    </h5>
                                    <div style="position: relative; display: inline-block;">
                                        <span id="txtAvgAge"
                                            style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                            font-size: 50px; font-weight: bold; color: #000080; margin-top: 20px;">0</span>
                                        <div style="
                                                margin-top: 70px;
                                                width: 135px; height: 135px; 
                                                background-color:#000080;
                                                -webkit-mask-image: url('http://localhost/crewcv/assets/img/avgage.svg'); 
                                                mask-image: url('http://localhost/crewcv/assets/img/avgage.svg'); 
                                                -webkit-mask-size: contain;
                                                -webkit-mask-position: center;
                                                ">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center p-4 shadow-sm border-0" style="border-radius: 15px;">
                                    <h5 class="fw-bold"
                                        style="font-size: 20px; background-color: #000080; padding: 5px; font-weight:bold; color: #fff;">
                                        Gender
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div
                                                style="position: relative; display: inline-block; font-size: 150px; color: #000080;">
                                                <span id="txtTotalMale"
                                                    style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                                    font-size: 50px; font-weight: bold; color: #000080; margin-top: 20px;">0</span>
                                                <i class="fa fa-male" style="font-size: 85px;"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                style="position: relative; display: inline-block; font-size: 150px; color: #000080;">
                                                <span id="txtTotalFemale"
                                                    style="position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%);
                                                font-size: 50px; font-weight: bold; color: #000080; margin-top: 20px;margin-right: 20px;">0</span>
                                                <i class="fa fa-female" style="font-size: 85px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12" style="border-radius: 10px; padding: 15px;">
                                <h5 style="font-size: 20px; color: #000080; font-weight: bold;">Vessel Name</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="listKapal_1"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="listKapal_2"></div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>

            </div>
            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div id="idDivHeatMap"></div>
                </div>
            </div>

            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12 text-center">
                    <h3 style="font-weight: bold;">Top 10 Schools by</h3>
                    <div style="width: 200px; margin: 10px auto;">
                        <select id="crewCategory" class="form-control">
                            <option value="Onboard">Onboard</option>
                            <option value="Onleave">Onleave</option>
                            <option value="All">All</option>
                        </select>
                    </div>
                </div>
                <div id="idDivtotalSchoolContainer" class="col-md-12">
                    <div id="idDivtotalSchool"></div>
                </div>
                <div id="comparisonContainer" class="col-md-6" style="display: none; margin-top: 10px;">
                    <div id="idDivComparison"></div>
                </div>
            </div>





            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div>
                        <div id="idDivBarChartRank">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div>
                        <div id="idDivRankContractExpiry">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<div class="modal fade" id="idModalCrewByOwnShip" tabindex="-1" role="dialog"
    aria-labelledby="idModalCrewByOwnShipLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#078415;">
                <h5 class=" modal-title" id="idModalCrewByOwnShipLabel" style="color: white;">Crew Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <legend style="text-align: left; margin-bottom: 10px;">
                    <label>Vessel: <span id="idLblModalVesselDetailByOwnShip"></span></label>
                    <br>
                    <span id="idLblVesselStatus" style="font-weight: bold;"></span>
                    <br>
                    <span id="idLblProperlyManned" style="font-weight: bold; color: green;"></span>
                </legend>
                <div class="table-responsive">
                    <table
                        class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                        <thead>
                            <tr style="background-color:#078415; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 60%;">Crew Name</th>
                                <th style="text-align: center; width: 35%;">Position</th>
                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByOwnShip"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Distribution By Institution</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table
                        class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                        <thead>
                            <tr style="background-color:#078415; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: left; width: 60%;">Crew Name & Rank / Position</th>

                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByInstitution"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModalClientShip" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Distribution By Client Ship</h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table
                        class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                        <thead>
                            <tr style="background-color:#078415; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: left; width: 60%;">Crew Name</th>
                                <th style="text-align: left; width: 35%;">Position</th>
                                <th style="text-align: left; width: 10%;">Age</th>
                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByClientShip"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModalCrewSignoff" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#078415;">
                <h5 class="modal-title" id="modalTitleCrewDetailByEstimatedSignOff" style="color: white;">Crew
                    Distribution By Estimated Sign Off
                </h5>
            </div>
            <div class="modal-body">
                <p id="idRankSummary" style="font-weight: bold; font-size: 16px; margin-bottom: 10px;"></p>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background-color: #078415; color: #FFF; height: 45px;">
                                <th style="width: 5%;">No</th>
                                <th style="width: 30%;">Crew Name</th>
                                <th style="width: 25%;">Rank Name</th>
                                <th style="width: 20%;">Sign On Date</th>
                                <th style="width: 20%;">Estimated Signoff Date</th>
                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByEstimatedSignOff"></tbody>
                        <div id="paginationInfo"></div>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReqDetail" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <button type="button" class="close" data-dismiss="modal"
                    style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
                <h4 class="modal-title" style="color:#000080;"><i>:: Crew On Board ::</i></h4>
            </div>
            <div class="modal-body" id="idModalDetail">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <legend style="text-align: left;margin-bottom:0px;">
                            <label id="lblModal">Total : <span id="idLblModal"></span></label>
                            <img id="idLoadingModal" style="display:none;"
                                src="<?php echo base_url('assets/img/loading.gif'); ?>">
                        </legend>
                        <div class="table-responsive">
                            <table
                                class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                <thead>
                                    <tr style="background-color: #16839B;color: #FFF;height:40px;">
                                        <th style="vertical-align: middle; width:3%;text-align:center;">No</th>
                                        <th style="vertical-align: middle; width:25%;text-align:center;">Vessel Name
                                        </th>
                                        <th style="vertical-align: middle; width:10%;text-align:center;">Total Crew
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="idBodyModal">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-7 col-xs-12">
                        <div style="border:1px solid black;padding:10px;">
                            <legend style="text-align: left;margin-bottom:0px;">
                                <label id="lblModal">Vessel : <span id="idLblModalVesselDetail"></span></label>
                            </legend>
                            <div class="table-responsive">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color: #16839B;color: #FFF;height:40px;">
                                            <th style="vertical-align: middle; width:3%;text-align:center;">No</th>
                                            <th style="vertical-align: middle; width:60%;text-align:center;">Crew Name
                                            </th>
                                            <th style="vertical-align: middle; width:37%;text-align:center;">Posisi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="idBodyModalCrewDetail">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTotalCrewOnLeave" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px; background-color:#078415">
                <button type=" button" class="close" data-dismiss="modal"
                    style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
                <h4 class="modal-title" style="color:#000080;"><i>:: Crew On Leave ::</i></h4>
            </div>
            <div class="modal-body" id="idModalDetail">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <legend style="text-align: left;margin-bottom:0px;">
                            <label id="lblTotalCrewOnLeave">Total : <span id="idLblModalTotalCrew"></span></label>
                            <img id="idLoadingModal" style="display:none;"
                                src="<?php echo base_url('assets/img/loading.gif'); ?>">
                        </legend>
                        <div class="table-responsive">
                            <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd;">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color:#078415; color: #FFF; height:40px;">
                                            <th style="vertical-align: middle; width:3%; text-align:center;">No</th>
                                            <th style="vertical-align: middle; width:25%; text-align:center;">Rank
                                                Name
                                            </th>
                                            <th style="vertical-align: middle; width:10%; text-align:center;">Crew
                                                Name
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="idBodyTotalCrewOnLeave">
                                    </tbody>
                                </table>
                            </div>
                            <div id="dataSummary"
                                style="margin-top: 10px; font-size: 14px; font-weight: bold; color: #16839B;"></div>
                        </div>

                    </div>
                    <div class="col-md-7 col-xs-12">
                        <div style="border:1px solid black;padding:10px;">
                            <label id="lblModalCrewNameOnLeave">Crew Name & Rank: <span
                                    id="idLblModalCrewNameOnLeave"></span></label>
                            <div class="table-responsive" style="font-size: 16px;">
                                <table
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                    <thead>
                                        <tr style="background-color:#078415; color: #FFF; height: 50%;">
                                            <th
                                                style="vertical-align: middle; width: 5%; text-align: center; padding: 10px;">
                                                No</th>
                                            <th
                                                style="vertical-align: middle; width: 60%; text-align: center; padding: 10px;">
                                                Sign off date</th>
                                            <th
                                                style="vertical-align: middle; width: 35%; text-align: center; padding: 10px;">
                                                Last Vessel</th>
                                        </tr>
                                    </thead>
                                    <tbody id="idBodyModalCrewDetailOnLeave">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalShowCrewing" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#66007A;">
                <button type="button" class="close" data-dismiss="modal"
                    style="opacity:unset;text-shadow:none;color:#FFF;">&times;</button>
                <h4 class="modal-title" style="color:#000080;"><i>:: Crew New Applicent ::</i></h4>
            </div>
            <div class="modal-body" id="idDivModalCrewDetail">
                <div class="row">
                    <div class="col-md-12 col-xs-12">
                        <div class="table-responsive">
                            <table
                                class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                                <thead>
                                    <tr style="background-color: #66007A;color: #FFF;height:40px;">
                                        <th style="vertical-align: middle; width:3%;text-align:center;">No</th>
                                        <th style="vertical-align: middle; width:50%;text-align:center;">Crew Name
                                        </th>
                                        <th style="vertical-align: middle; width:45%;text-align:center;">Apply For
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="idBodyModalCrew">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>