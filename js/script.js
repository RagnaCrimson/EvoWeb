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
                    window.location.href = response.redirectUrl;
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

    // check dup
    $(document).ready(function() {
        $('#V_Name').on('input', function() {
            var name = $(this).val();
            if (name.length > 0) {
                $.ajax({
                    url: 'check_duplicate.php',
                    type: 'GET',
                    data: { name: name },
                    dataType: 'json',
                    success: function(response) {
                        if (response.exists) {
                            $('#duplicateMessage').text('This name already exists in the database.').show();
                        } else {
                            $('#duplicateMessage').text('').hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            } else {
                $('#duplicateMessage').text('').hide();
            }
        });
    });

    // autocomplete
    $(document).ready(function() {
        $('#V_Name').on('input', function() {
            var name = $(this).val();
            var $suggestions = $('#suggestions');
            var offset = $(this).offset();
            var inputWidth = $(this).outerWidth();

            if (name.length > 0) {
                $.ajax({
                    url: 'autocomplete.php',
                    type: 'GET',
                    data: { term: name },
                    dataType: 'json',
                    success: function(response) {
                        $suggestions.empty();
                        if (Array.isArray(response) && response.length > 0) {
                            response.forEach(function(item) {
                                $suggestions.append('<li>' + item + '</li>');
                            });
                            $suggestions.show().css({
                                top: offset.top + $(this).outerHeight(),
                                left: offset.left,
                                width: inputWidth
                            });
                        } else {
                            $suggestions.hide();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                });
            } else {
                $suggestions.hide();
            }
        });

        $(document).on('click', '#suggestions li', function() {
            $('#V_Name').val($(this).text());
            $('#suggestions').hide();
        });

        $(document).click(function(event) {
            if (!$(event.target).closest('#V_Name, #suggestions').length) {
                $('#suggestions').hide();
            }
        });
    });

    // Add more data
    $(document).on("click", "#addMore", function(e){
        e.preventDefault();
    
        // Generate new set of fields for PEAK
        var newPeakFields = `
            <div class="row set row-separator">
                ${Array.from({ length: 12 }, (_, i) => `
                    <div class="h-field">
                        <label class="h-label">เดือน ${i + 1} :</label>
                        <input type="date" name="P_M${i + 1}[]" class="form-control">
                        <input type="number" step="any" placeholder="000.00" name="P_${i + 1}[]" class="form-control">
                    </div>
                `).join('')}
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-danger remove" title="remove"><i class="fa fa-trash"></i> Remove</button>
                </div>
            </div>
        `;
    
        // Generate new set of fields for ELECTRICITY
        var newElectricityFields = `
            <div class="row set row-separator">
                ${Array.from({ length: 12 }, (_, i) => `
                    <div class="h-field">
                        <label class="h-label">เดือน ${i + 1} :</label>
                        <input type="date" name="B_M${i + 1}[]" class="form-control">
                        <input type="number" step="any" placeholder="000.00" name="B_${i + 1}[]" class="form-control">
                    </div>
                `).join('')}
                <div class="col-md-12 text-end">
                    <button type="button" class="btn btn-danger remove" title="remove"><i class="fa fa-trash"></i> Remove</button>
                </div>
            </div>
        `;
    
        // Append new fields to containers
        $("#peakContainer").append(newPeakFields);
        $("#electricityContainer").append(newElectricityFields);
    });
    
    $(document).on("click", ".remove", function(e){
        e.preventDefault();
        $(this).closest('.set').remove();
    });
    
    $(document).on("submit", "#frm", function(e){
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "create_items.php", // Update this to your PHP file
            data: $(this).serialize(),
            success: function(response){
                if (response == "success"){
                    var str = '<div class="alert alert-success">Rows inserted successfully</div>';
                    $(".set:not(:first)").remove();
                    $("#frm")[0].reset();
                } else {
                    var str = '<div class="alert alert-danger">'+response+'</div>';
                }
                $("#msg").html(str);
            }
        });
    });
    
    