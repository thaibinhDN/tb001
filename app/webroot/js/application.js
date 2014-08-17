$(document).ready(function() {
        
	var DOMAIN_URL = 'http://127.0.0.1/tb001/';
        console.log("welcome to main application.js");
       
        
        $('#paging_container').pajinate();
	$('.datepicker').datepicker({
	  	dateFormat:'yy-mm-dd',
	  	date: $('#datepicker').val(),
	  	current: $('#datepicker').val(),
	  	starts: 1,
	  	position: 'r',
	  	onBeforeShow: function(){
	    	$('#datepicker').DatePickerSetDate($('#datepicker').val(), true);
	  	},
	  	onChange: function(formated, dates){
	    	$('#datepicker').val(formated);
	    	if ($('#closeOnSelect input').attr('checked')) {
	      	$('#datepicker').DatePickerHide();
	    	}
	  	}
	});
	
        
        $(".select_type").change(function(e){
        
              if($(this).val() === "cessation"){
                  var attn = "<input type=\"text\" class = \"attn\" name=\"attn[]\" style=\"margin:0px 25px;height:35px\" value = \"\" size=\"8\" placeholder=\"  ATTN\"/>";
                  $(this).parent().append(attn);
                  $(this).parent().find($(this).parent().find($(".attn"))).insertBefore($(this).parent().find($(".remove_directors")));
              }else{
                  $(this).parent().find($(this).parent().find($(".attn"))).remove();
                  var attn = "<input type=\"hidden\" class = \"attn\" name=\"attn[]\" style=\"margin:0px 25px;height:35px\" value = \"\" size=\"8\" placeholder=\"  ATTN\"/>";
                  $(this).parent().append(attn);
             }
        });
        
        
        $(".browse").change(function(){
            //console.log($(this).val());
            var parent = $(this).parent();
            var grandpa = parent.parent();
            var sibling = grandpa.find($(".pathfield"));
            var path = $(this).val();
           
            sibling.val(path);
        });
        
	$('#directorField').change(function() {
		var director_id = this.value;
		var director_name = $(this).find(":selected").text();
                $('#director').val(director_id);
                $('#select option[value="'+director_id+'"]').text(director_name);
	});
        
       

    //add directors in create new directors view
    $( "#create_directors" ).unbind().bind("click",function(e) {
     
       
           var director_block = "<div class=\"new_director_block\">"
		+ "<div class=\"input text required\"><label for=\"DirectorName\">Name</label>"
                + "<input name=\"name[]\" class=\"form-control\" placeholder=\"e.g. John Doe\" maxlength=\"100\" type=\"text\" id=\"DirectorName\" required=\"required\"></div>"
                +"<div class=\"input text required\"><label for=\"DirectorNric\">NRIC</label><input name=\"nric[]\" class=\"form-control\" placeholder=\"e.g. S1234567Z\" maxlength=\"9\" type=\"text\" id=\"DirectorNric\" required=\"required\"></div>"
                +"<div class=\"input text required\"><label for=\"DirectorAddress1\">Address Line 1</label><input name=\"address_1[]\" class=\"form-control\" placeholder=\"e.g. 123 Bukit Batok Road\" maxlength=\"100\" type=\"text\" id=\"DirectorAddress1\" required=\"required\"></div>"
                +"<div class=\"input text required\"><label for=\"DirectorAddress2\">Address Line 2</label><input name=\"address_2[]\" class=\"form-control\" placeholder=\"e.g. Singapore 123456\" maxlength=\"50\" type=\"text\" id=\"DirectorAddress2\" required=\"required\"></div>"
                +"<div class=\"input text required\"><label for=\"DirectorNationality\">Nationality</label><input name=\"nationality[]\" class=\"form-control\" placeholder=\"e.g. Singaporean\" maxlength=\"50\" type=\"text\" id=\"DirectorNationality\" required=\"required\"></div>"
                
                +"<br>";
                +"<br>";
                +"</div>";
           $("#new_director_block").append(director_block);
        
           
        });

        
    
        //Add directors in appoint_assign view
        $( "#add_directors" ).click(function(e) {
  
       
            var new_row = "<div class=\"director-block\">" 
				
			
				+"<select name=\"director[]\" class=\"form-control\" >"
					+"<option value=\"\"> -- Select Director -- </option>"
				
				+ "</select>"
				+ "<select name=\"type[]\" class=\"form-control select_type\" style=\"margin:0px 25px;\">"
					+ "<option value=\"\" selected> -- Select Type -- </option>"
					+"<option value=\"appointment\">Appointment</option>"
					+"<option value=\"cessation\">Cessation</option>"
				+"</select>"
                                +"<button class=\"btn btn-danger remove_directors\" type=\"button\">Remove</button>"
			+"</div>";
            
            $("#container-director-block").append(new_row);
            $("#container-director-block").trigger('contentchanged');
         
        });

        
        $("#container-director-block ").bind('contentchanged', function() {
                var select_tag = $("#container-director-block .director-block:last-child").find("select[name='director[]']");
                var company_id = $("input[name='company'").val();
                var selections = "";
                $.get(DOMAIN_URL + "api/companies/"+company_id+"/directors", function( data ) {
                    json = JSON.parse(data);
                    console.log(json);
                    selections = '<option value="">-- Select Director --</value>';
                    for (var i = 0; i < json.length; i++) {
                            selections += '<option value="'+json[i]['StakeHolder']['id']+'">'+json[i]['StakeHolder']['name']+'</value>';
                     };
                    select_tag.html(selections);
                });
            $( ".remove_directors" ).click(function(e) {
                
                $(this).parent().parent().find($(this).parent()).remove();
                count--;
                if(count < 0){
                    count = 0;
                }
            });
       
        
        
        $(".select_type").unbind().change(function(e){
              if($(this).val() === "cessation"){
                  var attn = "<input type=\"text\" class = \"attn\" name=\"attn[]\" style=\"margin:0px 25px;height:35px\" value = \"\" size=\"8\" placeholder=\"  ATTN\"/>";
                  $(this).parent().append(attn);
                  $(this).parent().find($(this).parent().find($(".attn"))).insertBefore($(this).parent().find($(".remove_directors")));
              }else{
                  $(this).parent().find($(this).parent().find($(".attn"))).remove();
                  var attn = "<input type=\"hidden\" class = \"attn\" name=\"attn[]\" style=\"margin:0px 25px;height:35px\" value = \"\" size=\"8\" placeholder=\"  ATTN\"/>";
                  $(this).parent().append(attn);
             }
        });
         
        });
        
        
         
        
          if($("#trackNumberDirector").val() == 0){
            count = 1;
           console.log(count);
        }else{
            count=$("#trackNumberDirector").val(); 
            console.log(count);
        };
        
        
        
    //New js file
    //Generating form for different functions
   
    $("#functionField").on("change",function(){
        var function_id = $(this).val();
        $.get(DOMAIN_URL + "api/functions/" + function_id, function( data ) {
                    json = JSON.parse(data);
                    
                    $("form[id='generate_form']").attr("action",DOMAIN_URL+"FunctionCorps/"+json['FunctionCorps']['function_name']);
                    console.log( $("form[id='generate_form']").attr("action"));
                    
                });
    });      
});
        
