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
                loadCadanganChart();
            } else {
                alert("Gagal memuat data. Silakan coba lagi.");
            }
            $("#idLoading").hide();
        }, "json").fail(function() {
            alert("Terjadi kesalahan. Silakan coba lagi.");
            $("#idLoading").hide();
        });
    }

    function loadCadanganChart() {
        $.getJSON('<?php echo base_url("dashboard/getCadanganData"); ?>', function(data) {

            var filteredData = data.filter(item => parseInt(item.cadangan) > 0);

            var labels = filteredData.map(item => item.nmrank);
            var values = filteredData.map(item => parseInt(item.cadangan));

            var backgroundColors = values.map(value => value >= 18 ? 'rgba(0, 128, 0, 0.7)' :
                'rgba(255, 0, 0, 0.7)');
            var borderColors = values.map(value => value >= 18 ? 'rgba(0, 128, 0, 1)' : 'rgba(255, 0, 0, 1)');

            var ctx = document.getElementById('cadanganBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    aspectRatio: 1,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'start',
                            color: '#000',
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                return value;
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    layout: {
                        padding: 10
                    }
                },
                plugins: [ChartDataLabels]
            });

            var noteContainer = document.getElementById('chartNote');
            if (noteContainer) {
                noteContainer.innerHTML = `
                <p>
                    <span style="color: rgba(255, 0, 0, 1); font-weight: bold;">Merah:</span>
                    Jumlah orang yang kurang dicadangkan.
                </p>
                <p>
                    <span style="color: rgba(0, 128, 0, 1); font-weight: bold;">Hijau:</span>
                    Jumlah orang yang melebihi atau siap dicadangkan.
                </p>
            `;
            }
        }).fail(function() {
            alert("Gagal memuat data cadangan.");
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
            url: '<?php echo base_url('dashboard/crewPieChart'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Highcharts.chart('idDivOverall', {
                    chart: {
                        type: 'pie',
                        backgroundColor: null,
                        height: 500,
                        width: 500
                    },
                    title: {
                        text: 'Crew Distribution: Onboard vs Onleave',
                        style: {
                            fontSize: '15px',
                        }
                    },
                    subtitle: {
                        text: null
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    accessibility: {
                        point: {
                            valueSuffix: '%'
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                distance: 30,
                                format: '{point.name}: {point.y}',
                                style: {
                                    fontSize: '13px',
                                    color: 'black',
                                },
                                connectorShape: 'crookedLine',
                                connectorWidth: 1.5,
                                connectorColor: '#333'
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: [{
                        name: 'Jumlah Kru',
                        colorByPoint: true,
                        data: [{
                                name: 'Onboard',
                                y: data.onboard,
                                sliced: true,
                                selected: true
                            },
                            {
                                name: 'Onleave',
                                y: data.onleave
                            }
                        ]
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
            url: '<?php echo base_url('dashboard/rankBarChart'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                var rank = data.map(item => item.rank_name);
                var onboardData = data.map(item => item.total_onboard);
                var onleaveData = data.map(item => item.total_onleave);


                Highcharts.chart('idDivBarChartRank', {
                    chart: {
                        type: 'column',
                        backgroundColor: null,
                        height: 800,
                        width: 1100
                    },
                    title: {
                        text: 'Crew Distribution: Onboard vs Onleave by Rank',
                        style: {
                            fontSize: '20px',
                            fontWeight: 'bold',
                            color: '#333'
                        }
                    },
                    xAxis: {
                        categories: rank,
                        crosshair: true,
                        labels: {
                            rotation: -45,
                            style: {
                                fontSize: '12px',
                                color: '#333'
                            }
                        },
                        accessibility: {
                            description: 'Ranks'
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
                        valueSuffix: ' people',
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
                        }
                    },
                    series: [{
                            name: 'Onboard',
                            data: onboardData,
                            color: '#007bff'
                        },
                        {
                            name: 'Onleave',
                            data: onleaveData,
                            color: '#28a745'
                        }
                    ],
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
                console.error('Error fetching rankBarChart data:', error);
            }
        });
    });

    $(document).ready(function() {
        $.ajax({
            url: '<?php echo base_url('dashboard/shipDemograph'); ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Highcharts.chart('idTotalCrewByKapal', {
                    chart: {
                        type: 'bar',
                        backgroundColor: null,
                        height: 500,
                        width: 800
                    },
                    title: {
                        text: 'Crew Distribution by Ship',
                        align: 'center',
                        style: {
                            fontSize: '18px',
                            fontWeight: 'bold'
                        }
                    },
                    xAxis: {
                        categories: data.categories,
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
                    yAxis: [{
                        min: 0,
                        title: {
                            text: 'Jumlah Crew',
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
                    }, {
                        opposite: true,
                        title: {
                            text: 'Rata-rata Umur',
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
                    }],
                    legend: {
                        reversed: false,
                        itemStyle: {
                            fontSize: '12px'
                        }
                    },
                    plotOptions: {
                        series: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '12px',
                                    fontWeight: 'normal'
                                }
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: data.series
                });
            },
            error: function(xhr, status, error) {
                console.error('Error fetching data:', error);
                $('#idTotalCrewByKapal').html('<p>Unable to load data.</p>');
            }
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
                <div class="col-lg-2 col-6">
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
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" style="margin-top: 10px;">
                    <div id="idDivOverall">
                    </div>
                </div>
                <div class="col-md-6" style="margin-top: 10px;  ">
                    <div id="idTotalCrewByKapal">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-6">

                </div>
            </div>
            <div class="row">
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
            <div class="modal-header" style="padding: 10px;background-color:#078415;">
                <button type="button" class="close" data-dismiss="modal"
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
                                        <tr style="background-color:#078415;; color: #FFF; height: 50%;">
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
                    <br>
                    <div id="chartContainer" style="margin-top: 10px; text-align: center;">
                        <h4 style="margin-bottom: 10px;">Cadangan Chart</h4>
                        <canvas id="cadanganBarChart"
                            style="width: 100%; max-width: 200px; height: auto; max-height: 200px;">
                        </canvas>
                        <div id="chartNote" style="margin-top: 15px; font-size: 14px;"></div>
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