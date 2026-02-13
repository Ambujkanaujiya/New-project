<?php

if(isset($_POST['admin_registration'])){

  $admin_name = $_POST['admin_name'];
  $admin_email = $_POST['admin_email'];
  $admin_password = $_POST['admin_password'];
  $confirm_password = $_POST['confirm_password'];

  $admin_image = $_FILES['admin_image']['name'];
  $admin_image_temp = $_FILES['admin_image']['tmp_name'];

  // Check if admin already exists
  $select_query = $con->prepare("SELECT 1 FROM `admin_table`
                   WHERE admin_name=?
                   OR admin_email=?");

 $select_query->bind_param("ss",$admin_name,$admin_email);
  $select_query->execute();
  $result=$select_query->get_result();
  if($result->num_rows> 0){
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
    $insert_query = $con->prepare("INSERT INTO `admin_table`
    (admin_name, admin_email, admin_password, admin_image)
    VALUES
    (?,?,?,?)");
$insert_query->bind_param("ssss",$admin_name,$admin_email,$hash_password,$admin_image);
    if($insert_query->execute()){
      echo "<script>alert('Admin Registered Successfully')</script>";
    } else {
      echo "<script>alert('Registration Failed')</script>";
    }
  }
}
?>
