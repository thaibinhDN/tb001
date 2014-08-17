/*  var DOMAIN_URL = 'http://127.0.0.1/corp-sec/'
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    console.log("Welcome to increaseNonCashCapital");
   var DOMAIN_URL = 'http://127.0.0.1/tb001/';
        $( "#add_stakeholders" ).click(function(e) {
            var new_row = "<div class=\"stakeholder-block\">"
                    + "<label>Name of Allottee</label>" 
                                 +  "<select name=\"stakeholder[]\" class=\"form-control\" id=\"director\" style=\"margin-bottom:1em\">"
                                +  "</select><br>"
                                
                     + "<label>Shares Alloted</label><input style=\"margin-bottom:1em\" class=\"form-control\" name=\"SharesAlloted[]\" value=\"\" required/><br>"
                    +  "<label >Classes Of Shares</label><input style=\"margin-bottom:1em\"  value=\"ORDINARY\" class=\"form-control\" name=\"classes[]\"/><br>"
                    +  "<label >Shares Paid(Non Cash)</label><input  style=\"margin-bottom:1em\" class=\"form-control\" name=\"SharesNonCash[]\" value=\"\" required/><br>"
                    +  "<button style=\"margin-bottom:1em\" class=\"btn btn-danger remove_stakeholders\" type=\"button\">Remove</button>"
                    +  "</div>"
            
            $("#container-stakeholder-block").append(new_row);
            $("#container-stakeholder-block").trigger('contentchanged');
         
        });

        
        $("#container-stakeholder-block ").bind('contentchanged', function() {
                var select_tag = $("#container-stakeholder-block .stakeholder-block:last-child").find("select[name='stakeholder[]']");
                var company_id = $("input[name='company'").val();
                var selections = "";
                $.get(DOMAIN_URL + "Shareholders/getShareholders?id="+company_id, function( data ) {
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
