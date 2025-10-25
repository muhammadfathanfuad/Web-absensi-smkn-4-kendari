import ApexCharts from "apexcharts";

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
    return ["#10b981", "#f59e0b", "#ef4444", "#8b5cf6", "#06b6d4"];
}

// Chart Winrate Harian (Bar Chart) - untuk remaja
const winrateChartEl = document.getElementById("winrateChart");
if (winrateChartEl && typeof winrateData !== "undefined") {
    const colors = getChartColorsArray("winrateChart");
    const options = {
        series: [
            {
                name: "Winrate",
                data: winrateData,
            },
        ],
        chart: {
            height: 200,
            type: "bar",
            toolbar: {
                show: false,
            },
            animations: {
                enabled: true,
                easing: "easeinout",
                speed: 800,
            },
        },
        colors: colors,
        plotOptions: {
            bar: {
                borderRadius: 8,
                horizontal: false,
                columnWidth: "60%",
            },
        },
        dataLabels: {
            enabled: false,
        },
        xaxis: {
            categories: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
            labels: {
                style: {
                    colors: "#6b7280",
                    fontSize: "12px",
                    fontWeight: 500,
                },
            },
        },
        yaxis: {
            title: {
                text: "Winrate (%)",
                style: {
                    color: "#6b7280",
                    fontSize: "12px",
                },
            },
            labels: {
                style: {
                    colors: "#6b7280",
                    fontSize: "12px",
                },
            },
            min: 0,
            max: 100,
        },
        grid: {
            borderColor: "#f3f4f6",
            strokeDashArray: 3,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + "% Winrate";
                },
            },
            style: {
                fontSize: "12px",
            },
        },
        fill: {
            type: "gradient",
            gradient: {
                shade: "light",
                type: "vertical",
                shadeIntensity: 0.5,
                gradientToColors: [
                    "#10b981",
                    "#f59e0b",
                    "#ef4444",
                    "#8b5cf6",
                    "#06b6d4",
                ],
                inverseColors: false,
                opacityFrom: 0.8,
                opacityTo: 0.3,
            },
        },
    };

    const chart = new ApexCharts(winrateChartEl, options);
    chart.render();
}
