<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Constants based on provided data
$tarif_rate_per_1000L = 2.67; // Euro per 1000 liters
$rate_per_1000L_daily = 0.27; // Euro per 1000 liters daily
$cubic_litres_euro = 5.07; // Euro per cubic liter
$litres_1000_litres = 1.69; // Conversion factor from 1000 liters to liters

// Initialize variables
$monthly_bill_euro = 0;
$annual_bill_euro = 0;
$annual_savings_euro_with_smart_sink = 0;
$carbon_emissions_savings_kg = 0;

// Get user inputs
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $daily_water_usage_litres = floatval($_POST["daily_water_usage"]);
    $num_of_persons = intval($_POST["num_of_persons"]);
    $smart_sink_savings_percent = 0.30; // 30% savings with smart sink

    // Calculate Monthly and Annual Usage
    $monthly_water_usage_litres = $daily_water_usage_litres * 30 * $num_of_persons;
    $annual_water_usage_litres = $monthly_water_usage_litres * 12;

    // Calculate Monthly and Annual Billings
    $monthly_bill_euro = ($monthly_water_usage_litres / 1000) * $tarif_rate_per_1000L + $rate_per_1000L_daily;
    $annual_bill_euro = $monthly_bill_euro * 12;

    // Calculate Savings
    $net_flush_litres = $annual_water_usage_litres - (1000 * $num_of_persons); // Assuming 1000 liters is the base demand per person
    $net_flush_savings_euro = ($net_flush_litres / 1000) * $cubic_litres_euro;
    $annual_savings_euro = $annual_bill_euro - $net_flush_savings_euro;

    // Apply smart sink savings
    $annual_savings_euro_with_smart_sink = $annual_savings_euro * $smart_sink_savings_percent;

    // Calculate CO2 emissions savings
    $carbon_emissions_savings_kg = $annual_savings_euro_with_smart_sink / 1000;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Water Usage Calculator</title>
</head>
<body>
    <h1>Water Usage Calculator</h1>
    <form method="POST">
        <label for="daily_water_usage">Enter daily water usage in litres:</label>
        <input type="number" name="daily_water_usage" required><br>
        <label for="num_of_persons">Enter the number of persons:</label>
        <input type="number" name="num_of_persons" required><br>
        <input type="submit" value="Calculate">
    </form>

    <!-- Display results here -->
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <h2>Results:</h2>
        <p>Monthly Bill (Euro): €<?php echo number_format($monthly_bill_euro, 2); ?></p>
        <p>Annual Bill (Euro): €<?php echo number_format($annual_bill_euro, 2); ?></p>
        <p>Annual Savings (Euro) with Smart Sink: €<?php echo number_format($annual_savings_euro_with_smart_sink, 2); ?></p>
        <p>Carbon Emissions Savings (kgCO₂eqkwh) / year: <?php echo number_format($carbon_emissions_savings_kg, 2); ?> kg CO2</p>
    <?php endif; ?>
</body>
</html>

