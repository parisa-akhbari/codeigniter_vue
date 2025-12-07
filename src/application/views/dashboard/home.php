<div id="finance-app">

    <h2 class="mb-4">خلاصه وضعیت مالی</h2>

    <div class="row">

        <!-- درآمد -->
        <div class="col-md-4">
            <div class="p-3 text-center bg-success text-white rounded shadow-sm">
                <h4>کل درآمد</h4>
                <h2>{{ format(summary.total_income) }} تومان</h2>
            </div>
        </div>

        <!-- هزینه -->
        <div class="col-md-4">
            <div class="p-3 text-center bg-danger text-white rounded shadow-sm">
                <h4>کل هزینه</h4>
                <h2>{{ format(summary.total_expense) }} تومان</h2>
            </div>
        </div>

        <!-- موجودی -->
        <div class="col-md-4">
            <div class="p-3 text-center bg-primary text-white rounded shadow-sm">
                <h4>موجودی فعلی</h4>
                <h2>{{ format(summary.balance) }} تومان</h2>
            </div>
        </div>

    </div>

    <br><br>

    <h3 class="mt-4">نمودار درآمد و هزینه ماهانه</h3>

    <canvas id="incomeExpenseChart" style="max-height:350px;"></canvas>

</div>

<script src="https://unpkg.com/vue@3"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const app = Vue.createApp({
        data() {
            return {
                summary: {
                    total_income: 0,
                    total_expense: 0,
                    balance: 0,
                }
            }
        },

        mounted() {
            this.loadSummary();
        },

        methods: {

            async loadSummary() {
                const res = await fetch("<?= site_url('dashboard/api_summary'); ?>");
                this.summary = await res.json();
            },

            format(num) {
                return new Intl.NumberFormat('fa-IR').format(num);
            }
        }

    });

    app.mount("#finance-app");

});
</script>


<!-- jQuery + Chart.js -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
function toPersianDigits(num) {
    return num.toString().replace(/\d/g, d => "۰۱۲۳۴۵۶۷۸۹"[d]);
}

$(document).ready(function () {

    $.get("<?= site_url('dashboard/chart_data'); ?>", function (data) {

        let chartData = JSON.parse(data);

        const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'درآمد',
                        data: chartData.income,
                        borderColor: 'green',
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'هزینه',
                        data: chartData.expense,
                        borderColor: 'red',
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: { font: { size: 14 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return toPersianDigits(value);
                            }
                        }
                    }
                }
            }
        });

    });

});
</script>
