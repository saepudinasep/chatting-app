<?php
include('config/database_connection.php');

session_start();

$query = "
SELECT * FROM login 
WHERE user_id != '".$_SESSION['user_id']."' 
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '
<table class="table table-bordered ">
 <tr>
  <th width="70%">akun</td>
  <th width="20%">status</td>
  <th width="10%">chatting</td>
 </tr>
';

foreach($result as $row)
{
 $status = '';
 $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
 $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
 $user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
 if($user_last_activity > $current_timestamp)
 {
  $status = '<span class="badge badge-success">Online</span>';
 }
 else
 {
  $status = '<span class="badge badge-danger">Offline</span>';
 }
 $output .= '
 <tr>
  <td>'.$row['username'].' '.count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect).' '.fetch_is_type_status($row['user_id'], $connect).'</td>
  <td>'.$status.'</td>
  <td><button type="button" class="btn btn-success btn-sm start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">Chat</button></td>
 </tr>
 ';
}

$output .= '</table>';

echo $output;

?>