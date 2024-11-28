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
        <div class="flex-1 p-6 ml-64 " id="main-content">
            <?php 
            if(!isset($page)){
                require_once 'components/dashboard.php';
            }
            ?>
            <?php 
            if(isset($page) && $page ==='vote'){
                require_once 'components/vote.php';
            }
            ?>
            <?php 
            if(isset($page) && $page ==='settings'){
                require_once 'components/setting.php';
            }
            ?>
            <?php 
            if(isset($page) && $page ==='profile'){
                require_once 'components/profile.php';
            }
            ?>
            <?php 
            if(isset($page) && $page ==='create-poll'){
                require_once 'components/createpoll.php';
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
