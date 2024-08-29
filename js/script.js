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

    // pop up on status_view.php
    $(document).ready(function() {
        // View button functionality
        $('.view-btn').click(function() {
            var viewId = $(this).data('id');
            $('#modal-body').load('status/view_data.php', { view_id: viewId }, function() {
                $('#myModal').modal('show');
            });
        });

        // Sort functionality
        var sortOrder = 'asc'; // Default sort order
        $('#sortSale').click(function() {
            var table = $('#data');
            var rows = table.find('tr:gt(1)').toArray().sort(compareRows);
            if (sortOrder === 'asc') {
                rows = rows.reverse();
                sortOrder = 'desc';
            } else {
                sortOrder = 'asc';
            }
            $.each(rows, function(index, row) {
                table.append(row);
            });
        });

        function compareRows(a, b) {
            var valA = $(a).find('td').eq(8).text().toUpperCase();
            var valB = $(b).find('td').eq(8).text().toUpperCase();
            return valA.localeCompare(valB);
        }
    });
    
    