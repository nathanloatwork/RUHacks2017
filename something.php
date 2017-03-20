//draft php script for processing user input fields into an sqlite3 database
//todo : validation and statement prepartion

//need to change syntax from sqlite3 to mysqli

<?php
// <html>
// <body>
// <form action="index.html" method="get">

$servername = "mydbinstance.cdmmi6bseeqd.us-west-2.rds.amazonaws.com:3306";
$username = "sma";
$password = "ruhacks2017";
$dbname = "bif";
//mysql -h mydbinstance.cdmmi6bseeqd.us-west-2.rds.amazonaws.com -P 3306 -u sma -p

$db = mysqli_connect($servername, $username, $password, $dbname) or die('Error connecting to MySQL server.');

// Create database


//$sql = "CREATE DATABASE harassment;";
//mysqli_query($conn, $sql) or die('Error creating database');
// if ($conn->mysqli_query($sql) === TRUE) {
//     echo "Database created successfully";
// } else {
//     echo "Error creating database: " . $conn->error;
// }

$tableNames = array("HARASSMENT_INCIDENCE", "HARASSMENT_TYPE", "PERP_INFO", "CASE_INFO");
$sqlStatement = array();

$sqlStatement[0] =<<<EOF
  CREATE TABLE $tableNames[0]
  (ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  incidenceDescription  TEXT NOT NULL,
  locationType          TEXT NOT NULL,
  relationToReporter    TEXT NOT NULL
);
EOF;

$sqlStatement[1] =<<<EOF
  CREATE TABLE $tableNames[1]
  (ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  sexual    INTEGER NOT NULL,
  workplace INTEGER NOT NULL,
  street    INTEGER NOT NULL,
  school    INTEGER NOT NULL,
  online    INTEGER NOT NULL,
  religious INTEGER NOT NULL
);
EOF;

$sqlStatement[2] =<<<EOF
  CREATE TABLE $tableNames[2]
  (ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  gender       TEXT NOT NULL,
  age          INTEGER NOT NULL,
  sexuality    TEXT NOT NULL,
  ethnicity    TEXT NOT NULL
);
EOF;

$sqlStatement[3] =<<<EOF
  CREATE TABLE $tableNames[3]
  (ID INTEGER PRIMARY KEY AUTO_INCREMENT,
  gender       TEXT NOT NULL,
  age          INTEGER NOT NULL,
  sexuality    TEXT NOT NULL,
  ethnicity    TEXT NOT NULL
);
EOF;


for ($i = 0; $i < count($tableNames); $i++){

  //dropping tables if they already exist
    $drop="DROP table IF exists $tableNames[$i];";
  mysqli_query($db,$drop) or die("Error dropping table.");


  //creating tables

  $create = "$sqlStatement[$i];";
  mysqli_query($db, $create) or die ("Error creating table.");

  // if(!$create){
  //   echo $db->lastErrorMsg(), "\n";
  // }
  // else {
  //   echo "Table $tableNames[$i] created successfully \n";
  // }
}

//want to create a an array of array so when the user submits the information,
//we can create insert statements without having to create a for loop for each table
// $AoA = array(
// array($relationToReporter, $incidenceDescription,$locationType), //harassment incidence
// array($typeSexual,$typeWorkplace,$typeStreet, $typeSchool,$typeOnline,$typeReligious), //harassment type
// array($perpGender, $perpAge,$perpSexuality, $perpEthnicity), //perp info
// array($caseGender,$caseAge,$caseSexuality,$caseEthnicity) //case info
// );

//need some form validation to prevent SQL injection...

//grabbing and inserting user input fields into harassment incidence

$insertStatement=<<<EOF
  INSERT INTO $tableNames[0] (incidenceDescription, locationType, relationToReporter) VALUES (?,?,?);
EOF;

$prep=$db->prepare($insertStatement);
$prep->bind_param("sss",$relationToReporter, $incidenceDescription,$locationType);

// if (isset($_POST['submit'])){
//   if(isset($_POST['radioR'])){
//     $relationToReporter = $_POST['radioR'];
//   }
// }
// $incidenceDescription= $_POST["incidenceDescription"];
// $locationType= $_POST["location"];

$relationToReporter="hi";
$incidenceDescription="hi";
$locationType="hi";

$prep->execute() or die ("insert error for harassment incidence failed");

//grabbing and inserting user input fields into harassment type

$insertStatement=<<<EOF
  INSERT INTO $tableNames[1] (sexual, workplace, street,school,online,religious) VALUES (?,?,?,?,?,?);
EOF;
$prep = $db->prepare($insertStatement);
$prep->bind_param("iiiiii",$typeSexual,$typeWorkplace,$typeStreet, $typeSchool,$typeOnline,$typeReligious);

//
// $typeSexual = $_POST['checkSexual'];
// $typeWorkplace = $_POST['checkWork'];
// $typeStreet = $_POST['checkStreet'];
// $typeSchool = $_POST['checkSchool'];
// $typeOnline = $_POST['checkOnline'];
// $typeReligious = $_POST['checkReligious'];

$typeSexual = 1;
$typeWorkplace = 1;
$typeStreet = 1;
$typeSchool = 1;
$typeOnline = 1;
$typeReligious = 1;

$prep->execute() or die ("insert error for harassment types failed");


//grabbing and inserting user input fields into case info

$insertStatement=<<<EOF
  INSERT INTO $tableNames[3] (gender,age,sexuality,ethnicity)
  VALUES (?,?,?,?);
EOF;
$prep = $db->prepare($insertStatement);

$prep->bind_param(':caseGender', $caseGender, SQLITE3_TEXT);
$prep->bind_param(':caseAge',$caseAge,SQLITE3_INTEGER);
$prep->bind_param(':caseSexuality',$caseSexuality,SQLITE3_TEXT);
$prep->bind_param(':caseEthnicity',$caseEthnicity,SQLITE3_TEXT);

if (isset($_POST['submit'])) {
  if(isset($_POST['radio'])){
    $caseGender=$_POST['radio'];
  }

  if(isset($_POST['radioE'])){
    $caseEthnicity=$_POST['radioE'];
  }
}

$caseAge = $_POST['Age'];
$caseSexuality = $_POST['orientation'];
$insert = $prep->mysqli_queryute();

if (!$insert){echo $db->lastErrorMsg(), "\n";}
else {echo "Record for CASE_INFO inserted successfully \n";}


echo "Closing database \n";
$db->close();


?>


<!-- </form>

</body>
</html> -->
