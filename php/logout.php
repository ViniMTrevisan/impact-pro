<?php
session_start();
session_destroy();
header("Location: ../main/index.html");
exit();