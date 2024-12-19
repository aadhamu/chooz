<?php
  require_once "components/auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="./src/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/utility.css">
    <title>Dashboard</title>
</head>
<body class="">
    <?php include 'components/dashboardheader.php'?>
    <div class="flex flex-1">
        <?php include 'components/sidebar.php'; ?>
        <div class="flex-1 p-6 ml-64  " id="main-content">
            <?php 
            if(!isset($page)){
                require_once 'components/dashboard.php';
            }elseif(isset($page) && $page ==='vote'){
                require_once 'components/vote.php';
            }elseif(isset($page) && $page ==='settings'){
                require_once 'components/setting.php';
            }elseif(isset($page) && $page ==='profile'){
                require_once 'components/profile.php';
            }elseif(isset($page) && $page ==='create-poll'){
                require_once 'components/createpoll.php';
            }elseif(isset($page) && $page ==='my-poll'){
                require_once 'components/mypoll.php';
            }elseif(isset($page) && $page ==='analysis'){
                require_once 'components/analysis.php';
            }elseif(isset($page) && $page ==='wallet'){
                require_once 'components/wallet.php';
            }elseif(isset($page) && $page ==='validatekey'){
                require_once 'components/validatekey.php';
            }elseif(isset($page) && $page ==='polldetails'){
                require_once 'components/polldetails.php';
            }elseif(isset($page) && $page ==='nomineeDetails'){
                require_once 'components/nomineeDetails.php';
            }elseif(isset($page) && $page ==='public'){
                require_once 'components/public.php';
            }elseif(isset($page) && $page ==='publickey'){
                require_once 'components/publicvote.php';
            }elseif(isset($page) && $page ==='pollcreator'){
                require_once 'components/pollanalysis.php';
            }elseif(isset($page) && $page ==='participatedpoll'){
                require_once 'components/participatedpoll.php';
            }elseif(isset($page) && $page ==='option-analysis'){
                require_once 'components/optionanalysis.php';
            }
            ?>

        </div>
    </div>

    <script>
       
        const mainContent = document.getElementById('main-content');
        const header = document.getElementById('header');
        
        hamburgerMenu.addEventListener('click', () => {
            
            if (sidebar.classList.contains('w-16')) {
                header.classList.remove('ml-64');
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-14');
                header.classList.add('ml-16');
            } else {
                mainContent.classList.remove('ml-14');
                header.classList.remove('ml-16');
                mainContent.classList.add('ml-64');
                header.classList.add('ml-64');
            }
        });
    </script>
</body>
</html>
