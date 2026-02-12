<?php

if(isset($_POST['admin_registration'])){

  $admin_name = $_POST['admin_name'];
  $admin_email = $_POST['admin_email'];
  $admin_password = $_POST['admin_password'];
  $confirm_password = $_POST['confirm_password'];

  $admin_image = $_FILES['admin_image']['name'];
  $admin_image_temp = $_FILES['admin_image']['tmp_name'];

  // Check if admin already exists
  $select_query = "SELECT * FROM `admin_table`
                   WHERE admin_name='$admin_name'
                   OR admin_email='$admin_email'";

  $result = mysqli_query($con, $select_query);
  $row_count = mysqli_num_rows($result);

  if($row_count > 0){
    echo "<script>alert('Admin name or Email already exists')</script>";
  }

  // Password match check
  else if($admin_password != $confirm_password){
    echo "<script>alert('Please match the password')</script>";
  }

  else{

    // Hash password (secure)
    $hash_password = password_hash($admin_password, PASSWORD_DEFAULT);

    // Move uploaded image
    move_uploaded_file($admin_image_temp,
      "../admin_area/admin_images/$admin_image");

    // Insert data
    $insert_query = "INSERT INTO `admin_table`
    (admin_name, admin_email, admin_password, admin_image)
    VALUES
    ('$admin_name', '$admin_email', '$hash_password', '$admin_image')";

    $result_query = mysqli_query($con, $insert_query);

    if($result_query){
      echo "<script>alert('Admin Registered Successfully')</script>";
    } else {
      echo "<script>alert('Registration Failed')</script>";
    }
  }
}
?>
