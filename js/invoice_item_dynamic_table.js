jQuery(document).ready(function($){
	var i=1;
	var row_count = 1;
    $("#add_row").click(function(){
    	$('#tab_logic').append('<tr id="addr'+i+'"></tr>');
    	$('#addr'+i).html("<td><input type='number' name='num"+i+"' value='"+(i+1)+"' style='width: 50px'></td>\
							<td>\
								<input type='text' name='name"+i+"'  placeholder='Продукт' class='form-control' />\
							</td>\
							<td>\
								<input type='text' name='quantity"+i+"' placeholder='Количество' class='form-control'/>\
							</td>\
							<td>\
								<select>\
									<option value''>Мярка</option>\
									<option value'1'>бр.</option>\
									<option value'2'>кг.</option>\
								</select>\
							</td>\
							<td>\
								<input type='text' name='price"+i+"' placeholder='Ед. цена' class='form-control'/>\
							</td>\
							<td>\
								<button name='del"+i+"' class='btn btn-danger row-remove'>x</button>\
							</td>"	);
     	i++; 
     	row_count++;
     	$('input#invoice_item_column_number').val(row_count);
	});


    $(document).on('click', '.row-remove', function() {
        var $row = $(this).closest('tr');
        $row.remove();

        row_count--;
        $('input#invoice_item_column_number').val(row_count);
    });

    $('input#invoice_item_column_number').val(i);

});