function login() {
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "login.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    window.location.href = "index.php"; // Redirect to dashboard on successful login
                } else {
                    document.getElementById("error").innerText = "Invalid username or password.";
                }
            } else {
                console.error("Error occurred: " + xhr.status);
            }
        }
    };

    var data = "username=" + username + "&password=" + password;
    xhr.send(data);
}

    // Function to show success message popup
    function showSuccessPopup() {
        alert('Data inserted successfully.');
      }