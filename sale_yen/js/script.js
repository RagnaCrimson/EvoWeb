
    // Function to show success message popup
    function showSuccessPopup() {
        alert('Data inserted successfully.');
      }

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
                            $('#duplicateMessage').text('มีหน่วยงานนี้ในระบบแล้ว.').show();
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