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
		<th>Details</th>
             
                
	</thead>
	<tbody>
            
            <?php for($i = 0;$i<count($documents);$i++){ ?>
            <!-- Data for filtering -->
       
            <tr>
                <td>
                    <?php 
                        echo $documents_withInfo[$i]['Document']['description'];
                    ?></td>
                <td>  
                    <?php echo $documents_withInfo[$i]['currentStatus']['Status']['status']?>
                    
                    <form  method="post" action="<?php echo $this->Html->url(array('controller' => 'Documents', 'action' => 'updateStatus'))?>" accept-charset="utf-8">
                        <input type="hidden" name="company" value="<?php echo $company ?>" />
                        <input type="hidden" name="functionCorp" value="<?php echo $functionCorp ?>" />
                        <input style="margin-bottom:5px" name="document_id" type="hidden" value="<?php echo $documents[$i]['Document']['id'] ?>" />
                       <select style="margin-bottom:5px" name="chosen_action" class="form-control" required="required">
                           <option value="" selected>------Action------</option>
                            <?php
                            for($j = 0; $j < count($documents_withInfo[$i]['actions']);$j++){ 
                                $action = $documents_withInfo[$i]['actions'][$j];
                                if($j == count($documents_withInfo[$i]['actions'])-1){?>
                                    
                                    <option value="<?php echo $action['Status']['id'] ?>" selected><?php echo $action['Status']['action'] ?></option>
                                <?php } else {
                                ?>       
                                <option value="<?php echo $action['Status']['id'] ?>"><?php echo $action['Status']['action'] ?></option>
                            <?php }}?>

                        </select><br>
                                <input name="next_action" type="hidden" value="<?php echo $documents_withInfo[$i]['actions'][count($documents_withInfo[$i]['actions'])-1]['Status']['id']  ?>"/>
                               <input type="submit" value="submit" class="form-control"/>     
                    </form>
                </td>
              <td>  
                 
                 <?php echo $this->Html->link("Details",array('controller' => 'Documents', 'action' => 'trackingDetails',"?"=>array("id"=>$documents_withInfo[$i]['Document']['id'])))?>
                </td>
                
            
            </tr>
            
        
            
            <?php }?>
            
            
	</tbody>
</table>
<div id="paging"/>
<?php
// Shows the page numbers
echo $this->Paginator->numbers(array("separator"=>""));

// Shows the next and previous links
echo $this->Paginator->prev(
  '« Previous',
  null,
  null,
  array('class' => 'disabled')
);
echo $this->Paginator->next(
  'Next »',
  null,
  null,
  array('class' => 'disabled')
);

// prints X of Y, where X is current page and Y is number of pages
echo $this->Paginator->counter();
?>
</div>



