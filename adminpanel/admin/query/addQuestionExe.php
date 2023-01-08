<?php 
 include("../../../conn.php");
 include("../../../database.php");


extract($_POST);
$qn = 1;



$selQuest = $conn->query("SELECT * FROM exam_question_tbl WHERE exam_id='$examId' AND exam_question='$question' ");
if($selQuest->rowCount() > 0)
{
  $res = array("res" => "exist", "msg" => $question);
  
}
else
{
	$question_num = mysqli_query($con, "SELECT exam_question from exam_question_tbl where exam_id = $examId");
	$qstns = mysqli_num_rows($question_num);
if($qstns > 0)
{
  $qn = intval($qstns);
 
  $qn = intval($qn) + 1 ;
}
	$insQuest = $conn->query("INSERT INTO exam_question_tbl(exam_id,exam_question,exam_ch1,exam_ch2,exam_ch3,exam_ch4,exam_answer,qestion_number) VALUES('$examId','$question','$choice_A','$choice_B','$choice_C','$choice_D','$correctAnswer','$qn') ");

	if($insQuest)
	{
       $res = array("res" => "success", "msg" => $question);
	}
	else
	{
       $res = array("res" => "failed");
	}
}



echo json_encode($res);
 ?>