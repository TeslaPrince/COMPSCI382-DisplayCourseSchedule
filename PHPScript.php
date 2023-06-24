<?php
    createPageHeader();
    echo "<h1>Schedule of Classes</h1>";
    displayLinks();
    $filename = "fall22schedule.txt";
    $fp = openFile($filename, "r");
    if (!$fp) {
        echo "Could not open the {$filename} file.";
        exit();
    }
    $selection = "all";
    if (isset($_GET["selection"])) {
        $selection = trim($_GET["selection"]);
    }
    if ($selection === "all") {
        $classList = getAllClasses($fp);
    }
    else {
        $id = "";
        if($selection == "COMPSCI" || $selection == "MATH"){
            $id = "Subject";
        }
        elseif($selection == "MG0115" || $selection == "MG0125" || $selection == "HY0210"){
            $id = "Location";
        }
        else{
            $id = "Unknown";
        }
        $classList = getSelectedClasses($fp, $selection, $id);
    }
    $columnLabels = ["Class", "Section", "Instructor", "Time", "Location"]; 
    createTableHeader($columnLabels);
    displayMovieList($classList);
    createTableFooter();
    echo "</body></html>";
    fclose($fp);
    function createPageHeader() {
?>
<!doctype html>
    <html>
        <head>
            <title>Schedule of Classes</title>
            <style type="text/css">
                table { 
                    border: 1px solid black; 
                    border-collapse: collapse; 
                    background: #fff; 
                }
                th, td { 
                    border: 1px solid black; 
                    padding: .2em .7em;   
                    color: #000;
                    font-size: 16px; 
                    font-weight: 400; 
                } 
                thead th { 
                    background-color: #1A466A; 
                    color: #fff; 
                    font-weight: bold;  
                }
                .link {
                    padding: 10px 20px;
                }
                html{
                    font-family: "Lucinda Console", monospace;
                }
            </style>
        </head>
        <body>
<?php
    }
    function openFile($filename, $mode) {
        $fp = fopen($filename, $mode);
        if (!$fp) {
            return false;
        }
        return $fp;    
    }
    function getAllClasses($fp) {
        $list = [];
        while ($class = fgetcsv($fp, 255, ',')) {
            $list[] = $class;
        }
        return $list;
    }
    function createTableHeader($columnLabels) {
?>
            <table>
                <thead>
                    <tr>
                    <?php
                        foreach($columnLabels as $label) {
                            echo "<th>{$label}</th>";
                        }
                    ?>
                    <tr>
                </thead>
                <tbody>
<?php
    }
    function displayMovieList($list) {
        foreach($list as $class) {
            echo "<tr>";
                echo "<td>{$class[1]}{$class[2]}</td>";
                echo "<td>{$class[3]}</td>";
                echo "<td>{$class[5]}</td>";
                echo "<td>{$class[4]}</td>";
                echo "<td>{$class[6]}</td>";
            echo "</tr>";
        }
    }
    function createTableFooter() {
            echo "</tbody></table>";
    }
    function displayLinks() {
?>
            <p>
                <span class="link"><a href="PHPScript.php?selection=all">All Classes</a></span>
                <h2>Filter By Subject</h2>
                    <span class="link"><a href="PHPScript.php?selection=COMPSCI">COMPSCI</a></span>
                    <span class="link"><a href="PHPScript.php?selection=MATH">MATH</a></span>
                <h2>Filter by Location</h2>
                    <span class="link"><a href="PHPScript.php?selection=MG0115">MG0115</a></span>
                    <span class="link"><a href="PHPScript.php?selection=MG0125">MG0125</a></span>
                    <span class="link"><a href="PHPScript.php?selection=HY0210">HY0210</a></span>
            </p>
<?php
    }
    function getSelectedClasses($fp, $selection, $id) {
    echo $selection . "\n" . $id;
        $list = [];
        while ($class = fgetcsv($fp, 255, ',')) {
            if($id == "Subject"){
                if (trim($class[1]) == $selection) {
                    $list[] = $class;
                }
            }
            elseif($id == "Location"){
                if (trim($class[6]) == $selection) {
                    $list[] = $class;
                }
            }
        }
        return $list;
    }
?>