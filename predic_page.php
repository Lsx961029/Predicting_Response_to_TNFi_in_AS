<!DOCTYPE HTML>
<html>
<head>
<meta content="text/html; charset=utf-8" />
<title>Predicting_Response_to_TNFi_in_AS</title>
</head>

<script type="text/javascript">
	window.onload=function(){
		hide("HIDE_CALC_BMI_BUTTON");
		hide("CALC_BMI");
		hide("HIDE_CALC_BASFI_BUTTON");
		hide("CALC_BASFI");

	}

	function show_cal_bmi_button_click() {
		hide("SHOW_CALC_BMI_BUTTON");
		display("HIDE_CALC_BMI_BUTTON");
		display("CALC_BMI");
	}

	function hide_calc_bmi_button_click() {
		hide("HIDE_CALC_BMI_BUTTON");
		hide("CALC_BMI");
		display("SHOW_CALC_BMI_BUTTON");
	}


	function calc_bmi() {
		var hUnit = document.getElementById("HUNIT").value;
		var wUnit = document.getElementById("WUNIT").value;
		var h = document.getElementById("HEIGHT").value;
		var w = document.getElementById("WEIGHT").value;
		if(h < 0){
			alert("Height must be positive!")
			return;
		}
		if(w < 0){
			alert("Weight must be positive!")
			return;
		}
		if(hUnit=="0"){
			alert("Please choose an unit to use.")
			return;
		}
		if(wUnit=="0"){
			alert("Please choose an unit to use.")
			return;
		}

		if(hUnit=="in"){
			h=0.0254*h;
		}
		if(wUnit=="lb"){
			w=0.453592*w
		}
		var result=w/(h*h);
		document.getElementById("BMI").value=result;
	}

	function show_cal_basfi_button_click() {
		hide("SHOW_CALC_BASFI_BUTTON");
		display("HIDE_CALC_BASFI_BUTTON");
		display("CALC_BASFI");
	}

	function hide_calc_basfi_button_click() {
		hide("HIDE_CALC_BASFI_BUTTON");
		hide("CALC_BASFI");
		display("SHOW_CALC_BASFI_BUTTON");
	}

	function calc_basfi() {
		var arr = new Array();
		var tmp;
		var result = 0.0;
		for (var i = 1; i <= 10; i++){
			tmp="BFI_"+i;
			arr[i]=parseFloat(document.getElementById(tmp).value);
			if(arr[i]<0 || arr[i]>10){
				alert("ERROR: "+i+") range 0 - 10 !!!")
        return;
			}else if(!Number.isInteger(arr[i])){
        alert("ERROR: "+i+") Please enter an integer")
        return;
      }else{
        result = result + arr[i];
      }
		}
		result = result/10;
		document.getElementById("BASFI").value = result;
		}

	function display(id) {
        var target=document.getElementById(id);
		target.style.display='';
    }

	function hide(id) {
		var target=document.getElementById(id);
		target.style.display='none';
   }

	function send_button_click() {
    var str = document.getElementById("MAIL_CONTENT").value;
    if (str != document.getElementById("MAIL_CONTENT").placeholder) {
      window.open("mailto:ymr35625@gmail.com?subject=Feedback&body="+str, target="_self");
    }
  }

	function validateForm()
	{
		var arry=['AGE',"Gender",'CRP_L','BMI','BASFI','OUTCOME_TYPE'];
		for(var element of arry){
			var x = document.getElementById(element).value
			if(x==null || x==""){
				alert("Please fill required field: " + element);
				return false;
			}
		}
	}

  function formReset()
  {
      alert('The form will be reset');
      document.getElementById("AGE").value="";
      document.getElementById("Gender").options[0].selected = true
      document.getElementById("OUTCOME_TYPE").options[0].selected = true
      document.getElementById("MedicaRecords").value = document.getElementById("MedicaRecords").placeholder;
      document.getElementById("CRP_L").value="";
      document.getElementById("BMI").value="";
      document.getElementById("BASFI").value="";
      document.getElementById("PGA").value=5;
      document.getElementById("PGA_NUM").value=5;
      document.getElementById("BASDAI_2").value=5;
      document.getElementById("BASDAI_2_NUM").value=5;
  }


</script>

<?php
// error_reporting(E_ALL);
error_reporting(0);
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;
// require '/usr/share/php/libphp-phpmailer/autoload.php';
?>

<h1>
Predicting Short Term Response to TNF inhibitors in Patients with Ankylosing Spondylitis
</h1>

<p>developed by <a href="https://www.columbiadoctors.org/runsheng-wang-md">Runsheg Wang MD</a>, et al</p>

<fieldset>
	<p>THIS IS THE README FILE FOR THE USER.</p>
</fieldset><br>


<body style="background-color:LightCyan;">
<fieldset class="cont">

	<div class="list">
		<label>*MODEL: Logistic Regression Model</label>
		<br>
		<label>*MODEL TYPE: Reduced MODEL</label>
	</div>

<form name="myArgs" action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="post" >

		<h2>Please enter:</h2>

		<div class="list" id="AGE_d">
			<label>* Your Age:</label>
			<input type="number" name="AGE" id="AGE" min="18" max="99" value="<?php echo $_POST['AGE']?>">
		</div><br>

		<div class="list" id="Gender_d">
			<label>* Gender:</label>
			<select type="text" name="Gender" id="Gender" >
				<option value="" <?php if (isset($_POST["Gender"]) && $_POST["Gender"] == "") {echo "selected";}?> >--please select</option>
				<option value="0" <?php if (isset($_POST["Gender"]) && $_POST["Gender"] == "0") {echo "selected";}?> >Female</option>
				<option value="1" <?php if (isset($_POST["Gender"]) && $_POST["Gender"] == "1") {echo "selected";}?> >Male</option>
				<option value="2" <?php if (isset($_POST["Gender"]) && $_POST["Gender"] == "2") {echo "selected";}?> >Androgyne</option>
			</select>
		</div><br>

		<div class="list" id="MedicaRecords_d">
			<label>* Are you currently taking any medications(Optional):</label><br>
			<textarea name="MedicaRecords" rows="4" cols="50" id="MedicaRecords" placeholder="(Optional)"><?php echo $_POST['MedicaRecords']?></textarea>
		</div><br>


		<div class="list" id="CRP_L_d">
			<label>* C-reactive  protein:</label>
			<input type="number" id="CRP_L" name="CRP_L" min="0.01" max="20" step="any" value="<?php echo $_POST['CRP_L']?>"> mg/dL
		</div><br>

		<div class="list" id="BMI_I">
			<label>* BMI:</label>
			<input type="number" name="BMI" id="BMI" min="15" max="55" step="any" value="<?php echo $_POST['BMI']?>">
			<button type="button" id="SHOW_CALC_BMI_BUTTON" name="SHOW_CALC_BMI_BUTTON" onclick="javascript:show_cal_bmi_button_click()">
				Calculate BMI
			</button>
			<button type="button" id="HIDE_CALC_BMI_BUTTON" name="HIDE_CALC_BMI_BUTTON" onclick="javascript:hide_calc_bmi_button_click()">
				Hide BMI Calculator
			</button>
							<div class="list" id="CALC_BMI">
								<label>&nbsp Height:</label>
								<input type="number" id="HEIGHT" name="HEIGHT" min="0" step="any">
								<select name="HUNIT" id = 'HUNIT'>
									<option value="0">--please select a unit</option>
									<option value="m">m</option>
									<option value="in">inch</option>
								</select>
								<br>
								<label>&nbsp Weight:</label>
								<input type="number" id="WEIGHT" name="WEIGHT" min="0" step="any">
								<select name="WUNIT" id = 'WUNIT'>
									<option value="0">--please select an unit</option>
									<option value="kg">kg</option>
									<option value="lb">lb</option>
								</select>

								<button type="button" name="CALC_BMI_BUTTON" onclick="javascript:calc_bmi()">
								Calculate
								</button>
							</div>
		</div><br>

    <div>

      <label style="font-size:16px;">* Patient Global Assessment(range 0-10):</label><br><br>

		<div>
			<label style="font-size:14px; margin-left:15px;"><i>In the past week, how active waqs your ankylosing spondylitis, 0 is least active, 10 is the most active:</i></label><br><br>
			<input type="range" id="PGA" name="PGA" min="0" max="10" style="margin-left:15px; width:400px;" oninput="PGA_NUM.value=PGA.value" value=<?php if(isset($_POST["PGA"])) echo $_POST['PGA']; else echo "5";?>>
			<output id="PGA_NUM" name="PGA_NUM" for="PGA"><?php if(isset($_POST["PGA"])) echo $_POST['PGA']; else echo "5";?></output><br>
		</div><br>

    <label style="font-size:16px;">* BASDAI Q2(range 0-10):</label><br><br>

		<div class="list" id="BASDAI_2_d">
			<label style="font-size:14px; margin-left:15px;"><i>In the past week, how would you describe the overall level of AS neck, back or hip pain you have had?  0 is no pain, 10 is the worst pain:</i></label><br><br>
			<input type="range" id="BASDAI_2" name="BASDAI_2" min="0" max="10" style="margin-left:15px; width:400px;" oninput="BASDAI_2_NUM.value=BASDAI_2.value" value=<?php if(isset($_POST["BASDAI_2"])) echo $_POST['BASDAI_2']; else echo "5";?>>
			<output id="BASDAI_2_NUM" name="BASDAI_2_NUM" for="BASDAI_2"><?php if(isset($_POST["BASDAI_2"])) {echo $_POST['BASDAI_2'];} else {echo "5";}?></output><br>
		</div><br><br>

		<div class="list" id="BASFI_d">
			<label>* BASFI(range 0-10):</label>
			<input type="number" name="BASFI" id="BASFI" min="0" max="10" step="any" value="<?php echo $_POST['BASFI']?>">
			<button type="button" id="SHOW_CALC_BASFI_BUTTON" name="SHOW_CALC_BASFI_BUTTON" onclick="javascript:show_cal_basfi_button_click()">
				I don’t know my BASFI.
			</button>
			<button type="button" id="HIDE_CALC_BASFI_BUTTON" name="HIDE_CALC_BASFI_BUTTON" onclick="javascript:hide_calc_basfi_button_click()">
				Hide BASFI Calculator
			</button>
		</div><br>
  </div>


		<div class="list" id="CALC_BASFI">
			<label>&nbsp From 0 (easiest) to 10 (impossible), please rate your level of ability with each of the following activities during the past week. (range 0 - 10)</label>
			<br><br>
			<label>1)  Putting on your socks or tights without help or aids (e.g sock aid).</label>
			<input type="number" id="BFI_1" name="BFI_1" min="0" max="10" step="any">
			<br>
			<label>2) Bending from the waist to pick up a pen from the floor without aid.</label>
			<input type="number" id="BFI_2" name="BFI_2" min="0" max="10" step="any">
			<br>
			<label>3) Reaching up to a high shelf without help or aids (e.g helping hand).</label>
			<input type="number" id="BFI_3" name="BFI_3" min="0" max="10" step="any">
			<br>
			<label>4) Getting up from an armless chair without your hands or any other help. </label>
			<input type="number" id="BFI_4" name="BFI_4" min="0" max="10" step="any">
			<br>
			<label>5) Getting up off the floor without help from lying on your back.</label>
			<input type="number" id="BFI_5" name="BFI_5" min="0" max="10" step="any">
			<br>
			<label>6) Standing unsupported for 10 minutes without discomfort.</label>
			<input type="number" id="BFI_6" name="BFI_6" min="0" max="10" step="any">
			<br>
			<label>7) Climbing 12-15 steps without using a handrail or walking aid.</label>
			<input type="number" id="BFI_7" name="BFI_7" min="0" max="10" step="any">
			<br>
			<label>8) Looking over your shoulder without turning your body.</label>
			<input type="number" id="BFI_8" name="BFI_8" min="0" max="10" step="any">
			<br>
			<label>9) Doing physically demanding activities (e.g physiotherapy exercises, gardening or sports).</label>
			<input type="number" id="BFI_9" name="BFI_9" min="0" max="10" step="any">
			<br>
			<label>10) Doing a full day's activities whether it be at home or at work.</label>
			<input type="number" id="BFI_10" name="BFI_10" min="0" max="10" step="any">
			<br>
			<button type="button" id="CALC_BASFI_BUTTON" onclick="javascript:calc_basfi()">
				Calculate BASFI
			</button>
			<br><br>
		</div>

		<div class="list" id="OUTCOME_TYPE_d">
				<label>* Choose your outcome target:</label>
				<select name="OUTCOME_TYPE" id="OUTCOME_TYPE">
				<option value="" <?php if (isset($_POST["OUTCOME_TYPE"]) && 	$_POST["OUTCOME_TYPE"] == "") echo 'selected';?>>--please select</option>
				<option value="MAJOR" <?php if (isset($_POST["OUTCOME_TYPE"]) && 	$_POST["OUTCOME_TYPE"] == 'MAJOR') echo 'selected';?>>Have a major response at 12 weeks</option>
				<option value="NR" <?php if (isset($_POST["OUTCOME_TYPE"]) && 	$_POST["OUTCOME_TYPE"] == 'NR') echo 'selected';?>>Have no response at 12 weeks</option>
				</select>
		</div><br>

		<input type="submit" name="sub" value="Submit" style="margin-left:20px" onclick="return validateForm()"> <input type="BUTTON" value="Reset" style="margin-left:20px" onclick="formReset()">

	<?php
  if($_REQUEST["sub"]){
      $path="query.py";
      $AGE = $_POST["AGE"];
      $GENDER = $_POST["Gender"];
      $CRP_L = $_POST["CRP_L"];
      $CRP_L2 = log((float)$CRP_L, 2.7182818284);
      if($CRP_L2 < 0.1){
	      $CRP_L2 = "0.1";
      }else{
	      $CRP_L2 = (string)log((float)$CRP_L, 2.7182818284);
      }
      $BMI = $_POST["BMI"];
      $PGA = $_POST["PGA"];
      $BASDAI_2 = $_POST["BASDAI_2"];
      $BASFI = $_POST["BASFI"];
      $OUTCOME_TYPE = $_POST["OUTCOME_TYPE"];
      if($OUTCOME_TYPE == "MAJOR"){
        $output = exec("python $path -model LR -target Major -tag ReducedModel -CRP_L $CRP_L2 -AGE $AGE -BMI $BMI -BASDAI_2 $BASDAI_2 -PGA $PGA -BASFI $BASFI", $ret);
      }elseif($OUTCOME_TYPE == "NR"){
        $output= exec("python $path -model LR -target NR -tag ReducedModel -CRP_L $CRP_L2 -AGE $AGE -BMI $BMI -BASDAI_2 $BASDAI_2 -PGA $PGA -BASFI $BASFI", $ret); //python may be python3
      }
    }
	?>

	<div class="output" id="output" style=font-size:20px>
				<br>
				<b>Result: <?php echo $output;?></b>
				<br>
	</div><br>
</fieldset><br>


<fieldset>
    Feel free to send your comments and questions:
  	<div>
  			<textarea name="MAIL_CONTENT" rows="5" cols="70" placeholder="Please enter your comments here"></textarea>
  			<input type="submit" value="Send" name="mail">
  		</form>
  	</div>
</fieldset>

<?php
if($_REQUEST["mail"]){
   $MAIL_CONTENT = $_POST["MAIL_CONTENT"];
   if($MAIL_CONTENT != "") {
    $Mail_account="predict.response@gmail.com";
    $Password="pr123456!";
    //PHPMailer Object
    $mail = new PHPMailer();
    // Settings
    $mail->IsSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host       = "ssl://smtp.gmail.com"; // SMTP server example
    //$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
    $mail->Username   = $Mail_account; // SMTP account username example
    $mail->Password   = $Password;        // SMTP account password example
    //From email address and name
    $mail->From = $Mail_account;
    $mail->FromName = "Commenter";
    //To address and name
    $mail->addAddress($Mail_account, "Reciver_Name");
    //Address to which recipient will reply
    //$mail->addReplyTo("spimtalin@gmail.com", "Reply");
    //Send HTML or Plain Text email
    $mail->isHTML(false);
    $mail->Subject = "Feedback";
    //$mail->Body = "<i>Mail body in HTML</i>";
    $mail->Body = $MAIL_CONTENT;
    if(!$mail->send())
    {
      echo '<script>alert("Something went wrong!!!")</script>';
    }
    else
    {
      echo '<script>alert("Thank you for your comments!")</script>';
    }
   }else{
	 echo '<script>alert("Please enter something!")</script>';
   }
}
?>

</body>

