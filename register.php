<?php
require_once 'includes/header.php';

if (isLoggedIn()) {
    redirect(getUserType() . '/dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'includes/auth.php';
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];
    $name = $_POST['name'];
    $user_type = $_POST['user_type'];
    
    // Validate inputs
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email) || empty($name)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif (usernameExists($username)) {
        $error = "Username already taken.";
    } elseif (emailExists($email)) {
        $error = "Email already registered.";
    } else {
        // Register user
        $user_id = registerUser($username, $password, $email, $name, $user_type);
        
        if ($user_id) {
            if ($user_type === 'student') {
                $class = $_POST['class'];
                $section = $_POST['section'];
                $roll_number = $_POST['roll_number'];
                $date_of_birth = $_POST['date_of_birth'];
                $address = $_POST['address'];
                $phone_number = $_POST['phone_number'];
                
                if (registerStudent($user_id, $class, $section, $roll_number, $date_of_birth, $address, $phone_number)) {
                    $success = "Student registration successful! You can now login.";
                } else {
                    $error = "Error registering student details.";
                }
            } elseif ($user_type === 'teacher') {
                $subject = $_POST['subject'];
                $qualification = $_POST['qualification'];
                $joining_date = $_POST['joining_date'];
                
                if (registerTeacher($user_id, $subject, $qualification, $joining_date)) {
                    $success = "Teacher registration successful! You can now login.";
                } else {
                    $error = "Error registering teacher details.";
                }
            } else {
                $success = "Registration successful! You can now login.";
            }
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<section class="auth-form">
    <h2>Register</h2>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="register.php">
        <div class="form-group">
            <label for="user_type">I am a:</label>
            <select id="user_type" name="user_type" required onchange="toggleRegistrationFields()">
                <option value="">Select User Type</option>
                <option value="student">Student</option>
                <option value="teacher">Teacher</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <!-- Student fields (hidden by default) -->
        <div id="student-fields" style="display: none;">
            <div class="form-group">
                <label for="class">Class</label>
                <input type="text" id="class" name="class">
            </div>
            
            <div class="form-group">
                <label for="section">Section</label>
                <input type="text" id="section" name="section">
            </div>
            
            <div class="form-group">
                <label for="roll_number">Roll Number</label>
                <input type="number" id="roll_number" name="roll_number">
            </div>
            
            <div class="form-group">
                <label for="date_of_birth">Date of Birth</label>
                <input type="date" id="date_of_birth" name="date_of_birth">
            </div>
            
            <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address"></textarea>
            </div>
            
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="tel" id="phone_number" name="phone_number">
            </div>
        </div>
        
        <!-- Teacher fields (hidden by default) -->
        <div id="teacher-fields" style="display: none;">
            <div class="form-group">
                <label for="subject">Subject</label>
                <select id="subject" name="subject">
                    <option value="">Select Subject</option>
                    <?php foreach (getSubjects() as $subject): ?>
                        <option value="<?php echo $subject; ?>"><?php echo $subject; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="qualification">Qualification</label>
                <input type="text" id="qualification" name="qualification">
            </div>
            
            <div class="form-group">
                <label for="joining_date">Joining Date</label>
                <input type="date" id="joining_date" name="joining_date">
            </div>
        </div>
        
        <button type="submit" class="btn">Register</button>
    </form>
    
    <p>Already have an account? <a href="login.php">Login here</a></p>
</section>

<script>
function toggleRegistrationFields() {
    const userType = document.getElementById('user_type').value;
    document.getElementById('student-fields').style.display = 'none';
    document.getElementById('teacher-fields').style.display = 'none';
    
    if (userType === 'student') {
        document.getElementById('student-fields').style.display = 'block';
        // Make student fields required
        document.getElementById('class').required = true;
        document.getElementById('section').required = true;
        document.getElementById('roll_number').required = true;
        document.getElementById('date_of_birth').required = true;
        document.getElementById('address').required = true;
        document.getElementById('phone_number').required = true;
        // Make teacher fields not required
        document.getElementById('subject').required = false;
        document.getElementById('qualification').required = false;
        document.getElementById('joining_date').required = false;
    } else if (userType === 'teacher') {
        document.getElementById('teacher-fields').style.display = 'block';
        // Make teacher fields required
        document.getElementById('subject').required = true;
        document.getElementById('qualification').required = true;
        document.getElementById('joining_date').required = true;
        // Make student fields not required
        document.getElementById('class').required = false;
        document.getElementById('section').required = false;
        document.getElementById('roll_number').required = false;
        document.getElementById('date_of_birth').required = false;
        document.getElementById('address').required = false;
        document.getElementById('phone_number').required = false;
    } else {
        // Make all additional fields not required
        document.getElementById('class').required = false;
        document.getElementById('section').required = false;
        document.getElementById('roll_number').required = false;
        document.getElementById('date_of_birth').required = false;
        document.getElementById('address').required = false;
        document.getElementById('phone_number').required = false;
        document.getElementById('subject').required = false;
        document.getElementById('qualification').required = false;
        document.getElementById('joining_date').required = false;
    }
}
</script>

<?php require_once 'includes/footer.php'; ?>