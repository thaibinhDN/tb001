/*  var DOMAIN_URL = 'http://127.0.0.1/corp-sec/'
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    console.log("Welcome to addShareHolder.js");
    var DOMAIN_URL = 'http://127.0.0.1/corp-sec/';
    
    
    $("#stakeholder").on("change",function(e){
        console.log($(this).val());
    });
    
    $("#add_shareholders").click(function(e){
        var new_row = "<div class=\"shareholder-block\">" 
				+ "<br />"
				+"<select name=\"shareholder[]\" class=\"form-control\" >"
					+"<option value=\"\"> -- Select ShareHolder -- </option>"
				
				+ "</select>"
                                +"<button style=\"margin-left:10px\" class=\"btn btn-danger remove_shareholders\" type=\"button\">Remove</button>"
			+"</div>";
            
            $("#container-shareholder-block").append(new_row);
            $("#container-shareholder-block").trigger('contentchanged');
    });
    
     $("#container-shareholder-block ").bind('contentchanged', function() {
                var select_tag = $("#container-shareholder-block .shareholder-block:last-child").find("select[name='shareholder[]']");
                var company_id = $("input[name='company'").val();
                var selections = "";
                $.get(DOMAIN_URL + "Shareholders/getShareholders?id="+company_id, function( data ) {
                    json = JSON.parse(data);
                    console.log(json);
                    selections = '<option value="">-- Select ShareHolder --</value>';
                    for (var i = 0; i < json.length; i++) {
                            selections += '<option value="'+json[i]['StakeHolder']['id']+'">'+json[i]['StakeHolder']['name']+'</value>';
                     };
                    select_tag.html(selections);
                });
            $( ".remove_shareholders" ).click(function(e) {
                
                $(this).parent().parent().find($(this).parent()).remove();
            });
         
        });
    $("#add_directors").click(function(e){
        var new_row = "<div class=\"director-block\">" 
				+ "<br />"
				+"<select name=\"director[]\" class=\"form-control\" >"
					+"<option value=\"\"> -- Select Director -- </option>"
				
				+ "</select>"
                                +"<button style=\"margin-left:10px\" class=\"btn btn-danger remove_directors\" type=\"button\">Remove</button>"
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
            });
         
        });
     $("input[name='new_shareholder']").click(function(){
            if($(this).val()==="update"){
                $("form").attr("action","updateShareHolder");
                $("input[name='name']").removeAttr("required");
                $("input[name='nric']").removeAttr("required");
                $("input[name='address_1']").removeAttr("required");
                $("input[name='address_2']").removeAttr("required");
                $("input[name='nationality']").removeAttr("required");
                 $("select[name='director']").attr("required","required");
        
            }else{
                $("form").attr("action","addShareHolder");
                $("input[name='name']").attr("required","required");
                $("input[name='nric']").attr("required","required");
                $("input[name='address_1']").attr("required","required");
                $("input[name='address_2']").attr("required","required");
                $("input[name='nationality']").attr("required","required");
                 $("select[name='director']").removeAttr("required");
                
                //console.log($("form").attr("action"));
            }
     });
});
