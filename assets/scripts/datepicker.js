(function($) {
    $(function() {
        // Check to make sure the input box exists
        if( 0 < $('#datepicker').length ) {
            $('#datepicker').datepicker();
        }
    });
}(jQuery));

// jQuery(document).ready(function($){
 //    var i=1;
 //    $("#add_row").click(function(){
 //    	$('#addr'+i).html("<td>"+ (i+1) +"</td><td><input name='name"+i+"' type='text' placeholder='Name' class='form-control input-md'  /> </td><td><input  name='mail"+i+"' type='text' placeholder='Mail'  class='form-control input-md'></td><td><input  name='mobile"+i+"' type='text' placeholder='Mobile'  class='form-control input-md'></td>");

 //    	$('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
 //     	i++; 
	// });
	// $("#delete_row").click(function(){
 //    	if(i>1){
	// 		$("#addr"+(i-1)).html('');
	// 		i--;
	// 	}
	// });
// });

// jQuery(document).ready(function($){
// 	var i=1;
// 	var row_count = 1;
//     $("#add_row").click(function(){
//     	$('#tab_logic').append('<tr id="addr'+i+'"></tr>');
//     	$('#addr'+i).html("<td data-name='num'><input type='number' name='num"+i+"' value='"+(i+1)+"' style='width: 50px'></td>\
// 							<td data-name='name'>\
// 								<input type='text' name='name"+i+"'  placeholder='Продукт' class='form-control' />\
// 							</td>\
// 							<td data-name='quantity'>\
// 								<input type='text' name='quantity"+i+"' placeholder='Количество' class='form-control'/>\
// 							</td>\
// 							<td data-name='measure'>\
// 								<select>\
// 									<option value''>Мярка</option>\
// 									<option value'1'>бр.</option>\
// 									<option value'2'>кг.</option>\
// 								</select>\
// 							</td>\
// 							<td data-name='price'>\
// 								<input type='text' name='price"+i+"' placeholder='Ед. цена' class='form-control'/>\
// 							</td>\
// 							<td data-name='del'>\
// 								<button name='del"+i+"' class='btn btn-danger row-remove'>x</button>\
// 							</td>"	);
//      	i++; 
//      	row_count++;
//      	$('input#invoice_item_column_number').val(row_count);
// 	});


//     $(document).on('click', '.row-remove', function() {
//         var $row = $(this).closest('tr');
//         $row.remove();

//         row_count--;
//         $('input#invoice_item_column_number').val(row_count);
//     });

//     // var count_rows = $('.invoice_item_form tr').length;
//     $('input#invoice_item_column_number').val(i);

// });

// jQuery(document).ready(function($) {
//     $("#add_row").on("click", function() {
//         // Dynamic Rows Code
        
//         // Get max row id and set new id
//         var newid = 0;
//         $.each($("#tab_logic tr"), function() {
//             if (parseInt($(this).data("id")) > newid) {
//                 newid = parseInt($(this).data("id"));
//             }
//         });
//         newid++;
        
//         var tr = $("<tr></tr>", {
//             id: "addr"+newid,
//             "data-id": newid
//         });
        
        // loop through each td and create new elements with name of newid
        // $.each($("#tab_logic tbody tr:nth(0) td"), function() {
        //     var cur_td = $(this);
            
        //     var children = cur_td.children();
            
        //     // add new td and element if it has a name
        //     if ($(this).data("name") != undefined) {
        //         var td = $("<td></td>", {
        //             "data-name": $(cur_td).data("name")
        //         });
                
        //         var c = $(cur_td).find($(children[0]).prop('tagName')).clone().val("");
        //         c.attr("name", $(cur_td).data("name") + newid);
        //         c.appendTo($(td));
        //         td.appendTo($(tr));
        //     } else {
        //         var td = $("<td></td>", {
        //             'text': $('#tab_logic tr').length
        //         }).appendTo($(tr));
        //     }
        // });
        
        // // add the new row
        // $(tr).appendTo($('#tab_logic'));
        
//         $(tr).find("td button.row-remove").on("click", function() {
//              $(this).closest("tr").remove();
//         });
// 	});

//     $("#add_row").trigger("click");
// });