<?php
/**
 * priceCalculation tests.
 *
 * http://www.magentocommerce.com/boards/viewthread/17972/P0/
 */
include dirname(__FILE__).'/../../../../test/bootstrap/unit.php';

$t = new lime_test(7);

$prices = array(
    0.84,
    0.84
);
$tax = 1.19;

$t->is($prices[0] * $tax, 0.9996, sprintf("%f = %f", $prices[0] * $tax, 0.9996));

// calculate sum of prices and add tax
$sum = 0;
foreach ($prices as $price) {
  $sum += $price;
}
$res = $sum * $tax;
$t->is($res, 1.9992, sprintf("%f = %f", $res, 1.9992));
$t->is(number_format($res, 2), 2, sprintf("%.2f = 2.00", $res));

// calculate tax with each base price
$sum = 0;
foreach ($prices as $price) {
  $sum += $price * $tax;
}
$t->is($sum, 1.9992, sprintf("%f = %f", $sum, 1.9992));
$t->is(number_format($sum, 2), 2, sprintf("%.2f = 2.00", $sum));

$prices = array(
    0.84,
    0.94,
    0.99,
    5.55,
    0.87,
    0.84,
    0.83,
    0.32,
    100.84
);

$tax = 1.1925;

// calculate sum of prices and add tax
$sum = 0;
foreach ($prices as $price) {
  $sum += $price;
}
$t->is($sum * $tax, 133.58385, sprintf("%f = %f", $sum * $tax, 133.58385));

// calculate tax with each base price
$sum = 0;
foreach ($prices as $price) {
  $sum += $price * $tax;
}
$t->is($sum, 133.58385, sprintf("%f = %f", $sum, 133.58385));

var_dump(0.99999999999999); // output 0.9...
var_dump(0.999999999999999); // output 1

echo "(0.60-0.55)*100\n";
$no_of_time=(0.60-0.55)*100;
var_dump($no_of_time); //displays float(5)
for($i=1;$i<=$no_of_time;$i++)
{
   echo $i;
}
echo "\n";

echo "bcmul(bcsub(0.60,0.55,2),100)\n";
$no_of_time=bcmul(bcsub(0.60,0.55,2),100);
var_dump($no_of_time);  //display string(1) "5"
for($i=1;$i<=$no_of_time;$i++)
{
   echo $i;
}
echo "\n";