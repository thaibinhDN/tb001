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
<h1><?php echo $header?></h1>
<br>
<div>
 <?php echo $this->Form->create("Documents",array("action"=>"appointResignDirector"));?>
 <select name="director[]" class="form-control" id="director">
<option value="" selected>--Select Director--</option>

<?php foreach($directors as $director){ ?>
    
        <option value="<?php echo $director['Director']['director_id'] ?>"><?php echo $director['Director']['name'] ?> </option>
    
<?php }?>
</select> 
<input type="hidden" name="function_id" value="<?php echo $functionCorp ?>"/>
<input type="hidden" name="company_id" value="<?php echo $company ?>"/>
<button class="btn btn-small btn-primary" type="submit" id="Search">Search</button>
</form>
</div>
<br>
<br>
<table class="table table-striped">
	<thead>
		<th>Document</th>
		<th>Before Submission</th>
		<th>After Submission</th>
                <th></th>
                
	</thead>
	<tbody>
            
            <?php foreach($documents as $document){ ?>
            <!-- Data for filtering -->
       
            <tr>
                <td>
                    <?php $name_components = explode("_", $document['Document']['name']);
                           //ChromePhp::log($name_components);
                           unset($name_components[count($name_components)-1]);
                           $name="";
                           foreach($name_components as $component){
                               $name.=$component." ";
                           };
                          $name.=" (".$document['Director']['name']." ".$document['Document']['created_at'].") ";
                    ?>
                    <?php echo $name;
                          
                    ?></td>
                <td>  
                 
                <?php if(!is_null($document['DocumentDirector']['before'])){?>
                        <?php echo $this->Html->link('Download', array('controller' => 'Documents', 'action' => 'downloadDocument','?'=>array('type'=>'before','id' => $document['DocumentDirector']['id']))); ?>
                        <br />
                        <?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteDocument', '?'=>array('type'=>'before','id' => $document['DocumentDirector']['id'])), array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?>
				
                     <?php }else{?>
                        <?php echo $this->Form->create("Documents",array("action"=>"uploadBeforeSubmission",'type'=>'file'));
                    
                 ?>
                        <input class ="form-control pathfield" type="text" value="" disabled/>
                         <input type="hidden" name="document_director_id" value="<?php echo $document['DocumentDirector']['id']?>"/>
                        <input name = "submissionType" type="hidden" value="Before"/>
                     <br>
                     <?php echo $this->Form->input("Browse",array("type"=>"file","class"=>"browse","div"=>array("class"=>"btn btn-default btn-file btn-xs browseWidth browseId "))) ?>
                       
                   
                     
                     <input  class="btn btn-default btn-xs" type="submit"/>
                    
       
                     </form>
                     <?php }?>
                    
                </td>
              <td>  
                 
                  
                  <?php if(!is_null($document['DocumentDirector']['after'])){?>
                        <?php echo $this->Html->link('Download', array('controller' => 'Documents', 'action' => 'downloadDocument', '?'=>array('type'=>'after','id' => $document['DocumentDirector']['id']))); ?>
                        <br />
                        <?php echo $this->Html->link('Delete', array('controller' => 'Documents', 'action' => 'deleteDocument', '?'=>array('type'=>'after','id' => $document['DocumentDirector']['id'])), array('escape' => false, 'confirm' => 'Are you sure you want to delete this form?')); ?>
				
                     <?php }else{?>
                        <?php echo $this->Form->create("Documents",array("action"=>"uploadAfterSubmission",'type'=>'file'));
                    
                 ?>
                         <input class ="form-control pathfield" type="text" value="" disabled/>
                         <input name = "document_id" type="hidden" value="<?php echo $document['Document']['id'] ?>"/>
                         <input name = "submissionType" type="hidden" value="after"/>
                     <br>
                     <?php echo $this->Form->input("Browse",array("type"=>"file","class"=>"browse","div"=>array("class"=>"btn btn-default btn-file btn-xs browseWidth browseId "))) ?>
                     <input  class="btn btn-default btn-xs" type="submit"/>
                   
                     <input type="hidden" name="document_director_id" value="<?php echo $document['DocumentDirector']['id']?>"/>
                     </form>
                     <?php }?>
                </td>
                
                <td><?php echo $this->Html->link('Delete', array('controller' => 'DocumentDirectors', 'action' => 'deleteDocument','?'=>array('id' => $document['DocumentDirector']['id']))); ?></td>
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



