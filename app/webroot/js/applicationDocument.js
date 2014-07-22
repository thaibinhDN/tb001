/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    console.log("Welcome applicationIncorporation.js");
    var DOMAIN_URL = 'http://127.0.0.1/tb001/';
    $("#functionField").on("change",function(){
            
            var function_id = $(this).val();
            $.get(DOMAIN_URL + "api/functions/" + function_id, function( data ) {
                        json = JSON.parse(data);
                        $("form[id='view_doc']").attr("action",DOMAIN_URL+"Documents/"+json['FunctionCorps']['function_name']);
                        console.log($("form[id='view_doc']").attr("action"));
                    });
        });

});