<?php
// logout.php

// Hapus cookie 'jwt' (bukan 'token')
setcookie("jwt", "", time() - 3600, "/", "", false, true); // sesuaikan dengan flag saat set

// (Opsional) Bersihkan juga dari memori
unset($_COOKIE['jwt']);

// Redirect ke login
header("Location: login.php");
exit;
