
<div class="container">

    <h2 class="mb-4">خلاصه وضعیت مالی</h2>

    <div class="row">

        <!-- درآمد -->
        <div class="col-md-4">
            <div class="p-3 text-center bg-success text-white rounded shadow-sm">
                <h4>کل درآمد</h4>
                <h2><?= number_format($total_income); ?> تومان</h2>
            </div>
        </div>

        <!-- هزینه -->
        <div class="col-md-4">
            <div class="p-3 text-center bg-danger text-white rounded shadow-sm">
                <h4>کل هزینه</h4>
                <h2><?= number_format($total_expense); ?> تومان</h2>
            </div>
        </div>

        <!-- موجودی -->
        <div class="col-md-4">
            <div class="p-3 text-center bg-primary text-white rounded shadow-sm">
                <h4>موجودی فعلی</h4>
                <h2><?= number_format($balance); ?> تومان</h2>
            </div>
        </div>

    </div>

</div>
<br><br>

<h3 class="mt-4">نمودار میله‌ای درآمد و هزینه ماهانه</h3>

<canvas id="incomeExpenseChart" style="max-height:350px;"></canvas>


<script>
function toPersianDigits(num) {
    return num.toString().replace(/\d/g, d => "۰۱۲۳۴۵۶۷۸۹"[d]);
}

$(document).ready(function(){

    $.get("<?= site_url('dashboard/chart_data'); ?>", function(data){

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
                            callback: function(value) {
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






