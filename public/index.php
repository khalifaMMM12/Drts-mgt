<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../style/style.css" rel="stylesheet">    
    <link href="../style/output.css" rel="stylesheet">    
<title>SignUp</title>
</head>
<body>
    <div class="flex h-screen bg-amber-400">
    <div class="w-full max-w-md shadow-2xl m-auto border-8 border-gray-900 bg-amber-300 rounded-md p-5">   
        <header class="mb-5">
            <img class="w-28 mx-auto" src="../img/DRTS_logo.png" />
            <h2 class="mb-2 text-center text-3xl font-bold tracking-tight text-gray-900">Directorate of Road Traffic Services</h2>
            <p class="">Login with the assigned username and password</p>
        </header>   
        <form method="POST" action="login.php">
            <div>
                <label class="block mb-2 font-bold text-black-500" for="username">Username</label>
                <input class="w-full p-2 mb-6 text-black-700 border-b-4 border-amber-500 outline-none focus:bg-gray-300" id="username" type="text" name="username">
            </div>
            <div>
                <label class="block mb-2 font-bold text-black-500" for="password">Password</label>
                <input class="w-full p-2 mb-6 text-black-700 border-b-4 border-amber-500 outline-none focus:bg-gray-300" id="password" type="password" name="password">
            </div>
            <?php if (isset($error)): ?>
                <div class="bg-red-200 p-4 mb-6 rounded-sm">
                    <p class="text-red-500 text-center"><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            <div>          
            <button class="w-full bg-neutral-600 hover:bg-neutral-500 text-white font-bold py-2 px-4 mb-6 rounded" type="submit">Login</button>
            </div>       
        </form>  
        <!-- <footer>
            <a class="text-indigo-700 hover:text-pink-700 text-sm float-left" href="#">Forgot Password?</a>
            <a class="text-indigo-700 hover:text-pink-700 text-sm float-right" href="#">Create Account</a>
        </footer>    -->
        </div>
    </div>
</body>
</html>