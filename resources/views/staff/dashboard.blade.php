@extends('staff.master_layout')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@section('staff-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Dashbaord') }}</h1>
            </div>

            @php
                $monthsArray = [
                    1 => __('January'),
                    2 => __('February'),
                    3 => __('March'),
                    4 => __('April'),
                    5 => __('May'),
                    6 => __('June'),
                    7 => __('July'),
                    8 => __('August'),
                    9 => __('September'),
                    10 => __('October'),
                    11 => __('November'),
                    12 => __('December'),
                ];
            @endphp

            <div class="section-body">
    <!-- Loy Portal - Staff Dashboard Cards -->
  <div class="row">
    <!-- Total Shops Card -->
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card" style="background: rgb(93, 120, 238); border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(93, 120, 238, 0.3);">
            <div style="display: flex; flex-direction: column;">
                <span style="color: rgba(255,255,255,0.8); font-size: 14px; font-weight: 500; margin-bottom: 10px;">Total Shops</span>
                <span style="color: white; font-size: 42px; font-weight: 700; line-height: 1.2;">24</span>
            </div>
        </div>
    </div>
    
    <!-- Visited Today Card -->
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card" style="background: rgb(93, 120, 238); border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(93, 120, 238, 0.3);">
            <div style="display: flex; flex-direction: column;">
                <span style="color: rgba(255,255,255,0.8); font-size: 14px; font-weight: 500; margin-bottom: 10px;">Visited Today</span>
                <span style="color: white; font-size: 42px; font-weight: 700; line-height: 1.2;">6</span>
            </div>
        </div>
    </div>
    
    <!-- Pending Visits Card -->
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card" style="background: rgb(93, 120, 238); border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(93, 120, 238, 0.3);">
            <div style="display: flex; flex-direction: column;">
                <span style="color: rgba(255,255,255,0.8); font-size: 14px; font-weight: 500; margin-bottom: 10px;">Pending Visits</span>
                <span style="color: white; font-size: 42px; font-weight: 700; line-height: 1.2;">18</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="row mt-4">
    <div class="col-12">
        <h4 style="margin-bottom: 20px; font-weight: 600; color: #34395e;">Quick Actions</h4>
    </div>
    
    <!-- Add Shop Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s; hover:transform: translateY(-5px); box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center;">
                <div style="background: rgb(93, 120, 238); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <i class="fas fa-plus" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Add Shop</h5>
                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">Register a new shop</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Shop List Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s; hover:transform: translateY(-5px); box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center;">
                <div style="background: rgb(93, 120, 238); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <i class="fas fa-list" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Shop List</h5>
                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">View all shops</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Navigation Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s; hover:transform: translateY(-5px); box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center;">
                <div style="background: rgb(93, 120, 238); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <i class="fas fa-map-marker-alt" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Navigation</h5>
                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">Find nearby shops</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Visit History Card -->
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s; hover:transform: translateY(-5px); box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
            <div style="display: flex; align-items: center;">
                <div style="background: rgb(93, 120, 238); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                    <i class="fas fa-history" style="color: white; font-size: 20px;"></i>
                </div>
                <div>
                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Visit History</h5>
                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">View visit records</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('website/js/chart.min.js') }}"></script>

    <script>
        var balance_chart = document.getElementById("balance-chart").getContext('2d');

        var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
        balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
        balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

        var myChart = new Chart(balance_chart, {
            type: 'line',
            data: {
                labels: @json($cardData['sellChartLabels']),
                datasets: [{
                    label: '{{ __('Balance') }}',
                    data: @json($cardData['sellChartValues']),
                    backgroundColor: balance_chart_bg_color,
                    borderWidth: 3,
                    borderColor: 'rgba(63,82,227,1)',
                    pointBorderWidth: 0,
                    pointBorderColor: 'transparent',
                    pointRadius: 3,
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: 'rgba(63,82,227,1)',
                }]
            },
            options: {
                layout: {
                    padding: {
                        bottom: -1,
                        left: -1
                    }
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            display: false,
                        },
                        ticks: {
                            display: false
                        }
                    }]
                },
            }
        });

        var sales_chart = document.getElementById("sales-chart").getContext('2d');

        var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
        balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
        balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

        var myChart = new Chart(sales_chart, {
            type: 'line',
            data: {
                labels: @json($cardData['salesChartLabels']),
                datasets: [{
                    label: 'Sales',
                    data: @json($cardData['salesChartValues']),
                    borderWidth: 2,
                    backgroundColor: balance_chart_bg_color,
                    borderWidth: 3,
                    borderColor: 'rgba(63,82,227,1)',
                    pointBorderWidth: 0,
                    pointBorderColor: 'transparent',
                    pointRadius: 3,
                    pointBackgroundColor: 'transparent',
                    pointHoverBackgroundColor: 'rgba(63,82,227,1)',
                }]
            },
            options: {
                layout: {
                    padding: {
                        bottom: -1,
                        left: -1
                    }
                },
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            beginAtZero: true,
                            display: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            drawBorder: false,
                            display: false,
                        },
                        ticks: {
                            display: false
                        }
                    }]
                },
            }
        });
    </script>
@endpush
