function changePassword() {
    var oldpassword = document.getElementById('oldpassword').value;
    var newpassword = document.getElementById('newpassword').value;
    var confirmnewpassword = document.getElementById('confirmnewpassword').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "changepassword.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                alert(response.message);
                window.location.href = "login.html"; // Redirect to login page after successful password change
            } else {
                document.getElementById('changepassworderror').innerText = response.message;
            }
        }
    };

    var data = "oldpassword=" + encodeURIComponent(oldpassword) + "&newpassword=" + encodeURIComponent(newpassword) + "&confirmnewpassword=" + encodeURIComponent(confirmnewpassword);
    xhr.send(data);
}
