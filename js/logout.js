function confirmLogout() {
    var confirmed = confirm("Are you sure you want to logout?");
    if (confirmed) {
        window.location.href = "logout.php";
    }
}