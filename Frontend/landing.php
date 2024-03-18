<?php /*
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
	
$request = array();
$request['type'] = "Auth";
$request['destination'] = "database";
$request['username'] = $_SESSION['name'];
$request['message'] = 'Authorizing Token'; 

$response = $client->send_request($request);
if ($response['message'] != "authed"){
	header('Location: home.php');
} */
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
        <div class="input-group">
            <div class="dropdown">
                <input type="text" id="ingredientInput" class="input-field" oninput="filterIngredients()" placeholder="Search for ingredients...">
                <!-- Placeholder options, will eventually add to "Fridge" table -->
				<div id="ingredientDropdown" class="dropdown-content">
                    <a href="#" onclick="fillInput('Option A')">Option A</a>
                    <a href="#" onclick="fillInput('Option B')">Option B</a>
                    <a href="#" onclick="fillInput('Option C')">Option C</a>
                    <a href="#" onclick="fillInput('Option D')">Option D</a>
                    <a href="#" onclick="fillInput('Option E')">Option E</a>
                    <a href="#" onclick="fillInput('Option F')">Option F</a>
                    <a href="#" onclick="fillInput('Option G')">Option G</a>
                    <a href="#" onclick="fillInput('Option H')">Option H</a>
                    <a href="#" onclick="fillInput('Option I')">Option I</a>
                    <a href="#" onclick="fillInput('Option J')">Option J</a>
                </div>
            </div>
            <input type="number" id="quantity" name="quantity" class="input-field" placeholder="QTY" min="0" max="99">
        </div>
        <form action="testRabbitMQClient.php" method="POST">
			<button type="submit" class="submit-button">Add to Fridge</button>
            <button type="submit" name="logout" class="logout-button">Logout</button>
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
