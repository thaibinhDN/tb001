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
<h1> Sales of Assets and Business </h1>
<br>
<br>

<table class="table table-striped">
	<thead>
		<th>Description</th>
		<th>Before Submission</th>
		<th>After Submission</th>
        <th></th>
                
	</thead>
	<tbody>
            
           <?php for($i = 0;$i<count($documents);$i++){ ?>
            <!-- Data for filtering -->
       
            <tr>
                <td>
                    <?php 
                            
                          $desc = "Sales of Business and Assets "."from ".$view_data[$i]['SalesAssetBusiness']['buyer'].
                            " to ".$view_data[$i]['SalesAssetBusiness']['seller']."with price of ".$view_data[$i]['SalesAssetBusiness']['price'];
                    ?>
                    <?php echo $desc;
                          
                    ?></td>
             
                <td>  
                 
                <?php if(!empty($view_data[$i]['Document']['before'])){?>
                        <?php echo $this->Html->link('Download', array('controller' => 'Documents', 'action' => 'downloadFile','?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'before','id' =>$view_data[$i]['Document']['id']))); ?>
                        <br />
                        <?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteFile', '?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'before','id' =>$view_data[$i]['Document']['id'])), array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?>
                        <br>
                        <input name="acra_before" class="form-control" value="<?php echo $view_data[$i]['Document']['acra_before'] ?>" disabled/>
                     <?php }else{?>
                        <?php echo $this->Form->create("Documents",array("action"=>"uploadBeforeSubmission",'type'=>'file'));
                    
                 ?>
                        <input class ="form-control pathfield" type="text" value="" disabled />
                         <input type="hidden" name="document_id" value="<?php echo $view_data[$i]['Document']['id']?>"/>
                         <div style="margin-top:12px">
                         <input class ="form-control" type="text" name="acra" value="" placeholder="ACRA" required/>
                         </div>
                        <input name = "submissionType" type="hidden" value="Before"/>
                     <br>
                     <?php echo $this->Form->input("Browse",array("type"=>"file","required"=>"required","class"=>"browse","div"=>array("class"=>"btn btn-default btn-file btn-xs browseWidth browseId "))) ?>
                       
                     <input type="hidden" name="function_id" value="<?php echo $functionCorp ?>"/>
                     <input type="hidden" name="company_id" value="<?php echo $company ?>"/> 
                     <input  class="btn btn-default btn-xs" type="submit"/>
                    
       
                     </form>
                     <?php }?>
                    
                </td>
              <td>  
                 
                  
                  <?php if(!empty($view_data[$i]['Document']['after'])){?>
                        <?php echo $this->Html->link('Download', array('controller' => 'Documents', 'action' => 'downloadFile', '?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'after','id' =>$view_data[$i]['Document']['id']))); ?>
                        <br />
                        <?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteFile', '?'=>array('company'=>$company,"function"=>$functionCorp,'type'=>'after','id' =>$view_data[$i]['Document']['id'])), array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?>
                        <br>
                        <input name="acra_after" class="form-control" value="<?php echo $view_data[$i]['Document']['acra_after'] ?>" disabled/>	
                     <?php }else{?>
                        <?php echo $this->Form->create("Documents",array("action"=>"uploadAfterSubmission",'type'=>'file'));
                    
                 ?>
                         <input class ="form-control pathfield" type="text" value="" disabled required/>
                         
                        <div style="margin-top:12px">
                         <input class ="form-control" name="acra" type="text" value="" placeholder="ACRA" required/>
                        </div>
                         <input name = "submissionType" type="hidden" value="after"/>
                     <br>
                     <?php echo $this->Form->input("Browse",array("type"=>"file","required"=>"required","class"=>"browse","div"=>array("class"=>"btn btn-default btn-file btn-xs browseWidth browseId "))) ?>
               
                     
                     <input type="hidden" name="function_id" value="<?php echo $functionCorp ?>"/>
                     <input type="hidden" name="company_id" value="<?php echo $company ?>"/> 
                     <input type="hidden" name="document_id" value="<?php echo $view_data[$i]['Document']['id']?>"/>
                     <input  class="btn btn-default btn-xs" type="submit"/>
                     
                     
                     </form>
                     <?php }?>
                </td>
                
                <td><?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteDocument','?'=>array('company'=>$company,"function"=>$functionCorp,'id' => $view_data[$i]['Document']['id'])),array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?></td>
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



