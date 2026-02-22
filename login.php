<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Adjust the width and height of the input fields and button to match */
        .form-control {
            width: 100%;
            height: 50px; /* Set height for input fields */
            margin-bottom: 10px; /* Add margin for spacing */
            padding-left: 5px; /* Add padding for space */
        }

        .btn {
        font-size: 25px;
            width: 100%;
            height: 50px; /* Set the height of the button */
            margin-top: 10px; /* Optional: Add top margin to give space from inputs */
            background-color:  #9b86c5; /* Light lavender button */
            border: none;
            color: white;
        }

        .btn:hover {
            background-color: #b183d4; /* Slightly darker lavender for hover effect */
        }

        body {
            background-color: #9b86c5; /* Dark lavender background for the page */
        }

        .login-container {
        font-size: 25px;
            max-width: 700px;
            margin: 70px auto;
            padding: 50px;
            background-color: #333333; /* Light black box */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
        font-size: 50px;
            text-align: center;
            margin-bottom: 30px;
            color: #ffffff; /* White color for heading */
        }

        label {
        font-size: 25px;
            color: #ffffff; /* White color for labels */
        }

        .form-group {
        font-size: 25px;
            position: relative; /* Ensure correct layout of form elements */
        }

        .form-control::placeholder {
        font-size: 25px;
            color: #999999; /* Light grey placeholder text */
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2> POS Application Login</h2>
        <form action="login_action.php" method="POST">
            <div class="form-group">
                <label for="username">🙍🏻‍♀️Username:</label>
                <input type="text" class="form-control" name="username" placeholder="Enter username" required>
            </div>
            
            <div class="form-group">
                <label for="password">🔒Password:</label>
                <input type="password" class="form-control" name="password" placeholder="Enter password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
    </div>

</body>
</html>
