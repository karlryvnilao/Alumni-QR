<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN STUDENT</title>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@500&display=swap" rel="stylesheet">

    <style>
       /* Body with background gradient */
body {
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../assets/img/bg.jpg');
    background-size: cover; /* Cover the entire viewport */
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Prevent the image from repeating */
    margin: 0; /* Remove default margin */
    height: 100vh; /* Full height for the body */
    display: flex; /* Use flexbox for centering */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    position: relative; /* Relative positioning for the overlay */
    font-family: 'Roboto', sans-serif; /* Use Roboto as the main font */
}

/* Dark overlay */
.overlay {
    position: absolute; /* Absolute positioning to cover the entire body */
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
    z-index: 1; /* Place overlay behind the content */
}

/* Headings */
h2 {
    text-align: center;
    color: #fff; /* Change text color to white for better contrast */
    font-family: 'Montserrat', sans-serif; /* Use Montserrat for headings */
    margin-bottom: 20px; /* Space below the heading */
}

/* Form container */
.row {
    width: 90%; /* Use percentage for responsive width */
    max-width: 800px; /* Set max width */
    margin: 20px auto;
    border-radius: 12px; /* More pronounced rounded corners */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3); /* Enhanced shadow for depth */
    background-color: rgba(255, 255, 255, 0.9); /* Slightly opaque white */
    padding: 30px; /* More padding for breathing room */
    box-sizing: border-box; /* Include padding in width calculation */
    position: relative; /* Position relative for z-index */
    z-index: 2; /* Ensure form is above the overlay */
}

/* Video styling */
video {
    border-radius: 8px;
    width: 100%;
    height: auto; /* Maintain aspect ratio */
}

/* Labels */
label {
    display: block;
    margin-bottom: 10px;
    font-weight: 700; /* Bold for better emphasis */
    color: #333; /* Dark color for contrast */
}

/* Input fields */
input[type="text"] {
    width: calc(100% - 20px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
    box-sizing: border-box; /* Include padding in width calculation */
    font-family: 'Roboto', sans-serif; /* Use Roboto for inputs */
}

/* Input focus state */
input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
}

/* Button styles */
button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: background-color 0.3s ease; /* Smooth transition for hover */
}

/* Button hover state */
button:hover {
    background-color: #0056b3;
}

/* Error message styles */
.error {
    color: red;
    text-align: center;
    margin-bottom: 20px;
}

/* Media Queries for Smaller Screens */
@media (max-width: 600px) {
    .row {
        padding: 15px; /* Adjust padding for small screens */
    }

    button {
        font-size: 14px; /* Adjust button font size */
    }
}

    </style>
</head>
<body>
    <div class="overlay"></div> <!-- Overlay div for dark background -->
    <form action="login.php" method="post">
        <?php if (isset($_GET['error'])) { ?>
            <p class="error"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php } ?>
        <div class="row">
            <video id="preview" width="100%"></video>
            <label for="text">SCAN QR CODE HERE</label>
            <input type="text" name="username" id="text" placeholder="INPUT QR CODE" class="form-control" readonly>
            <button type="submit">LOGIN</button>
            Not yet a member? <a href="../index.php">here</a>
        </div>
    </form>
    
    <script>
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                scanner.start(cameras[0]);
            } else {
                alert('No cameras found');
            }
        }).catch(function(e) {
            console.error(e);
        });

        scanner.addListener('scan', function(c) {
            document.getElementById('text').value = c;
            document.forms[0].submit();
        });
    </script>
</body>
</html>
