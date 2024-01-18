<?php
// Get the user details from the form or other source
$email = $_POST['email']; // Assuming email is used as the login identifier
$password = $_POST['password'];
// Retrieve the WordPress user based on the email
$user = get_user_by( 'email', $email );
// Check if a user with the matching email exists
if ( $user ) {
    // Verify the password using WordPress's secure password checking
    if ( wp_check_password( $password, $user->data->user_pass, $user->ID ) ) {
        // Authentication successful
        // Create a session and redirect to the appropriate page
        wp_set_auth_cookie( $user->ID, true );
        wp_redirect( home_url() ); // Redirect to the home page
        exit();
    } else {
        // Password incorrect
        // Display an error message to the user
        echo 'Incorrect password. Please try again.';
    }
} else {
    // User not found
    // Display an error message to the user
    echo 'User not found. Please check your email and try again.';
}
?>
 <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h4 class="text-center mb-4">You are now logged out.</h4>
                <form action="login.php" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me">
                        <label class="form-check-label" for="remember_me">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">LOG IN</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="#">Lost Password</a> |
                    <a href="#">Go to myPSBA</a>
                </div>
            </div>
        </div>
    </main>