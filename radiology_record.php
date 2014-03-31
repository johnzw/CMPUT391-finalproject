<?php
include("InsertRecord.php");
?>
<?php
include("ImageConvertor.php");
?>

<?php
	if($_COOKIE['class']!='r'){
		header("Location: index.html");
	}
   //this php file collect all the information needed for the database
	//only execute when submit button is clicked 
	//We assume user enter all the right information
	//we assume only jpeg files uploaded
	$flag = '';
	
	//check all the input
	if(isset($_POST['validate'])){
		
		//get the input information
		if(empty($_POST['patient_id'])) {
			$patient_idErr = "Patient ID is required";
			$flag = true;
		}
		else {$patient_id = $_POST['patient_id'];}
		
		if(empty($_POST['doctor_id'])) {
			$doctor_idErr = "Doctor ID is required";
			$flag = true;
		}
		else {$doctor_id = $_POST['doctor_id'];}
		
		if(empty($_POST['test_type'])) {
			$test_typeErr = "Test type is required";
			$flag = true;
		}
		else {$test_type = $_POST['test_type'];}
		
		if(empty($_POST['p_date'])) {
			$p_dateErr = "Prescribe date is required";
			$flag = true;
		}
		else {$p_date = $_POST['p_date'];}
		
		if(empty($_POST['t_date'])) {
			$t_dateErr = "Test date is required";
			$flag = true;
		}
		else {$t_date = $_POST['t_date'];}		

		if(empty($_POST['diagnosis'])) {
			$diagnosisErr = "Diagnosis is required";
			$flag = true;
		}
		else {$diagnosis = $_POST['diagnosis'];}	
		
		if(empty($_POST['description'])) {
			$descriptionErr = "Discription is required";
			$flag = true;
		}
		else {$description = $_POST['description'];	}	
			
		
		//checking if files has uploaded
		if(empty($_FILES['upload']['name'][0])){
			$fileErr = "There is no file uploaded";
			$flag = true;
		}
		else {
			foreach($_FILES['upload']['size'] as $item){
				if($item > 50000000) {
					$fileErr = "File too big!!";
					$flag = true;
				}
			}
			
			foreach($_FILES['upload']['type'] as $item){
				if(($item!="image/jpeg")
				&& ($item!="image/jpg")
				&& ($item!="image/pjpeg")
				&& ($item!="image/png")
				&& ($item!="image/gif")) 
				{
					$fileErr = "File type not expected";
					$flag = true;		
				}
			}	
			
		}
		
		
		
		//give radiologist its id
		if($flag != true) {
			$radiologist_id = $_COOKIE["id"];
		
			//insert the record into the table
			$record_id = insertRecord($patient_id, $doctor_id, $radiologist_id, $test_type,
                     $p_date, $t_date, $diagnosis, $description);
      
      	
     	 	foreach($_FILES['upload']['tmp_name'] as $item){
      		
      		//convert the fullsize image into thumbnail and regular size
      		//the function is contained in the "ImageConvertor.php"
      		$pic_reguler = createThumbsFromJPG($item, 200);
    			$pic_thumbnail = createThumbsFromJPG($item,60);
    			
    			//insert the images into the table
    			//the function is contained in the "InsertRecord.php"
    			insertIMG($record_id, 1, $pic_thumbnail,$pic_reguler,$item);		
      	}
      	echo "Success";
      }
		
		
	}
?>
<!doctype html>
<html>
	<center>
		<br>
		<h1> Radiology Record</h1>
		<form id="form" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
		<label>Patient ID: </label> <input type="text" name="patient_id"><span style="color:red">* <?php echo $patient_idErr;?></span><br><br>
		<label>Doctor ID: </label> <input type="text" name="doctor_id"><span style="color:red">* <?php echo $doctor_idErr;?></span><br><br>
		<label>Test type: </label> <input type="text" name="test_type"><span style="color:red">* <?php echo $test_typeErr;?></span><br><br>
		<label>Prescribe Date: </label> <input type="date" name="p_date"><span style="color:red">* <?php echo $p_dateErr;?></span><br><br>
		<label>Test Date: </label><input type="date" name="t_date"><span style="color:red">* <?php echo $t_dateErr;?></span><br><br>
		
		<label for="diagnosis">Diagnosis: </label>
		<textarea form = "form" id="diagnosis" rows="4" cols="32" name="diagnosis"></textarea><span style="color:red">* <?php echo $diagnosisErr;?></span><br><br>
		
		<label for="description">Description: </label>
		<textarea form = "form" id="description" rows="16" cols="64" name="description"></textarea><span style="color:red">* <?php echo $descriptionErr;?></span><br><br>
		<span style="color:red">* <?php echo $fileErr;?></span><input type="file" name="upload[]" multiple>
		<input type="submit" value="Submit" name="validate">
		</form>
	</center>	
</html>
