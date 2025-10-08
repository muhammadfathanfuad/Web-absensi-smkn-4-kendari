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

// 1. Chart Jam Mengajar Hari Ini (Radial Bar) - DINAMIS
const jamMengajarChartEl = document.getElementById('jamMengajarChart');
if (jamMengajarChartEl && typeof jamMengajarData !== 'undefined') { // Pastikan variabel ada
    const colors = getChartColorsArray('jamMengajarChart');
    const options = {
        // Menggunakan persentase dari controller
        series: [jamMengajarData.persentase],
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


// 2. Chart Riwayat Mengajar Bulan Ini (Area Chart) - DINAMIS
const riwayatMengajarChartEl = document.getElementById('riwayatMengajarChart');
if (riwayatMengajarChartEl && typeof riwayatMengajarData !== 'undefined') { // Pastikan variabel ada
    const colors = getChartColorsArray('riwayatMengajarChart');
    const options = {
        // Menggunakan data series dari controller
        series: riwayatMengajarData.series,
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
            // Menggunakan kategori dari controller
            categories: riwayatMengajarData.categories,
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

// 3. Chart Statistik Kehadiran Siswa (Donut Chart) - DINAMIS
const statistikKehadiranChartEl = document.getElementById('statistikKehadiranChart');
if (statistikKehadiranChartEl && typeof statistikKehadiranData !== 'undefined') { // Pastikan variabel ada
    const colors = getChartColorsArray('statistikKehadiranChart');
    const options = {
        // Menggunakan data series dari controller
        series: statistikKehadiranData.series,
        chart: {
            height: 250,
            type: 'donut',
        },
        // Menggunakan labels dari controller
        labels: statistikKehadiranData.labels,
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