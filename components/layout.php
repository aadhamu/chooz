<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- <link rel="stylesheet" href="css/index.css"> -->
    <link href="./src/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <title><?php echo isset($page) ? $page : "Document"; ?></title>

<style>
    @keyframes float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.float-animation {
  animation: float 3s ease-in-out infinite;
}

</style>
</head>
<body class="min-h-screen bg-gradient-to-r from-blue-50 to-white overflow-x-hidden flex  flex-col w-full">
    <?php require_once 'header.php'?>

    <main class="flex-grow">
        <?php
        $page = isset($page) ? $page : 'homepage'; // Default to homepage

        // Use a whitelist to prevent direct access to files
        $allowedPages = ['homepage', 'contact', 'about', 'faq','login','signup'];
        if (in_array($page, $allowedPages)) {
            require_once $page . '.php'; // Include the appropriate page
        } else {
            require_once '404.php'; // Fallback page for unknown routes
        }
        ?>
    </main>

    <?php require_once 'footer.php'?>


<!-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> -->
    <script>
  document.addEventListener('DOMContentLoaded', () => {
    AOS.init({
      duration: 500,
      once: true,
    });
  });
</script>

</body>
</html>