<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!--Sweetalert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">-->
    <link rel="stylesheet" href="">

    <style>
    body {
    padding: 6vh;
    background-color: #75acd2;
    color: #0a2e48;
    }

    .col {
    
    padding: 0;
    }
    #login-container{
    position: relative;
    max-width: 56%;
    height:55vh;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
    }
    #header {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
        border-radius: 8px;
    }
    #username, #password {
    border: solid 2px;
    border-radius: 7px;
    color: #0d6efd;
    }
    #password {
    color: crimson;
    }
    span {
        width: 100px;
    }

    </style>

</head>
<body>
<div class="d-flex justify-content-center align-items-center" style=""  id="header">
    <h1 style="font-size:90px;">EARNINGS LOG SYSTEM</h1>
</div>
<div class="container" style="padding-top: 3vh;">
    <div class="container d-flex justify-content-center align-items-center">
        <div class="overflow-y-auto p-3" id="login-container">
            <div class="d-block px-4 pt-1" id="signin">
                <h1>Sign in your account</h1>
            </div>
            <div class="d-block px-4 pt-3" id="form">
                <!-- Login Form -->
                <form id="loginForm">
                    <!-- Username Section -->
                    <div class="input-group mb-3 mt-3" id="username">
                        <span class="input-group-text" id="basic-addon1">Username</span>
                        <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Enter your username" aria-describedby="basic-addon1">
                    </div>

                    <!-- Password Section -->
                    <div class="input-group mb-3 mt-3" id="password">
                        <span class="input-group-text" id="basic-addon2">Password</span>
                        <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Enter your password" aria-describedby="basic-addon2">
                    </div>


                   <!-- Submit Button -->
                    <div class="input-group  pt-2 mb-3">
                        <button type="button" class="btn btn-primary form-control" onclick="handleLogin()">Login</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>



    
<!-- Include your external script.js file -->
<script src="script.js"></script>

<!-- Bootstrap JS (optional) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
