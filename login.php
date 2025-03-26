<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container" id="signup" style="display:none;">
      <h1 class="form-title">Register</h1>
      <form method="post" action="register.php">
        <!-- User Type Selection -->
        <div class="input-group user-type">
            <label for="userType">I am a:</label>
            <select name="userType" id="userType" required>
                <option value="volunteer">Volunteer</option>
                <option value="organization">Organization</option>
            </select>
        </div>

        <!-- Volunteer Fields -->
        <div id="volunteerFields">
            <div class="input-group">
               <i class="fas fa-user"></i>
               <input type="text" name="fName" id="fName" placeholder="First Name">
               <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name">
                <label for="lName">Last Name</label>
            </div>
        </div>

        <!-- Organization Fields -->
     <div id="organizationFields" style="display:none;">
        </div>
        <div class="input-group">
            <i class="fas fa-building"></i>
            <label for="organizationame">Organization Name:</label>
            <input type="text" name="organizationName" id="organizationName" placeholder="Organization Name" >
        </div>
        <!-- Common Fields -->
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="password">Password</label>
        </div>

        <input type="submit" class="btn" value="Sign Up" name="signUp">
      </form>
      
      <p class="or">----------or--------</p>
      <div class="icons">
        <i class="fab fa-google"></i>
        <i class="fab fa-facebook"></i>
      </div>
      <div class="links">
        <p>Already Have Account?</p>
        <button id="signInButton">Sign In</button>
      </div>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Welcome back to Voluntree</h1>
        <form method="post" action="register.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="signInEmail" placeholder="Email" required>
              <label for="signInEmail">Email</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="signInPassword" placeholder="Password" required>
              <label for="signInPassword">Password</label>
          </div>
          
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>

        <p class="or">----------or--------</p>
        <div class="icons">
          <i class="fab fa-google"></i>
          <i class="fab fa-facebook"></i>
        </div>
        <div class="links">
          <p>Don't have an account yet?</p>
          <button id="signUpButton">Sign Up</button>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>