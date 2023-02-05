<?php
//$json_content = shell_exec("python3.8 roz.py");
//sleep("2");
date_default_timezone_set('Europe/Prague');
$json_content = file_get_contents("http://127.0.0.1/rozvrh.json");
$json = json_decode($json_content, true);
$CLASSES = json_decode(file_get_contents("http://127.0.0.1/classes.json"));
//$CLASSES = array("A1","A2","A3","A4","I1","I2","I3","I4","N1","N2","N3","N4","O1","O2","O3","O4");

$CLASSES_cnt=count($CLASSES);
$time_str = (string)date("H:i");
$currentTime = strtotime($time_str);// //strtotime('10:39');//time();//date("h:i"); // 5:32
$hour_s = 0;
$ZEROhour = False;
$_T_I = True;
if ((int)$currentTime >= (int)strtotime('07:09')) {$hour_s = 0;$_T_I=False;}        // Start Hodin | Bereme v uvahu ze request muze byt zpozdeny
//if ((int)$currentTime >= (int)strtotime('07:55')) {$hour_s = 1;} // Vybereme prvni hodinu jako dalsi pri prichodu do skole
if ((int)$currentTime >= (int)strtotime('07:48')) {$hour_s = 1;$_T_I=False;} //               08:05
if ((int)$currentTime >= (int)strtotime('08:49')) {$hour_s = 2;$_T_I=False;} //               08:50
if ((int)$currentTime >= (int)strtotime('09:44')) {$hour_s = 3;$_T_I=False;} //               09:45
if ((int)$currentTime >= (int)strtotime('10:44')) {$hour_s = 4;$_T_I=False;} //               10:45
if ((int)$currentTime >= (int)strtotime('11:34')) {$hour_s = 5;$_T_I=False;} //               11:35
if ((int)$currentTime >= (int)strtotime('12:29')) {$hour_s = 6;$_T_I=False;} //               12:30
if ((int)$currentTime >= (int)strtotime('12:49')) {$hour_s = 7;$_T_I=False;} //               12:50
if ((int)$currentTime >= (int)strtotime('13:44')) {$hour_s = 8;$_T_I=False;} //               13:45
if ((int)$currentTime >= (int)strtotime('14:39')) {$hour_s = 9;$_T_I=False;} //               14:40
if ((int)$currentTime >= (int)strtotime('15:29')) {$hour_s = 10;$_T_I=False;} //              15:30
if ((int)$currentTime >= (int)strtotime('16:19')) {$hour_s = 11;$_T_I=False;} //              16:20
if ((int)$currentTime >= (int)strtotime('18:04')) {$hour_s = 12;$_T_I=False;} //              18:05
if (date("l") == "Saturday" || date("l") == "Sunday"){$hour_s = -1;$_T_I=False;}
//if ($hour_s===1){echo(null);}else{$hour_s++;}
if ($_T_I){$hour_s = -1;}
?>
<table class="table tbres">
  <thead>
    
    <tr><th style="width: 9.09%;">&nbsp;</th>
      <?php
      // Gonna generate columns
      $max_column = 0;
      for($x=0;$x<$CLASSES_cnt;$x++){ 
        foreach($json[$CLASSES[$x]] as $a_) {
          if ((int)$a_['hour'] == "0"){$ZEROhour = True;}
          if ((int)$a_['hour'] > $max_column){
            $max_column = (int)$a_['hour'];
          }
        }
      }
      if ($ZEROhour){
        for($x=0;$x<=$max_column;$x++){ 
          echo '<th style="width: 9.09%;font-size: 21px;"> '.$x.' </th>';
        }
      } else {
        for($x=1;$x<=$max_column;$x++){ 
          echo '<th style="width: 9.09%;font-size: 21px;"> '.$x.' </th>';
        }
      }
     
      ?>
      
      <!--<th style="width: 9.09%;font-size: 21px;"> 1 <div class="rh-timetable-time">8:05 <span class="rh-timetable-time-sep">–</span>08:50 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 2 <div class="rh-timetable-time">09:00 <span class="rh-timetable-time-sep">–</span>09:45 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 3 <div class="rh-timetable-time">10:00 <span class="rh-timetable-time-sep">–</span>10:45 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 4 <div class="rh-timetable-time">10:50 <span class="rh-timetable-time-sep">–</span>11:35 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 5 <div class="rh-timetable-time">11:45 <span class="rh-timetable-time-sep">–</span>12:30 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 6 <div class="rh-timetable-time">12:05 <span class="rh-timetable-time-sep">–</span>12:50 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 7 <div class="rh-timetable-time">13:00 <span class="rh-timetable-time-sep">–</span>13:45 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 8 <div class="rh-timetable-time">13:55 <span class="rh-timetable-time-sep">–</span>14:40 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 9 <div class="rh-timetable-time">14:45 <span class="rh-timetable-time-sep">–</span>15:30 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 10 <div class="rh-timetable-time">15:35 <span class="rh-timetable-time-sep">–</span>16:20 </div></th>-->
      <!--<th style="width: 9.09%;font-size: 21px;"> 11 <div class="rh-timetable-time">17:20 <span class="rh-timetable-time-sep">–</span>18:05 </div></th>-->
    </tr>
  </thead>
  <tbody>
    <?php
    for($x=0;$x<$CLASSES_cnt;$x++){
      $OMG_TABLE = 0;
      $MAIN_counter = 0;
      $JSON_counter = 0;
      $LAST_INSERTED_HOUR = 0;
      $INSERTED_COLUMNS = 0;
      //$resize_POINT = "0";
      $MULTI__BLOCKADE = 0;
      echo '<tr><th style="font-size: 25px;"> '.$CLASSES[$x].'</th>';
      $FIRST_HOUR_INSERT = True;
      foreach($json[$CLASSES[$x]] as $a_) {
        $hour = $a_['hour'];
        if ($a_['subject'] == "REMOVED"){
          //$MULTI__BLOCKADE = 1;
          $OMG_TABLE++;
          $INSERTED_COLUMNS++;
          if ($ZEROhour && $hour != "0" && $FIRST_HOUR_INSERT){ // Pokud existuje 0 hodina a prave ted prochazime prvni hodinu, ktera neni 0
            echo '<td>
              <div class="rh-timetable-control-lesson-container">
                <div class="rh-timetable-control-lesson-container-inner"></div>
              </div>
            </td>';
          }
          echo '<td class="removed">';
          $LAST_INSERTED_HOUR = $hour;
           echo '<div class="rh-timetable-control-lesson-content">
                      <span class="subject">
                          <span></span>
                      </span>
                      <span class="classroom">
                        <a> </a>
                      </span>
                      <!--<div class="second-row">
                        <span class="teacher"></span>
                      </div>-->
                 </div>
                  </td>';
            $FIRST_HOUR_INSERT = False;
        } else {
              $subject = $a_['subject'];
              $teacher = $a_['teacher'];
              $room = $a_['room'];
              $changed = $a_['changed'];
              $JSON_counter = $MAIN_counter + 1;
              $MAIN_counter++;
              $OMG_TABLE++;

            if ($MULTI__BLOCKADE == 0){ // Blokáda pro MUTLI Hodiny
               // For Group Check 
              $INSERTED_COLUMNS++;
              if ($ZEROhour && $hour != "0" && $FIRST_HOUR_INSERT){ // Pokud existuje 0 hodina a prave ted prochazime prvni hodinu, ktera neni 0
                echo '<td>
                  <div class="rh-timetable-control-lesson-container">
                    <div class="rh-timetable-control-lesson-container-inner"></div>
                  </div>
                </td>';
              }
              if ($hour_s == $hour){echo '<td class="future_subject">';}else{echo '<td>';}
                  $LAST_INSERTED_HOUR = $hour;
                  if ($changed == "True"){
                    echo '<div class="rh-timetable-control-lesson-container changed">';
                  } else {
                    echo '<div class="rh-timetable-control-lesson-container">';
                  }
                  echo '<div class="rh-timetable-control-lesson-container-inner">
                        <div class="rh-timetable-control-lesson" style="border-color:#41b13c">
                          <div class="rh-timetable-control-lesson-content">
                            <span class="subject">';
                             if ($hour_s == $hour){
                              echo '<span style="color:#0000CD;font-weight: bold;">'.$subject.'</span>';
                            } else {
                              echo '<span style="color:#41b13c;font-weight: bold;">'.$subject.'</span>';
                            }
                            echo '</span>
                            <span class="classroom">
                              <a> '.$room.' </a>
                            </span>';
                            // Kontrola GROUPovanych hodin [Jestli jsou data groupovany/hodina rozdelena do dvou skupin] tak se ucitel neukaze
                             //if (isset($json[$CLASSES[$x]][$JSON_counter]['hour'])){ // Pokud existuje dalsi hodina v row
                             // if ($json[$CLASSES[$x]][$JSON_counter]['hour'] !== $a_['hour']){ // Pokud dalsi hodina NENI stejna jako aktualni
                             //   echo '<div class="second-row">
                             //     <span class="teacher">'.$teacher.'</span>
                             //   </div>';
                             // }
                            //} else {
                            //  echo '<div class="second-row">
                            //      <span class="teacher">'.$teacher.'</span>
                            //    </div>';
                           // }
                            if ($changed == "True"){
                              echo '<div class="second-row">
                                 <span class="teacher" style="color:#000000;">'.$teacher.'</span>
                                </div>';
                            }
                            echo '</div>';
                          // Groupped Hours
                          if (isset($json[$CLASSES[$x]][$JSON_counter]['hour'])){
                            if ($json[$CLASSES[$x]][$JSON_counter]['hour'] == $a_['hour']){
                              //$resize_POINT = "1";
                              $hour = $json[$CLASSES[$x]][$JSON_counter]['hour'];
                              $subject = $json[$CLASSES[$x]][$JSON_counter]['subject'];
                              $teacher = $json[$CLASSES[$x]][$JSON_counter]['teacher'];
                              $room = $json[$CLASSES[$x]][$JSON_counter]['room'];
                              $changed = $json[$CLASSES[$x]][$JSON_counter]['changed'];
                              $MULTI__BLOCKADE = 1;
                              $OMG_TABLE--;
                              echo '<hr>';
                              //if ($changed == "True"){
                                //echo '<div class="rh-timetable-control-lesson-container changed">';
                             // } else {
                                echo '<div class="rh-timetable-control-lesson-container">';
                              //}
                                echo '<span class="subject">';
                                 if ($hour_s == $hour){
                                  echo '<span style="color:#0000CD;font-weight: bold;">'.$subject.'</span>';
                                } else {
                                  echo '<span style="color:#41b13c;font-weight: bold;">'.$subject.'</span>';
                                }
                               echo '</span>
                                <span class="classroom">
                                  <a> '.$room.' </a>
                                </span>';
                                if ($changed == "True"){
                                  echo '<div class="second-row">
                                  <span class="teacher" style="color:#000000">'.$teacher.'</span>
                                   </div>';
                                 }
                              echo '</div>';
                            }
                          }
                        echo '</div>
                      </div>
                    </div>
                  </td>';
                  $FIRST_HOUR_INSERT = False;
                } else {$MULTI__BLOCKADE = 0;}
        }
         
    }
    while($OMG_TABLE !== $max_column){
      $OMG_TABLE++;
      echo '<td>
        <div class="rh-timetable-control-lesson-container">
          <div class="rh-timetable-control-lesson-container-inner"></div>
        </div>
      </td>';
    }
    echo '</tr>';
  } // For
    ?>
    </tr>
  </tbody>
</table>
<style type="text/css">hr{margin: 0rem 0!important;}</style>

<?php
// Remove variables from memory we don't need
// We have to be sure that we do not overflow the RPi memory [Garbage collector]
unset($json_content);
unset($json);
unset($CLASSES);
unset($CLASSES_cnt);
unset($time_str);
unset($currentTime);
unset($hour_s);
unset($ZEROhour);
unset($max_column);
unset($OMG_TABLE);
unset($MAIN_counter);
unset($JSON_counter);
unset($FIRST_HOUR_INSERT);
unset($LAST_INSERTED_HOUR);
unset($INSERTED_COLUMNS);
unset($MULTI__BLOCKADE);
unset($hour);
unset($subject);
unset($teacher);
unset($room);
unset($changed);
unset($a_);
unset($x);
unset($_T_I);
?>