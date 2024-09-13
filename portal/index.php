<!DOCTYPE html>
<html>
<head>
	<title>LOGIN STUDENT</title>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<style>
    h2 {
    text-align: center;
    color: #333;
}

.row {
    display: flex;
    justify-content: space-between;
    width: 80%;
    max-width: 800px;
    margin: 20px auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    background-color: #fff;
    padding: 20px;
}

.col-md-6 {
    flex: 1;
    padding: 10px;
}

video {
    border-radius: 8px;
    width: 100%;
}

label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

input[type="text"] {
    width: calc(100% - 20px);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
}

input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
}

button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

button:hover {
    background-color: #0056b3;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 20px;
}
</style>
<body>
     <form action="login.php" method="post">

     	<h2>LOGIN</h2>

     	<?php if (isset($_GET['error'])) { ?>
     		<p class="error"><?php echo $_GET['error']; ?></p>
     	<?php } ?>

     	<div class="row">
                <div class="col-md-6">
                    <video id="preview" width="100%"></video>
                </div>
                <div class="col-md-6">
                   <form method="post" class="form-horizontal">
                    <label>SCAN QR CODE HERE</label>
                    <input type="text" name="username" id="text" readonyy="" placeholder="INPUT QR CODE" class="form-control">
                   </form>
                   

     	<button type="submit">LOGIN</button>
        <a href="../index.php">Are you admin?</a>
     	</div>
        
            </div>
     </form>
             <script>
           let scanner = new Instascan.Scanner({ video: document.getElementById('preview')});
           Instascan.Camera.getCameras().then(function(cameras){
               if(cameras.length > 0 ){
                   scanner.start(cameras[0]);
               } else{
                   alert('No cameras found');
               }

           }).catch(function(e) {
               console.error(e);
           });

           scanner.addListener('scan',function(c){
               document.getElementById('text').value=c;
               document.forms[0].submit();
           });

        </script>
</body>
</html>