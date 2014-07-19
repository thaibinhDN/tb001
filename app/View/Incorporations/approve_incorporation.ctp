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
		<th>Company</th>
		<th>Created Time</th>
		<th>Action</th>
                <th>Status</th>

                
	</thead>
	<tbody>
            
           <?php for($i = 0;$i<count($companies);$i++){ ?>
            <!-- Data for filtering -->
       
            <tr>
                <td>

                    <?php echo $companies[$i]['Company']['name'];
                          
                    ?></td>
             
                <td>  
                 
                <?php echo $companies[$i]['Company']['created_at'] ?>
                    
                </td>
              <td>  
                 
                <?php echo $this->Html->link('Approve', array('controller' => 'Incorporations', 'action' => 'approveInc','?'=>array('id'=>$companies[$i]['Company']['company_id']))); ?>
                 
                </td>
                
                <td>
                    <?php if($companies[$i]['Company']['Approved'] == 0){ 
                            echo "Waiting for approval"; }else{ echo "Approved"."<br>"?>
                    <input data-toggle="collapse" data-target="<?php echo "#dateAndNo".$i?>" type="button" class="form-control enterDateAndComNo" value="Input Date&Number"> 
                    <div class="collapse" id="<?php echo "dateAndNo".$i ?>">
                        <?php echo $this->Form->create("Incorporation",array("action"=>"saveDateAndNo")); ?>
                         <input type="texy" class="form-control" name="dateOfIncorporation" placeholder="Date Of Incorporation" value="<?php echo $companies[$i]['Company']['Date_Of_Inc'] ?>"> 
                         <input type="text" class="form-control" name="Company_Number" placeholder="Company Number" value="<?php echo $companies[$i]['Company']['register_number'] ?>"> 
                         <input name="company_id" type="hidden" value="<?php echo $companies[$i]['Company']['company_id'] ?>">
                         <input type="submit" class="form-control" value="submit"></input>
                    </form>
                    </div>
                            <?php } ?>
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



