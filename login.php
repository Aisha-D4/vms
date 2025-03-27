<?php
session_start();
require_once 'db_connection.php';

$email = $password = "";
$emailErr = $passwordErr = $loginErr = "";
$justRegistered = isset($_GET['registered']) && $_GET['registered'] == 'true';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    
    if (empty($email)) {
        $emailErr = "Email is required";
    }
    
    if (empty($password)) {
        $passwordErr = "Password is required";
    }
    
    if (empty($emailErr) && empty($passwordErr)) {
        try {
            $stmt = $conn->prepare("SELECT id, first_name, last_name, email, password, role FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch();
                
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['logged_in'] = true;
                    
                    // Get additional info based on role
                    if ($user['role'] === 'organization') {
                        $stmt = $conn->prepare("SELECT organization_name FROM organizations WHERE id = :id");
                        $stmt->bindParam(':id', $user['id']);
                        $stmt->execute();
                        $orgInfo = $stmt->fetch();
                        $_SESSION['organization_name'] = $orgInfo['organization_name'];
                    }
                    
                    // Update last login
                    $stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
                    $stmt->bindParam(':id', $user['id']);
                    $stmt->execute();
                    
                    // Redirect based on role
                    switch ($user['role']) {
                        case 'organization':
                            header("Location: organization/dashboard.php");
                            break;
                        case 'volunteer':
                        default:
                            header("Location: volunteer/dashboard.php");
                            break;
                    }
                    exit();
                } else {
                    $loginErr = "Invalid email or password";
                }
            } else {
                $loginErr = "Invalid email or password";
            }
        } catch(PDOException $e) {
            $loginErr = "Login error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | Volunteer Hub</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-4">
                    <div>
                        <a href="index.php" class="flex items-center py-5 px-2 text-white">
                            <i class="fas fa-hands-helping text-xl mr-1"></i>
                            <span class="font-bold text-xl">Volunteer Hub</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-1">
                    <a href="register.php" class="py-2 px-4 bg-blue-800 text-white rounded hover:bg-blue-700 transition duration-300">Sign up</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="py-4 px-6 bg-blue-600 text-white text-center">
                <h2 class="text-2xl font-bold">Welcome Back</h2>
                <p class="text-blue-200">Log in to your Volunteer Hub account</p>
            </div>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="py-4 px-6">
                <?php if ($justRegistered): ?>
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <p>Registration successful! You can now log in.</p>
                    </div>
                <?php endif; ?>
                
                <?php if ($loginErr): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <p><?php echo $loginErr; ?></p>
                    </div>
                <?php endif; ?>
                
                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" 
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo $emailErr ? 'border-red-500' : ''; ?>">
                    <?php if ($emailErr): ?>
                        <p class="text-red-500 text-xs italic mt-1"><?php echo $emailErr; ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password" 
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo $passwordErr ? 'border-red-500' : ''; ?>">
                    <?php if ($passwordErr): ?>
                        <p class="text-red-500 text-xs italic mt-1"><?php echo $passwordErr; ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Remember Me and Forgot Password -->
                <div class="mb-6 flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500">Forgot password?</a>
                </div>
                
                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Log In
                    </button>
                </div>
                
                <!-- Register Link -->
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Don't have an account? <a href="register.php" class="text-blue-600 hover:text-blue-500">Sign up</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <a href="index.php" class="inline-flex items-center">
                <i class="fas fa-hands-helping text-xl mr-1"></i>
                <span class="font-bold text-xl">Volunteer Hub</span>
            </a>
            <p class="mt-2 text-sm text-gray-400">&copy; <?php echo date("Y"); ?> Volunteer Hub. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>