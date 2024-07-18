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
                    window.location.href = "dashboard.php";
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


    function updatePeakValues() {
        let totalPeakYear = 0;
        let totalPeakMonth = 0;

        for (let i = 1; i <= 12; i++) {
            let peakValue = parseFloat(document.getElementById('P_' + i).value) || 0;
            totalPeakYear += peakValue;
        }
        totalPeakMonth = totalPeakYear / 12; 

        document.getElementById('V_Peak_year').value = totalPeakYear.toFixed(2);
        document.getElementById('V_Peak_month').value = totalPeakMonth.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 1; i <= 12; i++) {
            document.getElementById('P_' + i).addEventListener('input', updatePeakValues);
        }
    });
    function updateElectricValues() {
        let totalElectricYear = 0;
        let totalElectricMonth = 0;

        for (let i = 1; i <= 12; i++) {
            let electricValue = parseFloat(document.getElementById('B_' + i).value) || 0;
            totalElectricYear += electricValue;
        }

        totalElectricMonth = totalElectricYear / 12;

        document.getElementById('V_Electric_per_year').value = totalElectricYear.toFixed(2);
        document.getElementById('V_Electric_per_month').value = totalElectricMonth.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        for (let i = 1; i <= 12; i++) {
            document.getElementById('B_' + i).addEventListener('input', updateElectricValues);
        }
    });