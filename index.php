<?php
session_start();
// $error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
$temp_username = $_SESSION['temp_username'] ?? '';
unset($_SESSION['temp_username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style/style.css" rel="stylesheet">    
    <link href="style/output.css" rel="stylesheet">    
<title>SignUp</title>
</head>
<body>
    <div class="flex h-screen bg-yellow-300">
    <div class="w-full max-w-md shadow-2xl m-auto border-8 border-gray-900 bg-amber-300 rounded-md p-5">   
        <header class="mb-5">
            <img class="w-28 mx-auto" src="img/DRTS_logo.png" />
            <h2 class="mb-2 text-center text-3xl font-bold tracking-tight text-gray-900">DRTS Assests Management</h2>
            <p class="text-center">Login with your assigned username and password</p>
        </header>   
        <form method="POST" action="public/login.php" id="loginForm">
            <div>
                <label class="block mb-2 font-bold text-black-500" for="username">Username</label>
                <input class="w-full p-2 mb-6 text-black-700 border-b-4 border-amber-500 outline-none focus:bg-gray-300" value="<?php echo htmlspecialchars($temp_username); ?>" id="username" type="text" name="username">
            </div>
            <div>
                <label class="block mb-2 font-bold text-black-500" for="password">Password</label>
                <div class="relative">
                    <input class="w-full p-2 mb-6 text-black-700 border-b-4 border-amber-500 outline-none focus:bg-gray-300" id="password" type="password" name="password">
                    <i class="fas fa-eye absolute right-3 top-3 cursor-pointer" id="togglePassword"></i>
                </div>
            </div>
            <?php if (isset($error)): ?>
                <div id="errorDiv" class="bg-red-200 p-4 mb-6 rounded-sm opacity-100">
                    <p class="text-red-600 text-center font-bold"><?php echo ($error); ?></p>
                </div>
            <?php endif; ?>
            <div>          
            <button class="w-full bg-neutral-600 hover:bg-neutral-500 text-white font-bold py-2 px-4 mb-6 rounded" type="submit">Login</button>
            </div>       
        </form>  
        </div>
    </div>

    <script src="scripts/login.js"></script>
    <script src="https://kit.fontawesome.com/79a49acde1.js" crossorigin="anonymous"></script>
</body>
</html>