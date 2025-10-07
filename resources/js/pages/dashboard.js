import ApexCharts from 'apexcharts';

// Fungsi untuk mendapatkan warna tema dari atribut HTML
function getChartColorsArray(chartId) {
    const chartElement = document.getElementById(chartId);
    if (chartElement) {
        const colors = chartElement.dataset.colors;
        if (colors) {
            return JSON.parse(colors);
        }
    }
    // Warna default jika tidak ada yang diset
    return ['#3498db', '#2ecc71', '#e74c3c', '#f1c40f', '#9b59b6'];
}

// 1. Chart Jam Mengajar Hari Ini (Radial Bar)
const jamMengajarChartEl = document.getElementById('jamMengajarChart');
if (jamMengajarChartEl) {
    const colors = getChartColorsArray('jamMengajarChart');
    const options = {
        series: [50], // Persentase dari controller (contoh 50%)
        chart: {
            height: 250,
            type: 'radialBar',
        },
        plotOptions: {
            radialBar: {
                hollow: {
                    size: '70%',
                },
                dataLabels: {
                    name: {
                        show: false,
                    },
                    value: {
                        fontSize: '24px',
                        fontWeight: 'bold',
                        offsetY: 10,
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            },
        },
        colors: colors,
        stroke: {
            lineCap: 'round'
        },
        labels: ['Selesai'],
    };

    const chart = new ApexCharts(jamMengajarChartEl, options);
    chart.render();
}


// 2. Chart Riwayat Mengajar Bulan Ini (Area Chart)
const riwayatMengajarChartEl = document.getElementById('riwayatMengajarChart');
if (riwayatMengajarChartEl) {
    const colors = getChartColorsArray('riwayatMengajarChart');
    const options = {
        series: [{
            name: 'Total Jam Mengajar',
            data: [18, 20, 15, 22] // Data dari controller
        }],
        chart: {
            height: 300,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2,
        },
        colors: colors,
        xaxis: {
            categories: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'], // Kategori dari controller
        },
        yaxis: {
            title: {
                text: 'Jumlah Jam'
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100]
            }
        },
        tooltip: {
            x: {
                format: 'dd/MM/yy HH:mm'
            },
        },
    };

    const chart = new ApexCharts(riwayatMengajarChartEl, options);
    chart.render();
}

// 3. Chart Statistik Kehadiran Siswa (Donut Chart)
const statistikKehadiranChartEl = document.getElementById('statistikKehadiranChart');
if (statistikKehadiranChartEl) {
    const colors = getChartColorsArray('statistikKehadiranChart');
    const options = {
        series: [350, 45, 15, 5], // Data dari controller (Hadir, Sakit, Izin, Alpha)
        chart: {
            height: 250,
            type: 'donut',
        },
        labels: ['Hadir', 'Sakit', 'Izin', 'Alpha'],
        colors: colors,
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const chart = new ApexCharts(statistikKehadiranChartEl, options);
    chart.render();
}