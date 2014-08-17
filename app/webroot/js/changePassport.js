/*  var DOMAIN_URL = 'http://127.0.0.1/corp-sec/'
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    console.log("Welcome to changePassport.js");
   var DOMAIN_URL = 'http://127.0.0.1/tb001/';
    
    

    
    $("#add_stakeholders").click(function(e){
        var new_row = "<div class=\"stakeholder-block\">" 
				+ "<br />"
				+"<select name=\"stakeholder[]\" class=\"form-control\" >"
					+"<option value=\"\"> -- Select Stakeholder -- </option>"
				
				+ "</select>"
                                +"<input name=\"passportNo[]\" placeholder=\"Eg: B3478590P\" class=\"form-control\" required=\"required\"/> "
                                + "<select name=\"occupation[]\" class=\"form-control\" id=\"stakeholder\" required=\"required\">"
                                +    "<option value=\"\"> -- Select Occupation -- </option>"

                                 +   "<option value=\"Director\">Director</option>"
                                  +  "<option value=\"Secretary\">Secretary</option>"
                                   + "<option value=\"Auditor\">Auditor</option>"
                                    +"<option value=\"Shareholder\">Shareholder</option>"

                            + "</select>"
                                +"<button style=\"margin-left:10px\" class=\"btn btn-danger remove_stakeholders\" type=\"button\">Remove</button>"
			+"</div>";
            
            $("#container-stakeholder-block").append(new_row);
            $("#container-stakeholder-block").trigger('contentchanged');
    });
    
     $("#container-stakeholder-block ").bind('contentchanged', function() {
                var select_tag = $("#container-stakeholder-block .stakeholder-block:last-child").find("select[name='stakeholder[]']");
                var company_id = $("input[name='company'").val();
                var selections = "";
                $.get(DOMAIN_URL + "Companies/getStakeholders?id="+company_id, function( data ) {
                    
                    json = JSON.parse(data);
                    console.log(json);
                    selections = '<option value="">-- Select StakeHolder --</value>';
                    for (var i = 0; i < json.length; i++) {
                            selections += '<option value="'+json[i]['StakeHolder']['id']+'">'+json[i]['StakeHolder']['name']+'</value>';
                     };
                    select_tag.html(selections);
                });
            $( ".remove_stakeholders" ).click(function(e) {
                
                $(this).parent().parent().find($(this).parent()).remove();
            });
         
        });
   
});
