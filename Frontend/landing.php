<?php
session_start();

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
	
$request = array();
$request['type'] = "getIngredientsList";
$request['destination'] = "database";
$request['username'] = $_SESSION['name'];
$request['token'] = $_SESSION['token'];
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");		
$response = $client->send_request($request);

	
if ($response['authed'] == "not authed"){
	header('Location: home.php');
}else{
$ingredientArray = $response['message'];	
}
?>

<html>
<head>
    <title>Landing Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1 class="title">Your Digital Fridge</h1>
        <p class="welcome-message">Welcome <?php echo $_SESSION['name']; ?>, what's in your Fridge?</p>
        <form action="testRabbitMQClient.php" method="POST">
        <div class="input-group">
            <div class="dropdown">
                <input value = '' type="text" name = "ingredientInput" id="ingredientInput" class="input-field" oninput="filterIngredients()" placeholder="Search for ingredients...">
		<div id="ingredientDropdown" class="dropdown-content">
                    <?php
			foreach($ingredientArray as $item){
				echo "<a href='#' onclick=\"fillInput('$item')\">$item</a>";
			}

                    ?>
                </div>
            </div>
        </div>
	<button type="submit" name = "addFridge" class="submit-button">Add to Fridge</button>
        <button type="submit" name="logout" class="logout-button">Logout</button>
        <button type="submit" name="openFridge" class="submit-button">Open Fridge</button>
                <button type="submit" name="getRecipes" class="submit-button">Get Recipes</button>
        </form>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <script>
        function filterIngredients() {
            var input, filter, dropdown, options, i, txtValue;
            input = document.getElementById("ingredientInput");
            filter = input.value.toUpperCase();
            dropdown = document.getElementById("ingredientDropdown");
            options = dropdown.getElementsByTagName("a");
            for (i = 0; i < options.length; i++) {
                txtValue = options[i].textContent || options[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                } else {
                    options[i].style.display = "none";
                }
            }
            dropdown.classList.add("show");
        }

        function fillInput(value) {
            document.getElementById("ingredientInput").value = value;
            document.getElementById("ingredientDropdown").classList.remove("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.input-field')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
