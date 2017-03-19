//draft php script for processing user input fields into an sqlite3 database for demo, will need to convert into mysqli syntax for further functionality
//todo : validation and statement prepartion


<?php
// <html>
// <body>
// <form action="index.html" method="get">

$servername = "mydbinstance.cdmmi6bseeqd.us-west-2.rds.amazonaws.com:3306";
$username = "sma";
$password = "ruhacks2017";
$dbname = "";
//mysql -h mydbinstance.cdmmi6bseeqd.us-west-2.rds.amazonaws.com -P 3306 -u sma -p

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE harassment";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$tableNames = array("HARASSMENT_INCIDENCE", "HARASSMENT_TYPE", "PERP_INFO", "CASE_INFO");
$sqlStatement = array();

$sqlStatement[0] =<<<EOF
  CREATE TABLE $tableNames[0]
  (ID INTEGER PRIMARY KEY AUTOINCREMENT,
  incidenceDescription  TEXT NOT NULL,
  locationType          TEXT NOT NULL,
  relationToReporter    TEXT NOT NULL
);
EOF;

$sqlStatement[1] =<<<EOF
  CREATE TABLE $tableNames[1]
  (ID INTEGER PRIMARY KEY AUTOINCREMENT,
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
  (ID INTEGER PRIMARY KEY AUTOINCREMENT,
  gender       TEXT NOT NULL,
  age          INTEGER NOT NULL,
  sexuality    TEXT NOT NULL,
  ethnicity    TEXT NOT NULL
);
EOF;

$sqlStatement[3] =<<<EOF
  CREATE TABLE $tableNames[3]
  (ID INTEGER PRIMARY KEY AUTOINCREMENT,
  gender       TEXT NOT NULL,
  age          INTEGER NOT NULL,
  sexuality    TEXT NOT NULL,
  ethnicity    TEXT NOT NULL
);
EOF;


for ($i = 0; $i < count($tableNames); $i++){

  //dropping tables if they already exist
  $drop = $db->query("DROP table IF exists $tableNames[$i]");

  if(!$drop){
    echo $db->lastErrorMsg(), "\n";
  }
  else {
    echo "Table $tableNames[$i] successfully dropped \n";
  }

  //creating tables
  $create = $db->exec($sqlStatement[$i]);

  if(!$create){
    echo $db->lastErrorMsg(), "\n";
  }
  else {
    echo "Table $tableNames[$i] created successfully \n";
  }
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
  INSERT INTO $tableNames[0] (incidenceDescription, locationType, relationToReporter)
  VALUES (:incidenceDescription,:locationType,:relationToReporter);
EOF;
$prep = $db->prepare($insertStatement);

$prep->bindParam(':incidenceDescription',$incidenceDescription,SQLITE3_TEXT);
$prep->bindParam(':relationToReporter',$relationToReporter,SQLITE3_TEXT);
$prep->bindParam(':locationType',$locationType,SQLITE3_TEXT);

if (isset($_POST['submit'])){
  if(isset($_POST['radioR'])){
    $relationToReporter = $_POST['radioR'];
  }
}

$incidenceDescription= $_POST["incidenceDescription"];
$locationType= $_POST["location"];

$insert = $prep->execute();

if (!$insert){echo $db->lastErrorMsg(), "\n";}
else {echo "Record for Harassment Incidence inserted successfully \n";}

//grabbing and inserting user input fields into harassment type

$insertStatement=<<<EOF
  INSERT INTO $tableNames[1] (sexual, workplace, street,school,online,religious)
  VALUES (:typeSexual,:typeWorkplace,:typeStreet,:typeSchool,:typeOnline,:typeReligious);
EOF;
$prep = $db->prepare($insertStatement);

$prep->bindParam(':typeSexual',$typeSexual,SQLITE3_INTEGER);
$prep->bindParam(':typeWorkplace',$typeWorkplace,SQLITE3_INTEGER);
$prep->bindParam(':typeStreet',$typeStreet,SQLITE3_INTEGER);
$prep->bindParam(':typeSchool',$typeSchool,SQLITE3_INTEGER);
$prep->bindParam(':typeOnline',$typeOnline,SQLITE3_INTEGER);
$prep->bindParam(':typeReligious',$typeReligious,SQLITE3_INTEGER);

$typeSexual = $_POST['checkSexual'];
$typeWorkplace = $_POST['checkWork'];
$typeStreet = $_POST['checkStreet'];
$typeSchool = $_POST['checkSchool'];
$typeOnline = $_POST['checkOnline'];
$typeReligious = $_POST['checkReligious'];

$insert = $prep->execute();
if (!$insert){echo $db->lastErrorMsg(), "\n";}
else {echo "Record for HARASSMENT_TYPE inserted successfully \n";}

//grabbing and inserting user input fields into perp info

/* Irrelevant data?
$insertStatement=<<<EOF
  INSERT INTO $tableNames[2] (gender,age,sexuality,ethnicity)
  VALUES (:perpGender,:perpAge,:perpSexuality,:perpEthnicity);
EOF;
$prep = $db->prepare($insertStatement);
$prep->bindParam(':perpGender',$perpGender,SQLITE3_TEXT);
$prep->bindParam(':perpAge',$perpAge,SQLITE3_INTEGER);
$prep->bindParam(':perpSexuality',$perpSexuality,SQLITE3_TEXT);
$prep->bindParam(':perpEthnicity',$perpEthnicity,SQLITE3_TEXT);

$perpGender = $_POST[];
$perpAge = $_POST[];
$perpSexuality = $_POST[];
$perpEthnicity = $_POST[];

$insert = $prep->execute();

if (!$insert){echo $db->lastErrorMsg(), "\n";}
else {echo "Record for PERP_INFO inserted successfully \n";}
*/

//grabbing and inserting user input fields into case info

$insertStatement=<<<EOF
  INSERT INTO $tableNames[3] (gender,age,sexuality,ethnicity) VALUES (:caseGender,:caseAge,:caseSexuality,:caseEthnicity);
EOF;
$prep = $db->prepare($insertStatement);

$prep->bindParam(':caseGender', $caseGender, SQLITE3_TEXT);
$prep->bindParam(':caseAge',$caseAge,SQLITE3_INTEGER);
$prep->bindParam(':caseSexuality',$caseSexuality,SQLITE3_TEXT);
$prep->bindParam(':caseEthnicity',$caseEthnicity,SQLITE3_TEXT);

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
$insert = $prep->execute();

if (!$insert){echo $db->lastErrorMsg(), "\n";}
else {echo "Record for CASE_INFO inserted successfully \n";}


echo "Closing database \n";
$db->close();


?>


<!-- </form>

</body>
</html> -->
