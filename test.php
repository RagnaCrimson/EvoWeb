<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <style>
        .scroll-container {
            overflow-x: auto;
            white-space: nowrap;
        }
        .scroll-container .row {
            display: inline-block;
            white-space: nowrap;
        }
        .h-field {
            display: inline-block;
            width: 200px; /* Adjust width as needed */
            margin-right: 10px;
        }
    </style>
    <title>Add Multiple Rows in Database</title>
</head>
<body>
    <div class="container">
        <h1>Add Multiple Rows in Database</h1>
        <div id="msg">
            <!-- display message -->
        </div>
        <form action="" id="frm">
            <h3>ค่า PEAK ของแต่ละเดือน</h3>
            <div class="scroll-container" id="peakContainer">
                <!-- Initial set of fields -->
                <div class="row set">
                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                        <div class="h-field">
                            <label class="h-label" for="P_<?php echo $i; ?>">เดือน <?php echo $i; ?> :</label>
                            <input type="date" id="P_M<?php echo $i; ?>" name="P_M<?php echo $i; ?>[]" class="form-control">
                            <input type="number" step="any" placeholder="000.00" id="P_<?php echo $i; ?>" name="P_<?php echo $i; ?>[]" class="form-control">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <h3>ค่าไฟ ของแต่ละเดือน</h3>
            <div class="scroll-container" id="electricityContainer">
                <!-- Initial set of fields -->
                <div class="row set">
                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                        <div class="h-field">
                            <label class="h-label" for="B_<?php echo $i; ?>">เดือน <?php echo $i; ?> :</label>
                            <input type="date" id="B_M<?php echo $i; ?>" name="B_M<?php echo $i; ?>[]" class="form-control">
                            <input type="number" step="any" placeholder="000.00" id="B_<?php echo $i; ?>" name="B_<?php echo $i; ?>[]" class="form-control">
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="col-md-12 text-end">
                <button type="button" id="addMore" class="btn btn-primary">Add More</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).on("click", "#addMore", function(e){
            e.preventDefault();
            
            // Generate new set of fields
            var newFields = `
                <div class="row set">
                    ${Array.from({ length: 12 }, (_, i) => `
                        <div class="h-field">
                            <label class="h-label">เดือน ${i + 1} :</label>
                            <input type="date" name="P_M${i + 1}[]" class="form-control">
                            <input type="number" step="any" placeholder="000.00" name="P_${i + 1}[]" class="form-control">
                        </div>
                    `).join('')}
                </div>
                <div class="row set">
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
            $("#peakContainer").append(newFields);
            $("#electricityContainer").append(newFields);
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
    </script>
</body>
</html>