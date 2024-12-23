<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST['productName'];
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["productImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // تحقق مما إذا كانت الصورة حقيقية
    $check = getimagesize($_FILES["productImage"]["tmp_name"]);
    if ($check === false) {
        echo "الملف ليس صورة.";
        $uploadOk = 0;
    }

    // تحقق من حجم الملف
    if ($_FILES["productImage"]["size"] > 500000) {
        echo "آسف، حجم الملف كبير جداً.";
        $uploadOk = 0;
    }

    // السماح بأنواع الملفات المحددة
    if(!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        echo "آسف، فقط ملفات JPG، JPEG، PNG و GIF مسموح بها.";
        $uploadOk = 0;
    }

    // تحقق مما إذا كان $uploadOk قد تم تعيينه إلى 0 بسبب خطأ
    if ($uploadOk == 0) {
        echo "آسف، لم يتم تحميل الصورة.";
    } else {
        if (move_uploaded_file($_FILES["productImage"]["tmp_name"], $targetFile)) {
            echo "تم تحميل الصورة " . htmlspecialchars(basename($_FILES["productImage"]["name"])) . ".";
            // هنا يمكنك حفظ معلومات المنتج في ملف أو قاعدة بيانات
            $productInfo = "اسم المنتج: " . $productName . "\nصورة: " . $targetFile . "\n\n";
            file_put_contents('products.txt', $productInfo, FILE_APPEND | LOCK_EX);
            echo "تم حفظ المنتج بنجاح.";
        } else {
            echo "آسف، حدث خطأ أثناء تحميل الصورة.";
        }
    }
}
?>