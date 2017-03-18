//draft php script for processing user input fields into an sqlite3 database
//todo : validation and statement prepartion

<?php
<html>
<body>
<form action="index.html" method="get">

$db_name = 'test.db';
$db = new SQLite3($db_name);
$harassment_incidence = 'HARASSMENT_INCIDENCE';

$tableNames = array("HARASSMENT_INCIDENCE", "HARASSMENT_TYPE", "PERP_INFO", "CASE_INFO");

$sqlStatement = array();

  if(!$db){
    echo $db->lastErrorMsg();
  } else {
    echo "Opened database successfully\n";
  }

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
  age          TEXT NOT NULL,
  sexuality    TEXT NOT NULL,
  ethnicity    TEXT NOT NULL
);
EOF;

$sqlStatement[3] =<<<EOF
  CREATE TABLE $tableNames[3]
  (ID INTEGER PRIMARY KEY AUTOINCREMENT,
  gender       TEXT NOT NULL,
  age          TEXT NOT NULL,
  sexuality    TEXT NOT NULL,
  ethnicity    TEXT NOT NULL
);
EOF;



for ($i = 0; $i < count($tableNames); $i++){

  //dropping tables if they already exist
  $drop = $db->exec("DROP table IF exists $tableNames[$i]");

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

//inserting user input fields into harassment incidence
$prep = $db->prepare($insertStatement);
$insertStatement=<<<EOF
  INSERT INTO $harassmentIncidence(incidenceDescription, locationType, relationToReporter)
  VALUES(?,?,?);
EOF;
$insertStatement->bind_param("sss",$relationToReporter, $incidenceDescription, $locationType);


$relationToReporter = $_POST['relation'];
$incidenceDescription= $_POST['incidenceDescription'];
$locationType= $_POST['location'];

$prep->execute(); //not sure of syntax..

//inserting user input fields into harassment type
$prep = $db->prepare($insertStatement);
$insertStatement=<<<EOF
  INSERT INTO $harassmentType(sexual, workplace, street,school,online,religious)
  VALUES(?,?,?,?,?,?);
EOF;
$insertStatement->bind_param("iiiiii",$typeSexual,$typeWorkplace,$typeStreet, $typeSchool,$typeOnline,$typeReligious) //swap

$typeSexual = $_POST[];
$typeWorkplace = $_POST[];
$typeStreet = $_POST[];
$typeSchool = $_POST[];
$typeOnline = $_POST[];
$typeReligious = $_POST[];


$prep->execute();


//inserting user input fields into perp info
$prep = $db->prepare($insertStatement);
$insertStatement=<<<EOF
  INSERT INTO $perpInfo(gender,age,sexuality,ethnicity)
  VALUES(?,?,?,?;)
EOF;
$insertStatement->bind_param("ssss",$perpGender, $perpAge,$perpSexuality, $perpEthnicity);

$perpGender = $_POST[];
$perpAge = $_POST[];
$perpSexuality = $_POST[];
$perpEthnicity = $_POST[];

$prep->execute();


//inserting user input fields into case info
$prep = $db->prepare($insertStatement);
$insertStatement=<<<EOF
  INSERT INTO $caseInfo(gender,age,sexuality,ethnicity)
  VALUES(?,?,?,?;)
EOF;
$insertStatement->bind_param("ssss",$caseGender, $caseAge,$caseSexuality, $caseEthnicity);

$caseGender = $_POST[];
$caseAge = $_POST[];
$caseSexuality = $_POST[];
$caseEthnicity = $_POST[];

$prep->execute();


  //
  // $harassmentIncidence=$array($relationToReporter, $incidenceDescription,$locationType);
  //
  // $relationToReporter = $_POST['relation'];
  // $incidenceDescription= $_POST['incidenceDescription'];
  // $locationType= $_POST['location'];
  //
  // //escaping to prevent SQL injection
  // $relationToReporter = mysql_real_escape_string($relationToReporter);
  // $incidenceDescription = mysql_real_escape_string($incidenceDescription);
  // $locationType = mysql_real_escape_string($locationType);
  //
  //
  // $harassmentType=$array($typeSexual,$typeWorkplace,$typeStreet, $typeSchool,$typeOnline,$typeReligious);
  //
  // $typeSexual = $_POST[];
  // $typeWorkplace = $_POST[];
  // $typeStreet = $_POST[];
  // $typeSchool = $_POST[];
  // $typeOnline = $_POST[];
  // $typeReligious = $_POST[];
  //
  // //escaping to prevent SQL injection
  // $typeSexual = mysql_real_escape_string($typeSexual);
  // $typeWorkplace=mysql_real_escape_string($typeWorkplace);
  // $typeStreet=mysql_real_escape_string($typeStreet);
  // $typeSchool=mysql_real_escape_string($typeSchool);
  // $typeOnline=mysql_real_escape_string($typeOnline);
  // $typeReligious=mysql_real_escape_string($typeReligious);
  //
  //
  // $perpInfo=$array($perpGender, $perpAge,$perpSexuality, $perpEthnicity);
  //
  // $perpGender = $_POST[];
  // $perpAge = $_POST[];
  // $perpSexuality = $_POST[];
  // $perpEthnicity = $_POST[];
  //
  // //variables for PERP_INFO
  //
  // $caseInfo=$array($caseGender,$caseAge,$caseSexuality,$caseEthnicity);
  //
  // $caseGender = $_POST[];
  // $caseAge = $_POST[];
  // $caseSexuality = $_POST[];
  // $caseEthnicity = $_POST[];
  //



//   $insertStatement=<<<EOF
//     INSERT INTO $harassmentIncidence(incidenceDescription, locationType, relationToReporter)
//     VALUES(
//     'incidence_description',
//     'downtown',
//     'stranger'
//     );
// EOF;





  $insert=$db->exec($insertStatement);
  if (!$insert){
    echo $db->lastErrorMsg(), "\n";
  }
  else {
    echo "Record inserted successfully \n";
  }

  echo "Closing database \n";
  $db->close();
?>
</form>
</html>
</body>
