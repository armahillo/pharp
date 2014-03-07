<?php
function __autoload($class_name) {
    require_once("./lib/$class_name.php");
}

// This is an old DBO I wrote that is just propping
// the DB connection part up for now. It should be re-written.
$db = new Mysql("localhost","username","TOTALLYHARDTOGUESSPASSWORD","schema");
$db->connect();
$db->select();

// Assign the DBH to the base model
ActiveRecord::set_db($db);
// The table name is set in the model class itself.

  ///////////////////////////////
 // DEMO ///////////////////////
///////////////////////////////

// Let's create a record
// Instantiate a new model -- this is held in memory for now.
$s1 = new SampleModel(array("name" => "Foo", "keyword" => "bar", "size" => "5"));
// Oops! did I say "bar"? I meant "baz"
$s1->keyword = "baz";

// Ok, let's save it
$s1->save();

// There's an ID for it now!
echo $s1->id; // ID: 1, for example

// Let's create a couple more records
$s2 = new SampleModel(array("name" => "celery", "keyword" => "green", "size" => "2"));
$s2->save(); // ID 2
$s3 = new SampleModel(array("name" => "dog", "keyword" => "sixty", "size" => "8"));
$s3->save(); // ID 3

// OK, now what if we want the last one created?
SampleModel::last(); // -> record with ID 3

// Or the first?
SampleModel::first(); // -> record with ID 1

// How about we just want to find one by the ID #?
SampleModel::find(2); // -> record with ID 2

// OK no, we want ALL of them
SampleModel::all(); // will return an array of all records


?>
