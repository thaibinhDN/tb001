<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<title>Styled input[type="file"]</title>
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300" rel="stylesheet" type="text/css">

<style>
     span{
	padding:4px 12px;
	border: 1px solid #ddd;
}
    </style>
</head>


<table class="table table-striped">
	<thead>
		<th>Document</th>
		<th>Status</th>
		<th>Time</th>
             
                
	</thead>
	<tbody>
            
            <?php for($i = 0;$i<count($documents);$i++){ ?>
            <!-- Data for filtering -->
       
            <tr>
                <td>
                    <?php 
                        echo $documents[$i]['Document']['description'];
                    ?></td>
                <td>  
                    <?php echo $documents[$i]['Status']['status']?>
                    
                    
                </td>
              <td>  
                 <?php echo $documents[$i]['DocumentStatus']['updated_at']?>
                </td>
                
            
            </tr>
            
        
            
            <?php }?>
            
            
	</tbody>
</table>
