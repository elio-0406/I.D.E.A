<?php
session_start();
require("../util/dataProvider.php");
$dp = new DataProvider();
switch ($_SERVER["REQUEST_METHOD"]) {
    case 'GET':
        switch ($_GET['action']) {
            case 'getQuantity':
                $productID = $_GET['productID'];
                $colorID = $_GET['colorID'];
                $sql = "SELECT soLuong FROM sanpham WHERE  idSanPham= " . $productID." and  idMau=".$colorID;
                $result = $dp->excuteQuery($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo $row['soLuong'];
                } else {
                    echo "error";
                }
                break;
            case 'getProductInfo':
                $productID = $_GET['productID'];
                $sql = "SELECT * FROM sanpham where  idSanPham= " . $productID;
                $result = $dp->excuteQuery($sql);
                $product = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($product, $row);
                    }
                }
                if ($result != null) {
                    echo json_encode($product);
                } else {
                    echo "error";
                }
                break;

            case 'getNewIDProduct':
                $sql = "SELECT idSanPham FROM sanpham ORDER BY idSanPham DESC LIMIT 1";
                $result = $dp->excuteQuery($sql)->fetch_assoc();
                if ($result != null) {
                    echo $result['idSanPham'] + 1;
                } else {
                    echo "error";
                }
                break;
            case 'getAllproduct':
                $sql = "SELECT * FROM sanpham";
                $result = $dp->excuteQuery($sql);
                $product = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        array_push($product, $row);
                    }
                }
                echo json_encode($product);
                break;
        }
        break;
    case 'POST':
        switch ($_POST['action']) {
            case 'favorite':
                if (!isset($_SESSION['userID'])) {
                    echo "You are not logged in!";
                    break;
                }
                $userID = $_SESSION['userID'];
                $productID = $_POST['productID'];
                $sql = "INSERT INTO yeuthich
                    VALUES ( " . $productID . " ,'" . $userID . "')";
                $result = $dp->excuteQuery($sql);
                if ($result) {
                    echo "success";
                } else {
                    echo "error";
                }
                break;
            case 'addNewproduct':
                $productID = $_POST['productID'];
                $colorID=$_POST['colorID'];
                $phongID=$_POST['phongID'];
                $loaiID=$_POST['loaiID'];
                $productName = $_POST['productName'];
                $productPrice = $_POST['productPrice'];
                $productImage = $_POST['productImage'];
                $productDescribe = $_POST['productDescribe'];
                $sql = "INSERT INTO SanPham
                        VALUES(" . $productID . "," . $colorID . "," . $phongID . "," . $loaiID . ",'" . $producName . "'," . $productPrice .",'".$productDescribe."','".$productImage. "',0,1 )";
                echo $sql;
                $result = $dp->excuteQuery($sql);
                break;
        }
        break;
    case 'PUT':
        switch ($_GET['action']) {
            case 'updateproductInfo':
                $productID = $_POST['productID'];
                $colorID=$_POST['colorID'];
                $phongID=$_POST['phongID'];
                $loaiID=$_POST['loaiID'];
                $productName = $_POST['productName'];
                $productPrice = $_POST['productPrice'];
                $productImage = $_POST['productImage'];
                $productDescribe = $_POST['productDescribe'];
                $active = $_GET['active'];
                $sql = "UPDATE product
                        SET tenSanPham = '" . $productName . "',
                            idPhong = " . $phongID . ",
                            idLoai = " . $loaiID . ",
                            gia = " . $productPrice . ",
                            hinh = '" . $productImage . "',
                            moTa = '" . $productDescribe . "',
                            TrangThai=".$active.",
                        WHERE maproduct = " . $productID;
                $result = $dp->excuteQuery($sql);
                break;
            case 'deleteproduct':
                $productID = $_GET['productID'];
                $sql = "Update product
                        SET TrangThai = 0
                        WHERE maproduct = " . $productID;
                $result = $dp->excuteQuery($sql);
                if ($result) {
                    echo "Success";
                } else {
                    echo "error";
                }
                break;
        }
        break;
    case 'DELETE':
        switch ($_GET['action']) {
            case 'dislike':
                $userID = $_SESSION['userID'];
                $productID = $_GET['productID'];
                $sql = "DELETE FROM yeuthich
                    WHERE idNguoiDung = '" . $userID . "' AND idSanPham = " . $productID . "";
                $result = $dp->excuteQuery($sql);
                if ($result) {
                    echo "success";
                } else {
                    echo "error";
                }
                break;
            case 'deleteColor':
                $productID = $_GET['productID'];
                $colorID=$_GET['$colorID'];
                $sql = "DELETE FROM sanpham
                        WHERE idSanPham= " . $productID." and idMau= ".$colorID;
                $result = $dp->excuteQuery($sql);
                if ($result) {
                    echo "success";
                } else {
                    echo "error";
                }
                break;
            case 'deleteProductByID':
                $productID = $_GET['productID'];
                $sql = "DELETE FROM sanpham
                        WHERE idSanPham= " . $productID;
                $result = $dp->excuteQuery($sql);
                if ($result) {
                    echo "success";
                } else {
                    echo "error";
                }
                break;
        }
        break;
}