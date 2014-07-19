<h1>System Logs</h1>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Users</th>
            <th>Action</th>
            <th>Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($events as $event){
            $user = $event['User']['firstName']." ".$event['User']['lastName'];
            $action = $event['Event']['description'];
            $time = $event['Event']['created_time'];
                
        ?>
        <tr>
            <td><?php echo $user;?></td>
            <td><?php echo $action;?></td>
            <td><?php echo $time;?></td>
        </tr>
        <?php    
            }
        ?>
    </tbody>
</table>