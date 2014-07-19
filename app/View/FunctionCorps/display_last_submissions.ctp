<h1>Last Submissions</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Users</th>
            <th>Action</th>
            <th>View | Edit</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
       
        foreach($events as $event){
            $user = $event['User']['firstName']." ".$event['User']['lastName'];
            $action = $event['Event']['description'];
            $content_id = $event['Event']['id'];
            $time = $event['Event']['created_time'];
                
        ?>
        <tr>
            <td><?php echo $user;?></td>
            <td><?php echo $action;?></td>
            <td><?php echo $this->Html->link("Submission",array("action"=>"preview".$event['Event']['function_id'],"?"=>array(
                    "id"=>$content_id,
                    "company"=>$event['Event']['company_id'],
                    "function_id"=>$event['Event']['function_id'],
                    "edit"=>"y"
            ))) ?></td>
            <td><?php echo $time;?></td>
        </tr>
        <?php    
            }
        ?>
    </tbody>
</table>