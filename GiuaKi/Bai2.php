
<?php
$prices = [100, 200, 150, 300, 250];

function calculateTotal($arr) {
    $total = 0;
    foreach ($arr as $price) {
        $total += $price;
    }
    return $total;
}


function findMaxPrice($arr) {
    $max = $arr[0];
    foreach ($arr as $price) {
        if ($price > $max) {
            $max = $price;
        }
    }
    return $max;
}


$total = calculateTotal($prices);
$maxPrice = findMaxPrice($prices);

echo "Tổng giá trị sản phẩm: " . $total . "\n";
if ($maxPrice !== null){
    echo "Giá sản phẩm cao nhất :" .$maxPrice ;
} else{
    echo "Không có sản phẩm nào";
}
?>
