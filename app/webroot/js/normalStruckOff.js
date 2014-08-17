/*  var DOMAIN_URL = 'http://127.0.0.1/corp-sec/'
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    console.log("Welcome to NormalStruckOff");
var DOMAIN_URL = 'http://127.0.0.1/tb001/';
        $( "#add_stakeholders" ).click(function(e) {
            var new_row = "<div class=\"stakeholder-block\">" 
				+ "<br />"
				+"<select name=\"stakeholder[]\" class=\"form-control\" >"
					+"<option value=\"\"> ---Select--- </option>"
				
				+ "</select>"
				+ "<input class=\"form-control\" name=\"shareAmount[]\" value=\"\" required></input>"
                                +"<input  class=\"form-control\" name=\"witness[]\" value=\"\"  required></input>"
                                +"<button class=\"btn btn-danger remove_directors\" type=\"button\">Remove</button>"
			+"</div>";
            
            $("#container-stakeholder-block").append(new_row);
            $("#container-stakeholder-block").trigger('contentchanged');
         
        });

        
        $("#container-stakeholder-block ").bind('contentchanged', function() {
                var select_tag = $("#container-stakeholder-block .stakeholder-block:last-child").find("select[name='stakeholder[]']");
                var company_id = $("input[name='company'").val();
                var selections = "";
                $.get(DOMAIN_URL + "Shareholders/getShareholdersDirectors?id=" + company_id, function( data ) {
                    json = JSON.parse(data);
                    selections = '<option value="">---Select---</value>';
                    for (var i = 0; i < json.length; i++) {
                            selections += '<option value="'+json[i]['StakeHolder']['id']+'">'+json[i]['StakeHolder']['name']+'</value>';
                     };
                    select_tag.html(selections);
                });
            $( ".remove_directors" ).click(function(e) {                
                $(this).parent().parent().find($(this).parent()).remove();
            });
       

         
        });
        

});
