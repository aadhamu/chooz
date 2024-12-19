<?php
require_once 'sanitize.php';

function sendEmail($firstname, $lastname, $email, $inquiry) {
    $to = "ogbenihappy05@gmail.com";
    $subject = "New Inquiry from $firstname $lastname";
    $message = "
    <html>
    <head>
        <title>New Inquiry</title>
    </head>
    <body>
        <h2>You have received a new inquiry</h2>
        <p><strong>Name:</strong> $firstname $lastname</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Inquiry:</strong><br/>$inquiry</p>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: $email" . "\r\n";

    if (mail($to, $subject, $message, $headers)) {
        return true;
    } else {
        return false;
    }
}

$errors=[
    'Firstname'=> '',
    'Lastname'=> '',
    'Emailaddress'=> '',
    'Inquiry'=> ''
];
$Firstname=$Lastname=$Email=$Inquiry=$success=$failed="";
if (isset($_POST['Contact-me'])) {
    $Firstname=sanitize($_POST['firstname']);
    $Lastname=sanitize($_POST['lastname']);
    $Email=sanitize($_POST['email']);
    $Inquiry=sanitize($_POST['inquiry']);

    if(empty($Firstname)) {
        $errors['Firstname'] = "First Name is required.";
    } 

    if (empty($Lastname)) {
        $errors['Lastname'] = "Last Name is required.";
    }

    if (empty($Email)) {
        $errors['Emailaddress'] = "Email Address is required.";
    } elseif (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $errors['Emailaddress'] = "Invalid email format.";
    }

    if (empty($Inquiry)) {
        $errors['Inquiry'] = "Inquiry is required.";
    } 

    if (!array_filter($errors)) {
        $emailSent = sendEmail($Firstname, $Lastname, $Email, $Inquiry);

        if ($emailSent) {
            $Firstname=$Lastname=$Email=$Inquiry=$failed="";
            $success ="Thank you for your inquiry. We will get back to you shortly.";
        } else {
            $failed="Sorry, there was an error sending your inquiry. Please try again later.";
        }
    }
}
?>


<div class="grid w-full grid-cols-1 md:grid-cols-2 gap-10 py-10 px-6">
    <div class="flex flex-col justify-center">
        <h1 class="text-4xl font-bold text-blue-800 mb-4">Contact Us</h1>
        <p class="text-gray-600 mb-6">
            <strong>Need to get in touch with us?</strong> Either fill out the form with your inquiry or message us on our social media platforms.
        </p>
    </div>

    <div class="bg-white shadow-lg p-6 rounded-lg max-w-lg w-full mx-auto">
        <form action="contact.php" method="POST">
            <?php
              if(isset($success)):
            ?>
             <div class="text-green-500 text-sm mb-3 text-center"><?=$success?></div>
             <?php elseif($failed):?>
                <div class="text-red-500 text-sm mb-3 text-center"><?=$failed?></div>
            <?php endif;?>
            <div class="grid grid-cols-1 gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="firstname" class="block text-gray-700 font-semibold">First Name</label>
                        <input type="text" id="firstname" name="firstname" value="<?= $Firstname?>"
                               class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" >
                        <?php
                          if(isset($errors['Firstname'])):
                        ?>
                               <div class="text-sm text-red-600"><?= $errors['Firstname']?></div>
                        <?php endif;?>
                    </div>

                    <div>
                        <label for="lastname" class="block text-gray-700 font-semibold">Last Name</label>
                        <input type="text" id="lastname" name="lastname" value="<?= $Lastname?>"
                               class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" >
                        <?php
                          if(isset($errors['Lastname'])):
                        ?>
                               <div class="text-sm text-red-600"><?= $errors['Lastname']?></div>
                        <?php endif;?>
                     </div>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-semibold">Email Address</label>
                    <input type="text" id="email" name="email" value="<?= $Email?>"
                           class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200">
                    <?php
                    if(isset($errors['Emailaddress'])):
                    ?>
                        <div class="text-sm text-red-600"><?= $errors['Emailaddress']?></div>
                    <?php endif;?>
                </div>

                <div>
                    <label for="inquiry" class="block text-gray-700 font-semibold">What can we help you with?</label>
                    <textarea id="inquiry" name="inquiry" rows="5" 
                              class="w-full p-3 border border-gray-300 rounded-lg bg-blue-100 focus:outline-none focus:border-blue-500 transition duration-200" 
                              ><?= $Inquiry?></textarea>

                    <?php  
                      if(isset($errors['Inquiry'])):
                    ?>
                        <div class="text-sm text-red-600"><?= $errors['Inquiry']?></div>
                    <?php endif;?>
                </div>

                <div class="flex justify-start">
                    <input type="submit" class="px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg hover:bg-blue-900 transition duration-300" value="Submit" name="Contact-me"/>
                </div>
            </div>
        </form>
    </div>
</div>
