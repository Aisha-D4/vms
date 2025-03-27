<?php
session_start();
require_once __DIR__ . '/db_connection.php';

// Initialize all variables
$firstName = $lastName = $organizationName = $email = $password = $userType = "";
$description = $website = $address = $city = $foundedYear = "";
$errors = [];
$registrationSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userType = $_POST["userType"] ?? '';
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    
    // Common validation for all user types
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        $stmt = $conn->prepare("SELECT email FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $errors['email'] = "Email is already registered";
        }
    }
    
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    }
    
    if (empty($userType) || !in_array($userType, ['volunteer', 'organization', 'admin'])) {
        $errors['userType'] = "Please select a valid user type";
    }
    
    // User type specific validation
    if ($userType === 'volunteer' || $userType === 'admin') {
        $firstName = trim($_POST["firstName"]);
        $lastName = trim($_POST["lastName"]);
        
        if (empty($firstName)) {
            $errors['firstName'] = "First name is required";
        }
        
        if (empty($lastName)) {
            $errors['lastName'] = "Last name is required";
        }
    } elseif ($userType === 'organization') {
        $organizationName = trim($_POST["organizationName"]);
        $description = trim($_POST["description"] ?? '');
        $website = trim($_POST["website"] ?? '');
        $address = trim($_POST["address"] ?? '');
        $city = trim($_POST["city"] ?? '');
        $foundedYear = trim($_POST["foundedYear"] ?? '');
        
        if (empty($organizationName)) {
            $errors['organizationName'] = "Organization name is required";
        }
        if (empty($description)) {
            $errors['description'] = "Description is required";
        }
        if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
            $errors['website'] = "Invalid website URL";
        }
        if (empty($address)) {
            $errors['address'] = "Address is required";
        }
        if (empty($city)) {
            $errors['city'] = "City is required";
        }
        if (!empty($foundedYear) && ($foundedYear < 1900 || $foundedYear > date('Y'))) {
            $errors['foundedYear'] = "Invalid founded year";
        }
    }
    
    if (empty($errors)) {
        try {
            $conn->beginTransaction();
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            if ($userType === 'organization') {
                $firstName = $organizationName;
                $lastName = ''; // Empty last name for organizations
            }
            
            // Insert into users table
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password, role, registration_date) 
                                  VALUES (:firstName, :lastName, :email, :password, :role, NOW())");
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $userType);
            $stmt->execute();
            
            $userId = $conn->lastInsertId();
            
            // Insert into specific tables based on user type
            if ($userType === 'volunteer') {
                $stmt = $conn->prepare("INSERT INTO volunteers (user_id) VALUES (:userId)");
                $stmt->bindParam(':userId', $userId);
                $stmt->execute();
            } elseif ($userType === 'organization') {
                $stmt = $conn->prepare("INSERT INTO organizations 
                                      (user_id, organization_name, description, website, address, city, founded_year) 
                                      VALUES (:userId, :orgName, :description, :website, :address, :city, :foundedYear)");
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':orgName', $organizationName);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':website', $website);
                $stmt->bindParam(':address', $address);
                $stmt->bindParam(':city', $city);
                $stmt->bindParam(':foundedYear', $foundedYear);
                $stmt->execute();
            }
            
            $conn->commit();
            header("Location: login.php?registered=true");
            exit();
        } catch(PDOException $e) {
            $conn->rollBack();
            $errors['general'] = "Registration failed: " . $e->getMessage();
            error_log("Registration Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | VolunTree</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --teal: #008080;
            --mustard: #FFDB58;
            --teal-light: #E6F2F2;
            --mustard-light: #FFF4CC;
        }
        .btn-primary {
            background-color: var(--mustard);
            color: #333;
        }
        .btn-primary:hover {
            background-color: #E6C44D;
        }
        .btn-secondary {
            background-color: var(--teal);
            color: white;
        }
        .btn-secondary:hover {
            background-color: #006666;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeRadios = document.querySelectorAll('input[name="userType"]');
            const volunteerFields = document.getElementById('volunteerFields');
            const organizationFields = document.getElementById('organizationFields');
            
            function updateFormFields() {
                const selectedType = document.querySelector('input[name="userType"]:checked').value;
                
                if (selectedType === 'organization') {
                    volunteerFields.style.display = 'none';
                    organizationFields.style.display = 'block';
                } else {
                    volunteerFields.style.display = 'block';
                    organizationFields.style.display = 'none';
                }
            }
            
            userTypeRadios.forEach(radio => {
                radio.addEventListener('change', updateFormFields);
            });
            
            // Initialize form fields on page load
            updateFormFields();
        });
    </script>
</head>
<body class="font-sans bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.php" class="flex items-center">
                <i class="fas fa-tree text-2xl mr-2" style="color: var(--teal);"></i>
                <span class="text-xl font-bold" style="color: var(--teal);">VolunTree</span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="login.php" class="text-gray-600 hover:text-teal-700 font-medium">Log In</a>
                <a href="register.php" class="px-4 py-2 rounded-lg font-medium" style="background-color: var(--mustard); color: #333;">Sign Up</a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="py-4 px-6 text-white text-center" style="background-color: var(--teal);">
                <h2 class="text-2xl font-bold">Join VolunTree</h2>
                <p class="text-blue-200">Create your account to start making a difference</p>
            </div>
            
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="py-4 px-6">
                <?php if(isset($errors['general'])): ?>
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <p><?php echo $errors['general']; ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">I am a:</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="userType" value="volunteer" class="form-radio h-5 w-5" style="color: var(--teal);" <?php echo ($userType === 'volunteer') ? 'checked' : ''; ?>>
                            <span class="ml-2">Volunteer</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="userType" value="organization" class="form-radio h-5 w-5" style="color: var(--teal);" <?php echo ($userType === 'organization') ? 'checked' : ''; ?>>
                            <span class="ml-2">Organization</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="userType" value="admin" class="form-radio h-5 w-5" style="color: var(--teal);" <?php echo ($userType === 'admin') ? 'checked' : ''; ?>>
                            <span class="ml-2">Admin</span>
                        </label>
                    </div>
                    <?php if(isset($errors['userType'])): ?>
                        <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['userType']; ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Volunteer/Admin Fields -->
                <div id="volunteerFields">
                    <div class="mb-4">
                        <label for="firstName" class="block text-gray-700 font-bold mb-2">First Name *</label>
                        <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" 
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['firstName']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($errors['firstName'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['firstName']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="lastName" class="block text-gray-700 font-bold mb-2">Last Name *</label>
                        <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" 
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['lastName']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($errors['lastName'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['lastName']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Organization Fields -->
                <div id="organizationFields" style="display: none;">
                    <div class="mb-4">
                        <label for="organizationName" class="block text-gray-700 font-bold mb-2">Organization Name *</label>
                        <input type="text" id="organizationName" name="organizationName" value="<?php echo htmlspecialchars($organizationName); ?>" 
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['organizationName']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($errors['organizationName'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['organizationName']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Description *</label>
                        <textarea id="description" name="description" rows="3"
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['description']) ? 'border-red-500' : ''; ?>"><?php echo htmlspecialchars($description); ?></textarea>
                        <?php if(isset($errors['description'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['description']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="website" class="block text-gray-700 font-bold mb-2">Website</label>
                        <input type="url" id="website" name="website" placeholder="https://example.com" value="<?php echo htmlspecialchars($website); ?>"
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['website']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($errors['website'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['website']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700 font-bold mb-2">Address *</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>"
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['address']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($errors['address'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['address']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="city" class="block text-gray-700 font-bold mb-2">City *</label>
                        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>"
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['city']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($errors['city'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['city']; ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <label for="foundedYear" class="block text-gray-700 font-bold mb-2">Founded Year</label>
                        <input type="number" id="foundedYear" name="foundedYear" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo htmlspecialchars($foundedYear); ?>"
                            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['foundedYear']) ? 'border-red-500' : ''; ?>">
                        <?php if(isset($errors['foundedYear'])): ?>
                            <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['foundedYear']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Common Fields -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" 
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['email']) ? 'border-red-500' : ''; ?>">
                    <?php if(isset($errors['email'])): ?>
                        <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['email']; ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-bold mb-2">Password *</label>
                    <input type="password" id="password" name="password" 
                        class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline <?php echo isset($errors['password']) ? 'border-red-500' : ''; ?>">
                    <?php if(isset($errors['password'])): ?>
                        <p class="text-red-500 text-xs italic mt-1"><?php echo $errors['password']; ?></p>
                    <?php else: ?>
                        <p class="text-gray-500 text-xs italic mt-1">Must be at least 8 characters</p>
                    <?php endif; ?>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <input id="agree" name="agree" type="checkbox" required 
                            class="h-4 w-4" style="color: var(--teal);">
                        <label for="agree" class="ml-2 block text-sm text-gray-700">
                            I agree to the <a href="#" class="text-teal-600 hover:text-teal-500">Terms of Service</a> and <a href="#" class="text-teal-600 hover:text-teal-500">Privacy Policy</a>
                        </label>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="w-full py-2 px-4 rounded-lg font-bold" style="background-color: var(--mustard); color: #333;">
                        Sign Up
                    </button>
                </div>
                
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Already have an account? <a href="login.php" class="text-teal-600 hover:text-teal-500">Log in</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <a href="index.php" class="inline-flex items-center">
                <i class="fas fa-tree text-xl mr-1" style="color: var(--mustard);"></i>
                <span class="font-bold text-xl">VolunTree</span>
            </a>
            <p class="mt-2 text-sm text-gray-400">&copy; <?php echo date("Y"); ?> VolunTree. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>