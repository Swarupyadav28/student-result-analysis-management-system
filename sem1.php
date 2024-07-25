<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: LightGray;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            background-color: #e98778;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 1.9);
            width: 100%;
            max-width: 600px; /* Adjust as needed */
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .draggable {
            width: 100px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: move;
            user-select: none;
            text-align: center;
            margin-bottom: 20px;
        }

        .dropdown {
            margin-top: 20px;
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .dropdown button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: left; /* Align text to the left */
            width: 100%; /* Full width of the container */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 100%; /* Match the button width */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            z-index: 1;
            top: 100%; /* Position below the button */
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #f1f1f1;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown-content a:last-child {
            border-bottom: none;
        }

        #downloadButton {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
        }

        #downloadButton:hover {
            background-color: #218838;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        select, input[type="submit"] {
            padding: 10px;
            font-size: 16px;
            width: 50%;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        input[type="submit"]:hover {
            background-color: #ff8c00; /* Darker orange color on hover */
        }

        .top-center {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="top-center">
        <h1 style="font-size:35px">Semester Results</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <select name="sem">
                <option value="">Select semester</option>
                <option value="1-1">1-1</option>
                <option value="1-2">1-2</option>
                <option value="2-1">2-1</option>
                <option value="2-2">2-2</option>
                <option value="3-1">3-1</option>
                <option value="3-2">3-2</option>
                <option value="4-1">4-1</option>
                <!-- add more options as needed -->
            </select>
            <input type="submit" value="Submit">
        </form>

        <?php
        // Configuration
        $db_host = 'localhost';
        $db_username = 'root';
        $db_password = '';
        $db_name = '2018_2022';

        // Create connection
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get semester from dropdown
        if (isset($_POST['sem']) && !empty($_POST['sem'])) {
            $sem = $_POST['sem'];
            $tableName = str_replace("-", "_", $sem); // replace - with _ for table name

            // Query to retrieve data from selected semester table
            $query = "SELECT * FROM " . $tableName;
            $result = $conn->query($query);

            // Fetch column names
            $columns = array();
            $columnQuery = "SHOW COLUMNS FROM " . $tableName;
            $columnResult = $conn->query($columnQuery);
            while ($columnRow = $columnResult->fetch_assoc()) {
                $columns[] = $columnRow['Field'];
            }

            // Query to count students
            $countQuery = "SELECT COUNT(*) as student_count FROM " . $tableName;
            $countResult = $conn->query($countQuery);
            $studentCount = $countResult->fetch_assoc()['student_count'];

            // Query to count passed students
            $passQuery = "SELECT COUNT(*) as pass_count FROM " . $tableName . " WHERE RES='P'";
            $passResult = $conn->query($passQuery);
            $passCount = $passResult->fetch_assoc()['pass_count'];

            // Calculate failed students
            $failCount = $studentCount - $passCount;

            echo "<h2>Number of Students Appeared for Exam: " . $studentCount . "</h2>";
            echo "<h2>Number of Students Passed: " . $passCount . "</h2>";
            echo "<h2>Number of Students Failed: " . $failCount . "</h2>";

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                // Display column headers
                echo "<tr>";
                foreach ($columns as $column) {
                    echo "<th>" . $column . "</th>";
                }
                echo "</tr>";
                // Display rows
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>" . $cell . "</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No data found for the selected semester.";
            }
        } else {
            echo "No semester selected.";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>
</html>