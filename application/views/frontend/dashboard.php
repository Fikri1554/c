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
            url: '<?php echo base_url('dashboard/crewPieChart'); ?>', // URL ke fungsi PHP
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Highcharts.chart('idDivOverall', {
                    chart: {
                        type: 'pie',
                        backgroundColor: null
                    },
                    title: {
                        text: 'Crew Distribution by Company'
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.y} Crew</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b>: {point.y}',
                                style: {
                                    fontSize: '14px',
                                    textOutline: 'none',
                                    color: 'black'
                                }
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    legend: {
                        enabled: true
                    },
                    series: [{
                        name: 'Jumlah Kru',
                        colorByPoint: true,
                        data: data
                    }]
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                alert('Gagal mengambil data kru. Silakan coba lagi.');
            }
        });
    });


    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/contractBarChart'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                // Mengelompokkan data berdasarkan bulan
                const groupedData = {};
                data.forEach(item => {
                    const [year, month] = item.month.split('-');
                    const monthName = monthNames[parseInt(month) - 1];

                    if (!groupedData[monthName]) {
                        groupedData[monthName] = {
                            totalCrew: 0,
                            crewDetails: []
                        };
                    }

                    groupedData[monthName].totalCrew += item.total_crew;
                    groupedData[monthName].crewDetails.push({
                        crew_name: item.crew_name,
                        estimated_signoff_date: item.estimated_signoff_date
                    });
                });

                const months = Object.keys(groupedData);
                const crewCounts = months.map(month => groupedData[month].totalCrew);


                Highcharts.chart('idDivBarChartRank', {
                    chart: {
                        type: 'column',
                        backgroundColor: null,
                        height: 800,
                        width: 1100
                    },
                    title: {
                        text: 'Crew Contract Expiry: Monthly Distribution in 2025',
                        style: {
                            fontSize: '20px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    },
                    xAxis: {
                        categories: months,
                        crosshair: true,
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '12px',
                                color: '#333'
                            }
                        },
                        accessibility: {
                            description: 'Months'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Number of Crew',
                            style: {
                                fontSize: '16px',
                                fontWeight: 'bold',
                                color: '#333'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '12px',
                                color: '#333'
                            }
                        }
                    },
                    tooltip: {
                        valueSuffix: ' crew members',
                        style: {
                            fontSize: '12px',
                            color: '#333'
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.1,
                            groupPadding: 0.1,
                            borderWidth: 0,
                            cursor: 'pointer',
                            events: {
                                click: function(event) {
                                    const index = Math.round(event.point
                                        .index);
                                    const selectedMonth = months[index];
                                    const crewDetails = groupedData[selectedMonth]
                                        .crewDetails;


                                    let modalBody = '';
                                    crewDetails.forEach((item, i) => {
                                        modalBody += `
                                        <tr>
                                            <td style="text-align: center;">${i + 1}</td>
                                            <td style="text-align: left;">${item.crew_name}</td>
                                            <td style="text-align: center;">${item.estimated_signoff_date}</td>
                                        </tr>`;
                                    });

                                    // Update isi modal dan tampilkan
                                    $('#idBodyModalCrewDetailByEstimatedSignOff').html(
                                        modalBody);
                                    $('#detailModalCrewSignoff').modal('show');
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '10px',
                                fontWeight: 'bold',
                                color: '#333'
                            },
                            formatter: function() {
                                return this.y;
                            }
                        }
                    },
                    series: [{
                        name: 'Crew Count',
                        data: crewCounts,
                        color: '#007bff'
                    }],
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom',
                        itemStyle: {
                            fontSize: '14px',
                            fontWeight: 'normal',
                            color: '#333',
                            textDecoration: 'none'
                        },
                        itemHoverStyle: {
                            color: '#333'
                        },
                        useHTML: false
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: true
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching contractBarChart data:', error);
            }
        });
    });


    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url("dashboard/shipDemograph"); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Highcharts.chart('idTotalCrewByKapal', {
                    chart: {
                        type: 'bar',
                        backgroundColor: null,
                        height: 800,
                        width: 500
                    },
                    title: {
                        text: 'Crew Distribution by Ship',
                        align: 'center',
                        style: {
                            fontSize: '18px',
                            color: 'black'
                        }
                    },
                    xAxis: {
                        categories: data.map(item => item.nama_kapal),
                        title: {
                            text: 'Nama Kapal',
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Jumlah Crew / Umur',
                            style: {
                                fontSize: '15px',
                                fontWeight: 'bold'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '15px'
                            }
                        }
                    },
                    series: [{
                            name: 'Jumlah Crew Onboard',
                            data: data.map(item => item.jumlah_crew_onboard),
                            color: '#007bff'
                        },
                        {
                            name: 'Male',
                            data: data.map(item => item.total_male),
                            color: '#28a745'
                        },
                        {
                            name: 'Female',
                            data: data.map(item => item.total_female),
                            color: '#dc3545'
                        },
                        {
                            name: 'Rata-rata Umur',
                            data: data.map(item => item.rata_rata_umur),
                            color: '#fd7e14'
                        }
                    ],
                    plotOptions: {
                        series: {
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '15px',
                                    color: '#fff'
                                }
                            },
                            pointWidth: 10,
                            point: {
                                events: {
                                    click: function() {
                                        const vslCode = data[this.index].kode_kapal;
                                        const vesselName = this.category;
                                        const status = data[this.index].status;

                                        $("#idLblModalVesselDetailByOwnShip").text(
                                            vesselName);
                                        $("#idLblVesselStatus")
                                            .text(`Status: ${status}`)
                                            .css("color", status === "Properly Manned" ?
                                                "green" : "red");

                                        if (status === "Properly Manned") {
                                            $("#idLblVesselStatus").append(
                                                '<br><span style="font-size: 14px; color: green;">The vessel meets the minimum crew number requirements for safe sailing (≥ 22 crew).</span>'
                                            );
                                        }

                                        $.ajax({
                                            url: '<?php echo base_url("dashboard/getDetailCrewOnBoard"); ?>',
                                            method: 'POST',
                                            data: {
                                                vslCode: vslCode
                                            },
                                            dataType: 'json',
                                            success: function(response) {
                                                $("#idBodyModalCrewDetailByOwnShip")
                                                    .html(response.trNya);
                                                $("#idModalCrewByOwnShip")
                                                    .modal('show');
                                            },
                                            error: function() {
                                                alert(
                                                    "Failed to load crew details. Please try again."
                                                );
                                            }
                                        });
                                    }
                                }
                            }

                        }
                    },
                    bar: {
                        groupPadding: 0.1 // Tambahkan jarak antar bar
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: true
                    }
                });
            },
            error: function() {
                alert("Failed to load data.");
            }
        });
    });

    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url("dashboard/getSchool"); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const categories = data.map(item => item.school);
                const seriesDataTotal = data.map(item => ({
                    y: item.total_crew
                }));
                const seriesDataOnboard = data.map(item => item.onboard_crew);
                const seriesDataOnleave = data.map(item => item.onleave_crew);

                Highcharts.chart('idDivtotalSchool', {
                    chart: {
                        type: 'bar',
                        backgroundColor: null,
                        height: 800
                    },
                    title: {
                        text: 'Distribution Crew by School',
                        style: {
                            fontSize: '18px',
                            fontWeight: 'bold'
                        }
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Nama Sekolah',
                            style: {
                                fontSize: '14px'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '14px'
                            }
                        },
                        gridLineWidth: 1,
                        lineWidth: 0
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Jumlah Crew',
                            align: 'high',
                            style: {
                                fontSize: '14px'
                            }
                        },
                        labels: {
                            style: {
                                fontSize: '14px'
                            }
                        },
                        gridLineWidth: 0
                    },
                    tooltip: {
                        formatter: function() {
                            const schoolData = data[this.point.index];
                            return `
                            <b>${schoolData.school}</b><br>
                            Total Crew: ${schoolData.total_crew}<br>
                            Onboard: ${schoolData.onboard_crew}<br>
                            Onleave: ${schoolData.onleave_crew}
                        `;
                        },
                        style: {
                            fontSize: '12px'
                        }
                    },
                    plotOptions: {
                        bar: {
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '12px'
                                }
                            },
                            groupPadding: 0.1,
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function() {
                                        const index = this.index;
                                        const seriesName = this.series.name;
                                        const school = categories[index];
                                        let crewDetails = [];
                                        let modalColor = '#16839B'; // Default color

                                        if (seriesName === 'Total Crew') {
                                            crewDetails = data[index].crew_names.split(
                                                ', ');
                                            modalColor = '#16839B'; // Green
                                        } else if (seriesName === 'Onboard') {
                                            crewDetails = data[index]
                                                .onboard_crew_names ?
                                                data[index].onboard_crew_names.split(
                                                    ', ') : [];
                                            modalColor = '#5DADE2'; // Blue
                                        } else if (seriesName === 'Onleave') {
                                            crewDetails = data[index]
                                                .onleave_crew_names ?
                                                data[index].onleave_crew_names.split(
                                                    ', ') : [];
                                            modalColor = '#F5B041'; // Orange
                                        }

                                        // Update modal title and header background color
                                        $('#modalTitle').text(
                                            `Detail Crew for ${school} (${seriesName})`
                                        );
                                        $('.modal-header').css('background-color',
                                            modalColor);

                                        // Populate modal table body
                                        const tbody = $(
                                            '#idBodyModalCrewDetailByInstitution');
                                        tbody.empty();
                                        if (crewDetails.length > 0) {
                                            crewDetails.forEach((crew, i) => {
                                                tbody.append(`
                                                    <tr>
                                                        <td style="text-align: center;">${i + 1}</td>
                                                        <td>${crew}</td>
                                                    </tr>
                                                `);
                                            });
                                        } else {
                                            tbody.append(`
                                                <tr>
                                                    <td colspan="2" style="text-align: center;">No crew data available.</td>
                                                </tr>
                                            `);
                                        }
                                        // Show modal
                                        $('#detailModal').modal('show');
                                    }
                                }
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                            name: 'Total Crew',
                            data: seriesDataTotal,
                            color: '#16839B' // Green
                        },
                        {
                            name: 'Onboard',
                            data: seriesDataOnboard,
                            color: '#5DADE2' // Blue
                        },
                        {
                            name: 'Onleave',
                            data: seriesDataOnleave,
                            color: '#F5B041' // Orange
                        }
                    ]
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', xhr.responseText || error);
                alert(`Failed to load data: ${xhr.status} - ${xhr.statusText}`);
            }
        });
    });



    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/getCadangan'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const heatmapData = data.map((item, index) => {
                    var color;
                    if (item.total_onleave > 15) {
                        color = 'rgba(72, 239, 128, 0.8)';
                    } else if (item.total_onleave >= 11) {
                        color = 'rgba(255, 223, 107, 0.8)';
                    } else {
                        color = 'rgba(255, 107, 107, 0.8)';
                    }
                    return {
                        x: index % 5,
                        y: Math.floor(index / 5),
                        value: item.total_onleave,
                        onboard: item.total_onboard,
                        rank: item.rank,
                        color: color || item.color,
                    };
                });

                Highcharts.chart('idDivHeatMap', {
                    chart: {
                        type: 'heatmap',
                        plotBorderWidth: 1,
                        height: 500,
                        backgroundColor: null,
                    },
                    title: {
                        text: 'Cadangan Kapal per Rank (Heatmap)',
                        align: 'center',
                    },
                    xAxis: {
                        labels: {
                            enabled: false,
                        },
                        title: null,
                    },
                    yAxis: {
                        labels: {
                            enabled: false,
                        },
                        title: null,
                    },
                    colorAxis: {
                        stops: [
                            [0, 'rgba(255, 107, 107, 0.8)'],
                            [0.5, 'rgba(255, 223, 107, 0.8)'],
                            [1, 'rgba(72, 239, 128, 0.8)'],
                        ],
                        min: 0,
                        max: 20,
                    },
                    tooltip: {
                        formatter: function() {
                            var categoryLabel = '';
                            if (this.point.value > 15) {
                                categoryLabel = 'Strong';
                            } else if (this.point.value >= 11) {
                                categoryLabel = 'Medium';
                            } else {
                                categoryLabel = 'Low';
                            }

                            return `
                            Crew On Leave: ${this.point.value}<br>
                            Crew Onboard: ${this.point.onboard}<br>
                            Category: <b>${categoryLabel}</b>`;
                        },
                    },
                    series: [{
                        name: 'Cadangan Kapal',
                        borderWidth: 1,
                        data: heatmapData.map(item => ({
                            x: item.x,
                            y: item.y,
                            value: item.value,
                            onboard: item
                                .onboard,
                            rank: item.rank,
                            color: item.color,
                        })),
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return this.point.rank;
                            },
                            color: '#fff',
                            style: {
                                fontSize: '12px',
                            },
                        },
                    }],
                    credits: {
                        enabled: false,
                    },
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', status, error);
            },
        });
    });
    </script>
</head>

<body>
    <div class="container" style="background-color:;">
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
                        style="background-color:#16839B;color:#FFFFFF;border:2px solid #000000;cursor:pointer;border-radius:30px;"
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
                        style="background-color:#078415;color:#FFFFFF;border:2px solid #000000;cursor: pointer;border-radius:30px;"
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
                <!-- <div class="col-lg-2 col-6">
                    <div class="panel-heading"
                        style="background-color:#E47100;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
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
                        style="background-color:#C80000;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
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
                        style="background-color:#66007A;color:#FFFFFF;border:2px solid #000000;border-radius:30px;cursor:pointer;"
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
                        style="background-color:#7A1A00;color:#FFFFFF;border:2px solid #000000;border-radius:30px;">
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
                </div> -->
            </div>
            <div class="row">
                <div class="col-md-6" style="margin-top: 12px;">
                    <div id="idDivOverall">
                    </div>
                </div>
                <div class="col-md-6" style="margin-top: 12px;">
                    <div id="idTotalCrewByKapal">

                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div id="idDivHeatMap"></div>
                </div>
            </div>

            <div class="row" style="margin-top: 12px;">
                <div class="col-md-12">
                    <div id="idDivtotalSchool"></div>
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
        </div>
    </div>
</body>

</html>

<div class="modal fade" id="idModalCrewByOwnShip" tabindex="-1" role="dialog"
    aria-labelledby="idModalCrewByOwnShipLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
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
                            <tr style="background-color: #16839B; color: #FFF; height: 40px;">
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
                            <tr style="background-color: #16839B; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 60%;">Crew Name</th>

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

<div class="modal fade" id="detailModalCrewSignoff" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Distribution By Estimated Sign Off
                </h5>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table
                        class="table table-border table-striped table-bordered table-condensed table-advance table-hover">
                        <thead>
                            <tr style="background-color: #16839B; color: #FFF; height: 40px;">
                                <th style="text-align: center; width: 5%;">No</th>
                                <th style="text-align: center; width: 60%;">Crew Name</th>
                                <th style="text-align: center; width: 60%;">Estimated Signoff Date</th>
                            </tr>
                        </thead>
                        <tbody id="idBodyModalCrewDetailByEstimatedSignOff"></tbody>
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
                <h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew On Board ::</i></h4>
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
                                        <th style="vertical-align: middle; width:10%;text-align:center;">Total Crew</th>
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
                <h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew On Leave ::</i></h4>
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
                                            <th style="vertical-align: middle; width:25%; text-align:center;">Rank Name
                                            </th>
                                            <th style="vertical-align: middle; width:10%; text-align:center;">Crew Name
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
                <h4 class="modal-title" style="color:#FFFFFF;"><i>:: Crew New Applicent ::</i></h4>
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
                                        <th style="vertical-align: middle; width:50%;text-align:center;">Crew Name</th>
                                        <th style="vertical-align: middle; width:45%;text-align:center;">Apply For</th>
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