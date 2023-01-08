<?php
 $tt;
  include_once 'database.php';
  session_start();
  $email=$_SESSION['email'];

  if(isset($_SESSION['key']))
  {
    if(@$_GET['demail'] && $_SESSION['key']=='admin') 
    {
      $demail=@$_GET['demail'];
      $r1 = mysqli_query($con,"DELETE FROM rank WHERE email='$demail' ") or die('Error');
      $r2 = mysqli_query($con,"DELETE FROM history WHERE email='$demail' ") or die('Error');
      $result = mysqli_query($con,"DELETE FROM user WHERE email='$demail' ") or die('Error');
      header("location:dashboard.php?q=1");
    }
  }

  if(isset($_SESSION['key']))
  {
    if(@$_GET['q']== 'rmquiz' && $_SESSION['key']=='admin') 
    {
      $eid=@$_GET['eid'];
      $result = mysqli_query($con,"SELECT * FROM questions WHERE eid='$eid' ") or die('Error');
      while($row = mysqli_fetch_array($result)) 
      {
        $qid = $row['qid'];
        $r1 = mysqli_query($con,"DELETE FROM options WHERE qid='$qid'") or die('Error');
        $r2 = mysqli_query($con,"DELETE FROM answer WHERE qid='$qid' ") or die('Error');
      }
      $r3 = mysqli_query($con,"DELETE FROM questions WHERE eid='$eid' ") or die('Error');
      $r4 = mysqli_query($con,"DELETE FROM quiz WHERE eid='$eid' ") or die('Error');
      $r4 = mysqli_query($con,"DELETE FROM history WHERE eid='$eid' ") or die('Error');
      header("location:dashboard.php?q=5");
    }
  }

  if(isset($_SESSION['key']))
  {
    if(@$_GET['q']== 'addquiz' && $_SESSION['key']=='admin') 
    {
      $name = $_POST['name'];
      $name= ucwords(strtolower($name));
      $total = $_POST['total'];
      $sahi = $_POST['right'];
      $wrong = $_POST['wrong'];
      $id=uniqid();
      $q3=mysqli_query($con,"INSERT INTO quiz VALUES  ('$id','$name' , '$sahi' , '$wrong','$total', NOW())");
      header("location:dashboard.php?q=4&step=2&eid=$id&n=$total");
    }
  }

  if(isset($_SESSION['key']))
  {
    if(@$_GET['q']== 'addqns' && $_SESSION['key']=='admin') 
    {
      $n=@$_GET['n'];
      $eid=@$_GET['eid'];
      $ch=@$_GET['ch'];
      for($i=1;$i<=$n;$i++)
      {
        $qid=uniqid();
        $qns=$_POST['qns'.$i];
        $q3=mysqli_query($con,"INSERT INTO questions VALUES  ('$eid','$qid','$qns' , '$ch' , '$i')");
        $oaid=uniqid();
        $obid=uniqid();
        $ocid=uniqid();
        $odid=uniqid();
        $a=$_POST[$i.'1'];
        $b=$_POST[$i.'2'];
        $c=$_POST[$i.'3'];
        $d=$_POST[$i.'4'];
        $qa=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$a','$oaid')") or die('Error61');
        $qb=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$b','$obid')") or die('Error62');
        $qc=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$c','$ocid')") or die('Error63');
        $qd=mysqli_query($con,"INSERT INTO options VALUES  ('$qid','$d','$odid')") or die('Error64');
        $e=$_POST['ans'.$i];
        switch($e)
        {
          case 'a': $ansid=$oaid; break;
          case 'b': $ansid=$obid; break;
          case 'c': $ansid=$ocid; break;
          case 'd': $ansid=$odid; break;
          default: $ansid=$oaid;
        }
        $qans=mysqli_query($con,"INSERT INTO answer VALUES  ('$qid','$ansid')");
      }
      header("location:dashboard.php?q=0");
    }
  }

  if(@$_GET['q']== 'exam_tbl' && @$_GET['step']== 2) 
  {
    $eid=@$_GET['eid'];
    $sn=@$_GET['n'];
    $total=@$_GET['t'];
    @$ans1=$_POST['ans1'];
    @$ans2=$_POST['ans2'];
    @$ans3=$_POST['ans3'];
    @$ans4=$_POST['ans4'];
    $qid=@$_GET['qid'];
    $q=mysqli_query($con,"SELECT * FROM exam_question_tbl WHERE eqt_id='$qid' " );
    while($row=mysqli_fetch_array($q) )
    {  $ansid=$row['exam_answer']; }

 
    if($ans1 == $ansid || $ans2 == $ansid || $ans3 == $ansid || $ans4 == $ansid)
    {
      // $q=mysqli_query($con,"SELECT * FROM exam_tbl WHERE ex_id='$eid' " );
      // while($row=mysqli_fetch_array($q) )
      // {
      //   $sahi=1;
      // }
      if($sn == 1)
      {
        $q=mysqli_query($con,"INSERT INTO history (email,eid,score,level,sahi,wrong,date) VALUES('$email','$eid' ,'0','0','1','0',NOW())")or die('Error');
      }
      $q1=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' ")or die('Error115');
      while($row=mysqli_fetch_array($q1) )
      {
        $s=$row['score'];
        $r=$row['sahi'];
      }
      // $r++;
      // $s+=1;
      $q2=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`sahi`=$r, date= NOW()  WHERE  email = '$email' AND eid = '$eid'")or die('Error124');
    } 
   
    else
    {
      // $q=mysqli_query($con,"SELECT * FROM exam_tbl WHERE ex_id='$eid' " )or die('Error129');
      // while($row=mysqli_fetch_array($q) )
      // {
      //   $wrong=1;
      // }
      if($sn == 1)
      {
        $q=mysqli_query($con,"INSERT INTO history (email,eid,score,level,sahi,wrong,date)VALUES('$email','$eid' ,'0','0','0','1',NOW() )")or die('Error137');
      }
      else{
        $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email' " )or die('Error139');
      while($row=mysqli_fetch_array($q) )
      {
        $s=$row['score'];
        $w=$row['wrong'];
      }
      $w++;
      $s=$sn-1;
      $tt = $w;
      $q=mysqli_query($con,"UPDATE `history` SET `score`=$s,`level`=$sn,`wrong`=$w, date=NOW() WHERE  email = '$email' AND eid = '$eid'")or die('Error147');
      
      }
    }
    if($sn != $total)
    {
      $sn++;
      header("location:welcome.php?q=exam_tbl&step=2&eid=$eid&n=$sn&t=$total")or die('Error152');
    }
    else if( $_SESSION['key']!='suryapinky')
    {
      $q=mysqli_query($con,"SELECT * FROM history WHERE eid='$eid' AND email='$email'" )or die('Error156');
      while($row=mysqli_fetch_array($q) )
      {
        $wrong = $row['wrong'];
        $level = $row['level'];
        $score= $level - $wrong;
        $right = $level - $wrong;
        $score_update=mysqli_query($con,"UPDATE `history` SET `score`=$score, `sahi` = $right WHERE  email = '$email' AND eid = '$eid'")or die('Error123');


      }
      $q=mysqli_query($con,"SELECT * FROM rank WHERE email='$email'" )or die('Error161');
      $rowcount=mysqli_num_rows($q);
      if($rowcount == 0)
      {
        $q2=mysqli_query($con,"INSERT INTO `rank` (email,score,time) VALUES('$email','$score',NOW())")or die('Error165');
      }
      else
      {
        while($row=mysqli_fetch_array($q) )
        {
          $sun=$row['score'];
        }
        $sun=$s;
        $q=mysqli_query($con,"UPDATE `rank` SET `score`=$score ,time=NOW() WHERE email= '$email'")or die('Error174');
      }
      header("location:welcome.php?q=result&eid=$eid");
    }
    else
    {
      header("location:welcome.php?q=result&eid=$eid");
    }
  }

  if(@$_GET['q']== 'quizre' && @$_GET['step']== 25 ) 
  {
    $eid=@$_GET['eid'];
    $n=@$_GET['n'];
    $t=@$_GET['t'];
    $q=mysqli_query($con,"SELECT score FROM history WHERE eid='$eid' AND email='$email'" )or die('Error156');
    while($row=mysqli_fetch_array($q) )
    {
      $s=$row['score'];
    }
    $q=mysqli_query($con,"DELETE FROM `history` WHERE eid='$eid' AND email='$email' " )or die('Error184');
    $q=mysqli_query($con,"SELECT * FROM rank WHERE email='$email'" )or die('Error161');
    while($row=mysqli_fetch_array($q) )
    {
      $sun=$row['score'];
    }
    $sun=$sun-$s;
    $q=mysqli_query($con,"UPDATE `rank` SET `score`=$sun ,time=NOW() WHERE email= '$email'")or die('Error174');
    header("location:welcome.php?q=quiz&step=2&eid=$eid&n=1&t=$t");
  }
?>



