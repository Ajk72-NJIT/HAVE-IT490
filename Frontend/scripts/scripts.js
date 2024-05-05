function filterIngredients() {
    var input, filter, options, i, txtValue;
    input = document.getElementById("ingredientInput");
    filter = input.value;
    options = document.querySelectorAll(".dropdown a");
    for (i = 0; i < options.length; i++) {
        txtValue = options[i].textContent || options[i].innerText;
        if (txtValue.indexOf(filter) > -1) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
    document.querySelector('.dropdown-content').classList.add("show");
}


function fillInput(value) {
    document.getElementById("ingredientInput").value = value;
    document.querySelector('.dropdown-content').classList.remove("show");
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
