<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly PEAK and Electricity Values</title>
    <style>
        .container {
            max-width: 100%;
            padding: 20px;
            background-color: #E0F7FA;
        }
        h3 {
            text-align: center;
        }
        .h-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .h-field {
            flex: 0 1 calc(8.33% - 20px); /* 12 fields per row */
            margin: 0 10px;
            box-sizing: border-box;
        }
        .h-label {
            display: block;
            margin-bottom: 5px;
        }
        .h-field input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #B2EBF2;
            border-radius: 5px;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
        .button-container button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #00796B;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>ค่า PEAK ของแต่ละเดือน</h3>
        <div class="h-row">
            <?php for ($i = 1; $i <= 12; $i++) : ?>
                <div class="h-field">
                    <label class="h-label" for="P_M<?php echo $i; ?>">ระบุเดือน <?php echo $i; ?> :</label>
                    <input type="date" id="P_M<?php echo $i; ?>" name="P_M<?php echo $i; ?>">
                    <label class="h-label" for="P_<?php echo $i; ?>">เดือน <?php echo $i; ?> :</label>
                    <input type="number" step="any" placeholder="000.00" id="P_<?php echo $i; ?>" name="P_<?php echo $i; ?>">
                </div>
            <?php endfor; ?>
        </div>

        <h3>ค่าไฟ ของแต่ละเดือน</h3>
        <div class="h-row">
            <?php for ($i = 1; $i <= 12; $i++) : ?>
                <div class="h-field">
                    <label class="h-label" for="B_M<?php echo $i; ?>">ระบุเดือน <?php echo $i; ?> :</label>
                    <input type="date" id="B_M<?php echo $i; ?>" name="B_M<?php echo $i; ?>">
                    <label class="h-label" for="B_<?php echo $i; ?>">เดือน <?php echo $i; ?> :</label>
                    <input type="number" step="any" placeholder="000.00" id="B_<?php echo $i; ?>" name="B_<?php echo $i; ?>">
                </div>
            <?php endfor; ?>
        </div>

        <div class="button-container">
            <button type="submit">Submit</button>
        </div>
    </div>
</body>
</html>
